/**
 * Booking Business Hours Validation
 * Real-time validation untuk jam operasional barbershop
 */

class BookingValidator {
    constructor() {
        this.businessHours = {
            open: 11,  // 11:00
            close: 22, // 22:00
            closedDays: [] // Open every day, no holidays
        };

        this.init();
    }

    init() {
        // Bind validation to datetime input fields
        document.addEventListener('DOMContentLoaded', () => {
            const dateTimeInputs = document.querySelectorAll('input[type="datetime-local"], input[name*="date_time"]');
            dateTimeInputs.forEach(input => {
                input.addEventListener('change', (e) => this.validateBookingTime(e.target));
                input.addEventListener('blur', (e) => this.validateBookingTime(e.target));
            });
        });
    }

    validateBookingTime(input) {
        const selectedDateTime = new Date(input.value);

        if (!selectedDateTime || isNaN(selectedDateTime.getTime())) {
            return;
        }

        // Client-side validation first
        const validation = this.checkBusinessHours(selectedDateTime);

        if (!validation.isValid) {
            this.showValidationAlert(validation);
            return;
        }

        // Server-side validation for slot availability
        this.validateWithServer(input.value, input);
    }

    async validateWithServer(dateTimeString, inputElement) {
        try {
            // Get service_id if available
            const form = inputElement.closest('form');
            const serviceIdInput = form ? form.querySelector('[name="service_id"]') : null;
            const serviceId = serviceIdInput ? serviceIdInput.value : null;

            if (!serviceId) {
                return; // Skip server validation if no service selected
            }

            const response = await fetch('/api/validate-booking-time', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    date_time: dateTimeString,
                    service_id: serviceId
                })
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Validation failed');
            }

            const validation = data.validation;

