<?php

namespace App\Http\Requests\Auth;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/' // Only letters and spaces
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users,email',
                'not_regex:/^.+@(tempmail|10minutemail|guerrillamail)\./'
            ],
            'no_telepon' => [
                'required',
                'string',
                'regex:/^(\+62|62|0)[0-9]{9,13}$/',
                'unique:users,no_telepon'
            ],
            'password' => [
                'required',
                'confirmed',
                new StrongPassword(8, true, true, true, true)
            ],
            'terms' => 'required|accepted',
            'captcha' => 'sometimes|required' // For production with CAPTCHA
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama minimal 2 karakter.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.not_regex' => 'Email temporary tidak diperbolehkan.',
            
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.regex' => 'Format nomor telepon tidak valid. Gunakan format Indonesia (08xx atau +62).',
            'no_telepon.unique' => 'Nomor telepon sudah terdaftar.',
            
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
            
            'captcha.required' => 'Mohon verifikasi CAPTCHA.'
        ];
    }

    protected function prepareForValidation(): void
    {
        // Normalize phone number
        if ($this->no_telepon) {
            $phone = preg_replace('/[^0-9+]/', '', $this->no_telepon);
            
            // Convert to standard format
            if (str_starts_with($phone, '0')) {
                $phone = '+62' . substr($phone, 1);
            } elseif (str_starts_with($phone, '62')) {
                $phone = '+' . $phone;
            }
            
            $this->merge(['no_telepon' => $phone]);
        }

        // Clean name
        if ($this->name) {
            $this->merge([
                'name' => trim(preg_replace('/\s+/', ' ', $this->name))
            ]);
        }
    }
}