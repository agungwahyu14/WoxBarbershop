<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:100',
            'service_id' => 'required|exists:services,id',
            'hairstyle_id' => 'required|exists:hairstyles,id',
            'date_time' => [
                'required',
                'date',
                'after:now',
                'before:' . Carbon::now()->addMonths(3)->format('Y-m-d H:i:s')
            ],
            'description' => 'nullable|string|max:500',
        ];
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

    public function prepareForValidation()
    {
        $this->merge([
            'date_time' => Carbon::parse($this->date_time)->format('Y-m-d H:i:s'),
        ]);
    }

    protected function passedValidation()
    {
        // Additional business logic validation
        $dateTime = Carbon::parse($this->date_time);
        
        // Check if booking time is within business hours (9 AM - 9 PM)
        if ($dateTime->hour < 9 || $dateTime->hour >= 21) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['date_time' => ['Booking hanya dapat dilakukan antara jam 09:00 - 21:00.']]
            );
        }

        // Check if it's not Sunday (assuming barbershop closed on Sunday)
        if ($dateTime->dayOfWeek === Carbon::SUNDAY) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['date_time' => ['Maaf, kami tutup pada hari Minggu.']]
            );
        }
    }
}