            if (!validation.is_valid) {
                this.showValidationAlert(validation);
            } else if (validation.warnings && validation.warnings.length > 0) {
                this.showWarningAlert(validation);
            }

        } catch (error) {
            console.error('Server validation error:', error);
            // Fallback to client-side validation only
        }
    }

    async loadAvailableSlots(date, serviceId) {
        try {
            const response = await fetch(`/api/available-slots?date=${date}&service_id=${serviceId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                return data.slots;
            } else {
                throw new Error(data.error || 'Failed to load available slots');
            }

        } catch (error) {
            console.error('Failed to load available slots:', error);
            return [];
        }
    }

    checkBusinessHours(dateTime) {
        const result = {
            isValid: true,
            errors: [],
            warnings: [],
            suggestions: []
        };

        const now = new Date();
        const hour = dateTime.getHours();
        const dayOfWeek = dateTime.getDay();

        // Check if booking time is in the past
        if (dateTime < now) {
            result.isValid = false;
            result.errors.push('Tidak dapat membuat booking di waktu yang sudah berlalu');
            return result;
        }

        // Check if it's a business day (not Sunday)
        if (this.businessHours.closedDays.includes(dayOfWeek)) {
            result.isValid = false;
            result.errors.push('Maaf, barbershop tutup pada hari Minggu');
            result.suggestions.push('Silakan pilih hari Senin - Sabtu');

            // Suggest next business day
            const nextBusinessDay = new Date(dateTime);
            while (this.businessHours.closedDays.includes(nextBusinessDay.getDay())) {
                nextBusinessDay.setDate(nextBusinessDay.getDate() + 1);
            }
            result.suggestions.push(`Hari kerja berikutnya: ${this.formatDate(nextBusinessDay)}`);
        }

        // Check business hours
        if (hour < this.businessHours.open || hour >= this.businessHours.close) {
            result.isValid = false;

            if (hour < this.businessHours.open) {
                result.errors.push(`Barbershop belum buka. Jam operasional: ${this.businessHours.open.toString().padStart(2, '0')}:00 - ${this.businessHours.close.toString().padStart(2, '0')}:00`);
                result.suggestions.push(`Silakan pilih waktu mulai jam ${this.businessHours.open.toString().padStart(2, '0')}:00`);
            } else {
                result.errors.push(`Barbershop sudah tutup. Jam operasional: ${this.businessHours.open.toString().padStart(2, '0')}:00 - ${this.businessHours.close.toString().padStart(2, '0')}:00`);
                result.suggestions.push(`Silakan pilih waktu sebelum jam ${this.businessHours.close.toString().padStart(2, '0')}:00`);
            }
        }

        // Check if booking is too far in advance (30 days)
        const daysInAdvance = Math.ceil((dateTime - now) / (1000 * 60 * 60 * 24));
        if (daysInAdvance > 30) {
            result.warnings.push('Booking terlalu jauh di masa depan (maksimal 30 hari ke depan)');
        }

        // Check if booking is very soon (less than 2 hours)
        const hoursFromNow = Math.ceil((dateTime - now) / (1000 * 60 * 60));
        if (hoursFromNow < 2 && hoursFromNow > 0) {
            result.warnings.push('Booking dalam waktu dekat. Pastikan Anda bisa hadir tepat waktu');
        }

        return result;
    }

    showValidationAlert(validation) {
        const errorMessage = validation.errors.join('. ');
        const suggestions = validation.suggestions.length > 0 ?
            '<div class="mt-3 p-3 bg-red-50 rounded-lg"><h4 class="font-semibold text-red-800 mb-2"><i class="fas fa-lightbulb mr-1"></i> Saran:</h4>' +
            validation.suggestions.map(s => `<p class="text-red-700 text-sm">• ${s}</p>`).join('') +
            '</div>' : '';

        Swal.fire({
            icon: 'error',
            title: 'Waktu Tidak Valid',
            html: `
                <div class="text-left">
                    <p class="mb-3">${errorMessage}</p>
                    <div class="bg-red-50 p-3 rounded-lg">
                        <h4 class="font-semibold text-red-800 mb-2">
                            <i class="fas fa-clock mr-1"></i> Jam Operasional:
                        </h4>
                        <p class="text-red-700">
                            <strong>Senin - Sabtu:</strong> ${this.businessHours.open.toString().padStart(2, '0')}:00 - ${this.businessHours.close.toString().padStart(2, '0')}:00<br>
                            <strong>Minggu:</strong> Tutup
                        </p>
                    </div>
                    ${suggestions}
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'Pilih Waktu Lain',
            confirmButtonColor: '#DC2626'
        });
    }

    showWarningAlert(validation) {
        const warningMessage = validation.warnings.join('. ');

        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            html: `
                <div class="text-left">
                    <p class="mb-3">${warningMessage}</p>
                    <div class="bg-yellow-50 p-3 rounded-lg">
                        <p class="text-yellow-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Booking Anda masih valid, tetapi mohon perhatikan informasi di atas.
                        </p>
                    </div>
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#F59E0B',
            toast: true,
            position: 'top-end',
            timer: 5000
        });
    }

    formatDate(date) {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        const dayName = days[date.getDay()];
        const day = date.getDate();
        const month = months[date.getMonth()];
        const year = date.getFullYear();

        return `${dayName}, ${day} ${month} ${year}`;
    }

    // Static method to validate specific datetime
    static validateDateTime(dateTimeString) {
        const validator = new BookingValidator();
        const dateTime = new Date(dateTimeString);
        return validator.checkBusinessHours(dateTime);
    }

    // Static method to get next available slot
    static getNextAvailableSlot(requestedDateTime, durationMinutes = 30) {
        const validator = new BookingValidator();
        let current = new Date(requestedDateTime);

        // Max attempts to prevent infinite loop
        let attempts = 0;
        const maxAttempts = 48 * 7; // Check up to 1 week

        while (attempts < maxAttempts) {
            const validation = validator.checkBusinessHours(current);

            if (validation.isValid) {
                return current;
            }

            // Move to next 30-minute slot
            current.setMinutes(current.getMinutes() + 30);
            attempts++;
        }

        throw new Error('No available slots found in the next week');
    }
}

// Initialize booking validator
const bookingValidator = new BookingValidator();

// Make it globally available
window.BookingValidator = BookingValidator;
window.bookingValidator = bookingValidator;
