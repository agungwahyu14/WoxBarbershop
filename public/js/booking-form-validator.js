/**
 * Enhanced Form Validator with SweetAlert Integration
 * Real-time validation untuk booking form dengan business rules
 */

class BookingFormValidator {
    constructor() {
        this.businessHours = {
            open: 11,
            close: 22,
            closedDays: [] // Open every day, no holidays
        };

        this.validationRules = {
            name: {
                required: true,
                minLength: 2,
                maxLength: 100
            },
            service_id: {
                required: true
            },
            hairstyle_id: {
                required: true
            },
            date_time: {
                required: true,
                futureDate: true,
                businessHours: true,
                maxMonthsAhead: 3
            },
            description: {
                maxLength: 500
            }
        };

        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.bindFormEvents();
            this.enhanceFormSubmission();
        });
    }

    bindFormEvents() {
        // Find booking forms
        const bookingForms = document.querySelectorAll('form[action*="bookings"]');

        bookingForms.forEach(form => {
            // Real-time validation on input change
            form.addEventListener('input', (e) => this.validateField(e.target));
            form.addEventListener('change', (e) => this.validateField(e.target));

            // Enhanced form submission
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        });
    }

    validateField(field) {
        const fieldName = field.name;
        const value = field.value;

        if (!this.validationRules[fieldName]) return;

        const validation = this.validateSingleField(fieldName, value);
        this.displayFieldValidation(field, validation);
    }

    validateSingleField(fieldName, value) {
        const rules = this.validationRules[fieldName];
        const result = {
            isValid: true,
            errors: [],
            warnings: []
        };

        // Required field validation
        if (rules.required && (!value || value.trim() === '')) {
            result.isValid = false;
            result.errors.push(this.getFieldLabel(fieldName) + ' wajib diisi');
            return result;
        }

        // Skip other validations if field is empty and not required
        if (!value || value.trim() === '') return result;

        // Field-specific validations
        switch (fieldName) {
            case 'name':
                if (value.length < rules.minLength) {
                    result.isValid = false;
                    result.errors.push(`Nama minimal ${rules.minLength} karakter`);
                }
                if (value.length > rules.maxLength) {
                    result.isValid = false;
                    result.errors.push(`Nama maksimal ${rules.maxLength} karakter`);
                }
                break;

            case 'date_time':
                const dateValidation = this.validateDateTime(value);
                result.isValid = dateValidation.isValid;
                result.errors = dateValidation.errors;
                result.warnings = dateValidation.warnings;
                break;

            case 'description':
                if (value.length > rules.maxLength) {
                    result.isValid = false;
                    result.errors.push(`Deskripsi maksimal ${rules.maxLength} karakter`);
                }
                break;
        }

        return result;
    }

    validateDateTime(dateTimeString) {
        const result = {
            isValid: true,
            errors: [],
            warnings: []
        };

        const dateTime = new Date(dateTimeString);
        const now = new Date();

        // Check if valid date
        if (isNaN(dateTime.getTime())) {
            result.isValid = false;
            result.errors.push('Format tanggal dan waktu tidak valid');
            return result;
        }

        // Check if in the past
        if (dateTime <= now) {
            result.isValid = false;
            result.errors.push('Tanggal dan waktu harus di masa depan');
            return result;
        }

        // Check if too far in the future (3 months)
        const maxDate = new Date();
        maxDate.setMonth(maxDate.getMonth() + 3);
        if (dateTime > maxDate) {
            result.isValid = false;
            result.errors.push('Booking maksimal 3 bulan ke depan');
            return result;
        }

        // Check business day
        if (this.businessHours.closedDays.includes(dateTime.getDay())) {
            result.isValid = false;
            result.errors.push('Maaf, kami tutup pada hari Minggu');
            return result;
        }

        // Check business hours
        const hour = dateTime.getHours();
        if (hour < this.businessHours.open || hour >= this.businessHours.close) {
            result.isValid = false;
            result.errors.push(`Booking hanya dapat dilakukan antara jam ${this.businessHours.open.toString().padStart(2, '0')}:00 - ${this.businessHours.close.toString().padStart(2, '0')}:00`);
            return result;
        }

        // Warnings
        const hoursFromNow = (dateTime - now) / (1000 * 60 * 60);
        if (hoursFromNow < 2) {
            result.warnings.push('Booking dalam waktu dekat. Pastikan Anda bisa hadir tepat waktu');
        }

        const daysFromNow = (dateTime - now) / (1000 * 60 * 60 * 24);
        if (daysFromNow > 30) {
            result.warnings.push('Booking lebih dari 30 hari ke depan');
        }

        return result;
    }

    displayFieldValidation(field, validation) {
        // Remove existing validation displays
        this.clearFieldValidation(field);

        if (!validation.isValid) {
            this.showFieldErrors(field, validation.errors);
        } else if (validation.warnings.length > 0) {
            this.showFieldWarnings(field, validation.warnings);
        } else {
            this.showFieldSuccess(field);
        }
    }

    showFieldErrors(field, errors) {
        field.classList.add('border-red-500', 'bg-red-50');
        field.classList.remove('border-green-500', 'bg-green-50', 'border-yellow-500', 'bg-yellow-50');

        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-validation text-red-600 text-sm mt-1';
        errorDiv.innerHTML = errors.map(error => `<p><i class="fas fa-exclamation-circle mr-1"></i>${error}</p>`).join('');

        field.parentNode.appendChild(errorDiv);
    }

    showFieldWarnings(field, warnings) {
        field.classList.add('border-yellow-500', 'bg-yellow-50');
        field.classList.remove('border-red-500', 'bg-red-50', 'border-green-500', 'bg-green-50');

        const warningDiv = document.createElement('div');
        warningDiv.className = 'field-validation text-yellow-600 text-sm mt-1';
        warningDiv.innerHTML = warnings.map(warning => `<p><i class="fas fa-exclamation-triangle mr-1"></i>${warning}</p>`).join('');

        field.parentNode.appendChild(warningDiv);
    }

    showFieldSuccess(field) {
        field.classList.add('border-green-500', 'bg-green-50');
        field.classList.remove('border-red-500', 'bg-red-50', 'border-yellow-500', 'bg-yellow-50');
    }

    clearFieldValidation(field) {
        field.classList.remove('border-red-500', 'bg-red-50', 'border-green-500', 'bg-green-50', 'border-yellow-500', 'bg-yellow-50');

        const existingValidation = field.parentNode.querySelector('.field-validation');
        if (existingValidation) {
            existingValidation.remove();
        }
    }

    handleFormSubmit(e) {
        const form = e.target;
        const formData = new FormData(form);
        const validationResult = this.validateEntireForm(formData);

        if (!validationResult.isValid) {
            e.preventDefault();
            this.showFormValidationAlert(validationResult);
            return false;
        }

        if (validationResult.warnings.length > 0) {
            e.preventDefault();
            this.showFormWarningAlert(validationResult, form);
            return false;
        }

        // Show loading indicator
        this.showLoadingIndicator(form);
        return true;
    }

    validateEntireForm(formData) {
        const result = {
            isValid: true,
            errors: [],
            warnings: [],
            fieldErrors: {}
        };

        for (const [fieldName, value] of formData.entries()) {
            if (this.validationRules[fieldName]) {
                const fieldValidation = this.validateSingleField(fieldName, value);

                if (!fieldValidation.isValid) {
                    result.isValid = false;
                    result.errors = result.errors.concat(fieldValidation.errors);
                    result.fieldErrors[fieldName] = fieldValidation.errors;
                }

                result.warnings = result.warnings.concat(fieldValidation.warnings);
            }
        }

        return result;
    }

    showFormValidationAlert(validationResult) {
        const errorList = validationResult.errors.map(error => `<li class="text-left">${error}</li>`).join('');

        Swal.fire({
            icon: 'error',
            title: 'Form Validation Error',
            html: `
                <div class="text-left">
                    <p class="mb-3">Mohon perbaiki kesalahan berikut:</p>
                    <ul class="list-disc list-inside text-red-600">
                        ${errorList}
                    </ul>
                </div>
            `,
            confirmButtonText: 'Perbaiki Form',
            confirmButtonColor: '#DC2626',
            allowOutsideClick: false
        });
    }

    showFormWarningAlert(validationResult, form) {
        const warningList = validationResult.warnings.map(warning => `<li class="text-left">${warning}</li>`).join('');

        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            html: `
                <div class="text-left">
                    <p class="mb-3">Mohon perhatikan hal berikut:</p>
                    <ul class="list-disc list-inside text-yellow-600">
                        ${warningList}
                    </ul>
                    <p class="mt-3 text-sm text-gray-600">Apakah Anda ingin melanjutkan booking?</p>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#F59E0B',
            cancelButtonColor: '#6B7280'
        }).then((result) => {
            if (result.isConfirmed) {
                this.showLoadingIndicator(form);
                form.submit();
            }
        });
    }

    showLoadingIndicator(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        }

        Swal.fire({
            title: 'Memproses Booking',
            text: 'Mohon tunggu sebentar...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
    }

    getFieldLabel(fieldName) {
        const labels = {
            name: 'Nama',
            service_id: 'Layanan',
            hairstyle_id: 'Gaya Rambut',
            date_time: 'Tanggal & Waktu',
            description: 'Deskripsi'
        };

        return labels[fieldName] || fieldName;
    }
}

// Initialize the form validator
const bookingFormValidator = new BookingFormValidator();

// Make it globally available
window.BookingFormValidator = BookingFormValidator;
window.bookingFormValidator = bookingFormValidator;
