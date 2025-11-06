$(document).ready(function() {
    // Profile Update AJAX Handler
    $('#profile-update-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        // Show loading state
        submitBtn.prop('disabled', true)
               .html('<i class="fas fa-spinner fa-spin mr-2"></i>' + 
                     (window.profileTranslations ? window.profileTranslations.saving : 'Saving...'));
        
        // Create FormData for file upload
        const formData = new FormData(form[0]);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Handle success response
                if (response.success) {
                    // Show success notification
                    Swal.fire({
                        icon: 'success',
                        title: window.profileTranslations ? window.profileTranslations.success : 'Success!',
                        text: response.message || (window.profileTranslations ? window.profileTranslations.profile_updated : 'Profile updated successfully!'),
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    
                    // Update session flash message for next page load
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                } else if (response.errors) {
                    // Handle validation errors
                    let errorMessage = '';
                    $.each(response.errors, function(key, value) {
                        errorMessage += value + '\n';
                    });
                    
                    Swal.fire({
                        icon: 'error',
                        title: window.profileTranslations ? window.profileTranslations.error : 'Error!',
                        text: errorMessage,
                        confirmButtonColor: '#d33',
                        confirmButtonText: window.profileTranslations ? window.profileTranslations.ok : 'OK'
                    });
                }
            },
            error: function(xhr) {
                // Handle server errors
                let errorMessage = window.profileTranslations ? 
                    window.profileTranslations.update_failed : 'Profile update failed. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: window.profileTranslations ? window.profileTranslations.error : 'Error!',
                    text: errorMessage,
                    confirmButtonColor: '#d33',
                    confirmButtonText: window.profileTranslations ? window.profileTranslations.ok : 'OK'
                });
            },
            complete: function() {
                // Restore button state
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Password Update AJAX Handler
    $('#password-update-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        // Validate passwords match
        const newPassword = form.find('input[name="password"]').val();
        const confirmPassword = form.find('input[name="password_confirmation"]').val();
        
        if (newPassword !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: window.profileTranslations ? window.profileTranslations.error : 'Error!',
                text: window.profileTranslations ? window.profileTranslations.password_mismatch : 'Passwords do not match.',
                confirmButtonColor: '#d33',
                confirmButtonText: window.profileTranslations ? window.profileTranslations.ok : 'OK'
            });
            return;
        }
        
        // Show loading state
        submitBtn.prop('disabled', true)
               .html('<i class="fas fa-spinner fa-spin mr-2"></i>' + 
                     (window.profileTranslations ? window.profileTranslations.updating : 'Updating...'));
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Clear password fields
                    form.find('input[name="password"]').val('');
                    form.find('input[name="password_confirmation"]').val('');
                    form.find('input[name="current_password"]').val('');
                    
                    // Show success notification
                    Swal.fire({
                        icon: 'success',
                        title: window.profileTranslations ? window.profileTranslations.success : 'Success!',
                        text: response.message || (window.profileTranslations ? window.profileTranslations.password_updated : 'Password updated successfully!'),
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else if (response.errors) {
                    // Handle validation errors
                    let errorMessage = '';
                    $.each(response.errors, function(key, value) {
                        errorMessage += value + '\n';
                    });
                    
                    Swal.fire({
                        icon: 'error',
                        title: window.profileTranslations ? window.profileTranslations.error : 'Error!',
                        text: errorMessage,
                        confirmButtonColor: '#d33',
                        confirmButtonText: window.profileTranslations ? window.profileTranslations.ok : 'OK'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = window.profileTranslations ? 
                    window.profileTranslations.password_update_failed : 'Password update failed. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: window.profileTranslations ? window.profileTranslations.error : 'Error!',
                    text: errorMessage,
                    confirmButtonColor: '#d33',
                    confirmButtonText: window.profileTranslations ? window.profileTranslations.ok : 'OK'
                });
            },
            complete: function() {
                // Restore button state
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Real-time validation feedback
    $('input[name="name"]').on('input', function() {
        const value = $(this).val().trim();
        if (value.length < 3) {
            $(this).addClass('border-red-500').removeClass('border-gray-300');
        } else {
            $(this).removeClass('border-red-500').addClass('border-gray-300');
        }
    });
    
    $('input[name="email"]').on('input', function() {
        const value = $(this).val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            $(this).addClass('border-red-500').removeClass('border-gray-300');
        } else {
            $(this).removeClass('border-red-500').addClass('border-gray-300');
        }
    });
    
    $('input[name="no_telepon"]').on('input', function() {
        const value = $(this).val().trim();
        const phoneRegex = /^[0-9]+$/;
        if (!phoneRegex.test(value) || value.length < 10) {
            $(this).addClass('border-red-500').removeClass('border-gray-300');
        } else {
            $(this).removeClass('border-red-500').addClass('border-gray-300');
        }
    });
});
