<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasRole(['admin', 'pegawai']);
    }

    public function rules(): array
    {
        $serviceId = $this->route('service');
        
        return [
            'name' => 'required|string|min:3|max:100|unique:services,name,' . $serviceId,
            'description' => 'required|string|min:10|max:1000',
            'price' => 'required|numeric|min:0|max:1000000',
            'duration' => 'required|integer|min:15|max:300', // 15 minutes to 5 hours
            'is_active' => 'required|boolean',
            'image' => $this->isMethod('POST') ? 'required|image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama layanan wajib diisi.',
            'name.min' => 'Nama layanan minimal 3 karakter.',
            'name.max' => 'Nama layanan maksimal 100 karakter.',
            'name.unique' => 'Nama layanan sudah digunakan.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'price.max' => 'Harga maksimal Rp 1.000.000.',
            'duration.required' => 'Durasi wajib diisi.',
            'duration.integer' => 'Durasi harus berupa angka bulat.',
            'duration.min' => 'Durasi minimal 15 menit.',
            'duration.max' => 'Durasi maksimal 300 menit (5 jam).',
            'is_active.required' => 'Status aktif wajib dipilih.',
            'is_active.boolean' => 'Status aktif harus true atau false.',
            'image.required' => 'Gambar layanan wajib diupload.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}