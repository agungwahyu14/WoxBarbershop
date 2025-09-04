<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|min:2|max:100',
            'service_id' => 'required|exists:services,id',
            'hairstyle_id' => 'required|exists:hairstyles,id',
            'description' => 'nullable|string|max:500',
        ];

        // Different validation for create vs update
        if ($this->isMethod('post')) {
            // For creating new booking
            $rules['date_time'] = [
                'required',
                'date',
                'after:now',
                'before:'.Carbon::now()->addMonths(3)->format('Y-m-d H:i:s'),
            ];
        } else {
            // For updating existing booking - less restrictive
            $rules['date_time'] = [
                'required',
                'date',
                'before:'.Carbon::now()->addMonths(3)->format('Y-m-d H:i:s'),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal 2 karakter.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'service_id.required' => 'Layanan wajib dipilih.',
            'service_id.exists' => 'Layanan yang dipilih tidak valid.',
            'hairstyle_id.required' => 'Gaya rambut wajib dipilih.',
            'hairstyle_id.exists' => 'Gaya rambut yang dipilih tidak valid.',
            'date_time.required' => 'Tanggal dan waktu wajib diisi.',
            'date_time.after' => 'Tanggal dan waktu harus di masa depan.',
            'date_time.before' => 'Booking hanya dapat dilakukan maksimal 3 bulan ke depan.',
            'description.max' => 'Deskripsi maksimal 500 karakter.',
        ];
    }

    /**
     * Handle a failed validation attempt with enhanced logging and error categorization
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        
        // Log validation failure with details
        Log::warning('BookingRequest validation failed', [
            'user_id' => auth()->id() ?? 'guest',
            'errors' => $errors,
            'input_data' => $this->except(['password', '_token']),
            'ip' => $this->ip(),
            'user_agent' => $this->userAgent()
        ]);

        // Categorize errors for different SweetAlert handling
        $errorCategory = $this->categorizeValidationErrors($errors);
        
        // Add error category to session for SweetAlert handling
        session()->flash('validation_error_category', $errorCategory);
        session()->flash('validation_errors_detail', $errors);

        parent::failedValidation($validator);
    }

    /**
     * Categorize validation errors for appropriate SweetAlert display
     */
    private function categorizeValidationErrors(array $errors): string
    {
        if (isset($errors['date_time'])) {
            foreach ($errors['date_time'] as $error) {
                if (strpos($error, 'jam 09:00 - 21:00') !== false) {
                    return 'business_hours';
                }
                if (strpos($error, 'hari Minggu') !== false) {
                    return 'closed_day';
                }
                if (strpos($error, 'masa depan') !== false) {
                    return 'past_date';
                }
                if (strpos($error, '3 bulan') !== false) {
                    return 'too_far_future';
                }
            }
            return 'datetime_error';
        }

        if (isset($errors['service_id']) || isset($errors['hairstyle_id'])) {
            return 'selection_error';
        }

        if (isset($errors['name'])) {
            return 'name_error';
        }

        return 'general_error';
    }

    public function prepareForValidation()
    {
        $this->merge([
            'date_time' => Carbon::parse($this->date_time)->format('Y-m-d H:i:s'),
        ]);
    }

    protected function passedValidation()
    {
        // Additional business logic validation with enhanced logging
        $dateTime = Carbon::parse($this->date_time);

        Log::info('BookingRequest business logic validation', [
            'user_id' => auth()->id(),
            'requested_datetime' => $dateTime->format('Y-m-d H:i:s'),
            'day_of_week' => $dateTime->dayOfWeek,
            'hour' => $dateTime->hour,
            'service_id' => $this->service_id
        ]);

        $errors = [];
        $errorCategory = '';

        // Check if booking time is within business hours (9 AM - 9 PM)
        if ($dateTime->hour < 9 || $dateTime->hour >= 21) {
            $errors['date_time'] = ['Booking hanya dapat dilakukan antara jam 09:00 - 21:00.'];
            $errorCategory = 'business_hours';
            
            Log::warning('Booking attempt outside business hours', [
                'user_id' => auth()->id(),
                'requested_hour' => $dateTime->hour,
                'business_hours' => '09:00 - 21:00'
            ]);
        }

        // Check if it's not Sunday (assuming barbershop closed on Sunday)
        if ($dateTime->dayOfWeek === Carbon::SUNDAY) {
            $errors['date_time'] = ['Maaf, kami tutup pada hari Minggu.'];
            $errorCategory = 'closed_day';
            
            Log::warning('Booking attempt on closed day', [
                'user_id' => auth()->id(),
                'requested_date' => $dateTime->format('Y-m-d'),
                'day_name' => 'Sunday'
            ]);
        }

        // Check if service might extend beyond business hours
        if ($this->service_id && $dateTime->hour >= 20) {
            // This is a soft warning - we'll let BookingService handle the detailed check
            Log::info('Late booking attempt - service might extend beyond hours', [
                'user_id' => auth()->id(),
                'requested_hour' => $dateTime->hour,
                'service_id' => $this->service_id
            ]);
        }

        if (!empty($errors)) {
            // Add error category to session for SweetAlert handling
            session()->flash('validation_error_category', $errorCategory);
            session()->flash('business_logic_errors', $errors);

            Log::error('Business logic validation failed', [
                'user_id' => auth()->id(),
                'errors' => $errors,
                'category' => $errorCategory,
                'datetime' => $dateTime->format('Y-m-d H:i:s')
            ]);

            // Flash errors and error type to session for SweetAlert
            session()->flash('validation_errors', $errors);
            session()->flash('error_type', $errorCategory);
            session()->flash('error', 'Booking tidak dapat dilakukan pada jam tersebut.');
            
            // Use redirect to trigger SweetAlert instead of throwing exception
            $response = redirect()->back()
                ->withErrors($errors)
                ->withInput();
                
            // Send the response immediately to avoid processing
            $response->send();
            exit();
        }

        Log::info('BookingRequest validation passed', [
            'user_id' => auth()->id(),
            'datetime' => $dateTime->format('Y-m-d H:i:s')
        ]);
    }
}
