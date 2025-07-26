<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|in:cash,transfer,e_wallet,credit_card',
            'total_amount' => 'required|numeric|min:0|max:10000000',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'booking_id.required' => 'ID booking wajib diisi.',
            'booking_id.exists' => 'Booking tidak ditemukan.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'total_amount.required' => 'Total pembayaran wajib diisi.',
            'total_amount.numeric' => 'Total pembayaran harus berupa angka.',
            'total_amount.min' => 'Total pembayaran tidak boleh negatif.',
            'total_amount.max' => 'Total pembayaran terlalu besar.',
            'payment_proof.image' => 'Bukti pembayaran harus berupa gambar.',
            'payment_proof.mimes' => 'Format bukti pembayaran harus jpeg, png, atau jpg.',
            'payment_proof.max' => 'Ukuran bukti pembayaran maksimal 2MB.',
            'notes.max' => 'Catatan maksimal 500 karakter.',
        ];
    }

    protected function prepareForValidation()
    {
        // Ensure total_amount is properly formatted
        if ($this->total_amount) {
            $this->merge([
                'total_amount' => (float) str_replace(',', '', $this->total_amount),
            ]);
        }
    }
}