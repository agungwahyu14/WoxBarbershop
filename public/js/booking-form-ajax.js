/**
 * WOX Barbershop - Booking Form AJAX Handler
 * Menangani form reservasi dengan AJAX dan prevent default
 */

$(document).ready(function () {

    // AJAX Setup untuk CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * AJAX Form Submission dengan prevent default
     */
    $('#booking-form').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        // Clear previous error messages
        clearFormErrors();

        // Show loading state
        showLoadingState();

        // Get form data
        const formData = new FormData(this);

        // AJAX request
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                handleSuccessResponse(response);
            },
            error: function (xhr) {
                handleErrorResponse(xhr);
            }
        });
    });

    /**
     * Clear previous error messages and styling
     */
    function clearFormErrors() {
        $('.text-red-600').remove();
        $('.border-red-500').removeClass('border-red-500');
        $('input, select, textarea').removeClass('border-red-500');
    }

    /**
     * Show loading state on submit button
     */
    function showLoadingState() {
        const submitBtn = $('#submit-booking');
        const submitText = $('#submit-text');
        const loadingText = $('#loading-text');

        submitBtn.prop('disabled', true);
        submitText.addClass('hidden');
        loadingText.removeClass('hidden');
    }

    /**
     * Hide loading state on submit button
     */
    function hideLoadingState() {
        const submitBtn = $('#submit-booking');
        const submitText = $('#submit-text');
        const loadingText = $('#loading-text');

        submitBtn.prop('disabled', false);
        submitText.removeClass('hidden');
        loadingText.addClass('hidden');
    }

    /**
     * Handle successful AJAX response
     */
    function handleSuccessResponse(response) {
        hideLoadingState();

        if (response.success) {
            // Success notification with booking details
            Swal.fire({
                icon: 'success',
                title: 'Reservasi Berhasil! 🎉',
                html: `
                    <div class="text-left">
                        <p class="mb-2"><strong>Nama:</strong> ${response.data.name}</p>
                        <p class="mb-2"><strong>Nomor Antrian:</strong> <span class="text-2xl font-bold text-green-600">${response.data.queue_number}</span></p>
                        <p class="mb-2"><strong>Tanggal & Waktu:</strong> ${response.data.date_time}</p>
                        <p class="mb-2"><strong>Layanan:</strong> ${response.data.service_name}</p>
                    </div>
                `,
                confirmButtonColor: '#10B981',
                confirmButtonText: 'Lihat Detail Booking',
                timer: 5000,
                timerProgressBar: true,
                width: '500px'
            }).then((result) => {
                // Reset form
                $('#booking-form')[0].reset();

                // Redirect to bookings page to see the new booking
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            });
        }
    }

    /**
     * Handle AJAX error response
     */
    function handleErrorResponse(xhr) {
        hideLoadingState();

        if (xhr.status === 422) {
            // Validation errors
            handleValidationErrors(xhr.responseJSON.errors);

            // Show general validation error notification
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal ❌',
                text: 'Mohon periksa kembali data yang Anda masukkan.',
                confirmButtonColor: '#EF4444',
                confirmButtonText: 'OK, Saya Mengerti'
            });

        } else if (xhr.status === 401) {
            // Unauthorized - redirect to login
            Swal.fire({
                icon: 'warning',
                title: 'Sesi Berakhir ⏰',
                text: 'Silakan login terlebih dahulu untuk melakukan reservasi.',
                confirmButtonColor: '#F59E0B',
                confirmButtonText: 'Login Sekarang'
            }).then(() => {
                window.location.href = '/login';
            });

        } else if (xhr.status === 409) {
            // Conflict - time slot taken
            const errorMessage = xhr.responseJSON?.message || 'Jadwal yang dipilih sudah diambil.';

            Swal.fire({
                icon: 'warning',
                title: 'Jadwal Bentrok ⚠️',
                text: errorMessage,
                confirmButtonColor: '#F59E0B',
                confirmButtonText: 'Pilih Jadwal Lain'
            });

        } else {
            // Other errors
            const errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses reservasi.';

            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan 💥',
                text: errorMessage,
                confirmButtonColor: '#EF4444',
                confirmButtonText: 'Coba Lagi'
            });
        }
    }

    /**
     * Handle validation errors display
     */
    function handleValidationErrors(errors) {
        $.each(errors, function (field, messages) {
            const fieldElement = $(`[name="${field}"]`);

            // Add red border to field
            fieldElement.addClass('border-red-500');

            // Add error message below field
            const errorHtml = `<p class="text-red-600 text-sm mt-1">
                <i class="fas fa-exclamation-circle mr-1"></i>
                ${messages[0]}
            </p>`;

            fieldElement.closest('div').append(errorHtml);
        });

        // Scroll to first error
        const firstErrorField = $('.border-red-500').first();
        if (firstErrorField.length) {
            $('html, body').animate({
                scrollTop: firstErrorField.offset().top - 100
            }, 500);
        }
    }

    /**
     * Real-time validation feedback (optional enhancement)
     */
    $('input[required], select[required], textarea[required]').on('blur', function () {
        const field = $(this);
        const value = field.val().trim();

        // Remove previous error for this field
        field.removeClass('border-red-500');
        field.closest('div').find('.text-red-600').remove();

        // Basic validation
        if (!value) {
            field.addClass('border-red-500');
            const errorHtml = `<p class="text-red-600 text-sm mt-1">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Field ini wajib diisi.
            </p>`;
            field.closest('div').append(errorHtml);
        }
    });

    /**
     * Date time picker validation
     */
    $('#date_time').on('change', function () {
        const selectedDate = new Date($(this).val());
        const now = new Date();

        // Remove previous error
        $(this).removeClass('border-red-500');
        $(this).closest('div').find('.text-red-600').remove();

        // Check if date is in the past
        if (selectedDate < now) {
            $(this).addClass('border-red-500');
            const errorHtml = `<p class="text-red-600 text-sm mt-1">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Tidak bisa memilih waktu yang sudah berlalu.
            </p>`;
            $(this).closest('div').append(errorHtml);
        }
    });

});