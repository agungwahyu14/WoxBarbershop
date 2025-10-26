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
                        <p class="mb-2"><strong>Nomor Pesanan:</strong> <span class="text-2xl font-bold text-green-600">${response.data.queue_number}</span></p>
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
            const alternativeSlots = xhr.responseJSON?.alternative_slots || [];

            let htmlContent = `<div class="text-left">
                <p class="mb-4">${errorMessage}</p>`;

            if (alternativeSlots.length > 0) {
                htmlContent += `<div class="border-t pt-4">
                    <h4 class="font-semibold mb-2">Jadwal Alternatif Tersedia:</h4>
                    <div class="space-y-2 max-h-48 overflow-y-auto">`;

                alternativeSlots.forEach(slot => {
                    htmlContent += `<div class="flex justify-between items-center p-2 border rounded hover:bg-gray-50 cursor-pointer alternative-slot" 
                                        data-datetime="${slot.datetime}">
                        <div>
                            <div class="font-medium">${slot.day_name}, ${slot.formatted_date}</div>
                            <div class="text-sm text-gray-600">${slot.formatted_time}</div>
                        </div>
                        <button type="button" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Pilih Jadwal Ini
                        </button>
                    </div>`;
                });

                htmlContent += `</div></div>`;
            }

            htmlContent += `</div>`;

            Swal.fire({
                icon: 'warning',
                title: 'Jadwal Bentrok ⚠️',
                html: htmlContent,
                confirmButtonColor: '#F59E0B',
                confirmButtonText: 'Tutup',
                width: '500px',
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show',
                    icon: 'swal2-icon-show'
                },
                didOpen: () => {
                    // Add event listeners to alternative slot buttons
                    document.querySelectorAll('.alternative-slot').forEach(slotDiv => {
                        slotDiv.addEventListener('click', function () {
                            const datetime = this.dataset.datetime;
                            $('#date_time').val(datetime);
                            $('#date_time').trigger('change'); // Trigger validation
                            Swal.close();

                            // Show confirmation
                            Swal.fire({
                                icon: 'success',
                                title: 'Jadwal Dipilih!',
                                text: 'Jadwal alternatif telah dipilih. Silakan submit ulang formulir.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        });
                    });
                }
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
            return;
        }

        // Check 24 hours advance requirement
        // const hoursInAdvance = (selectedDate - now) / (1000 * 60 * 60);
        // if (hoursInAdvance < 24) {
        //     $(this).addClass('border-red-500');
        //     const errorHtml = `<p class="text-red-600 text-sm mt-1">
        //         <i class="fas fa-exclamation-circle mr-1"></i>
        //         Pemesanan harus dilakukan minimal 24 jam sebelumnya.
        //     </p>`;
        //     $(this).closest('div').append(errorHtml);
        //     return;
        // }

        // Check if time is between 11:00 - 22:00
        const selectedHour = selectedDate.getHours();
        if (selectedHour < 11 || selectedHour >= 22) {
            $(this).addClass('border-red-500');
            const errorHtml = `<p class="text-red-600 text-sm mt-1">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Booking hanya dapat dilakukan antara jam 11:00 - 22:00.
            </p>`;
            $(this).closest('div').append(errorHtml);
        }
    });

});
