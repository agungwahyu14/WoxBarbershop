<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    protected $minLength;

    protected $requireUppercase;

    protected $requireLowercase;

    protected $requireNumbers;

    protected $requireSpecialChars;

    protected $errors = [];

    public function __construct(
        int $minLength = 8,
        bool $requireUppercase = true,
        bool $requireLowercase = true,
        bool $requireNumbers = true,
        bool $requireSpecialChars = true
    ) {
        $this->minLength = $minLength;
        $this->requireUppercase = $requireUppercase;
        $this->requireLowercase = $requireLowercase;
        $this->requireNumbers = $requireNumbers;
        $this->requireSpecialChars = $requireSpecialChars;
    }

    public function passes($attribute, $value): bool
    {
        $this->errors = [];

        // Check minimum length
        if (strlen($value) < $this->minLength) {
            $this->errors[] = "minimal {$this->minLength} karakter";
        }

        // Check uppercase letter
        if ($this->requireUppercase && ! preg_match('/[A-Z]/', $value)) {
            $this->errors[] = 'minimal 1 huruf besar';
        }

        // Check lowercase letter
        if ($this->requireLowercase && ! preg_match('/[a-z]/', $value)) {
            $this->errors[] = 'minimal 1 huruf kecil';
        }

        // Check numbers
        if ($this->requireNumbers && ! preg_match('/[0-9]/', $value)) {
            $this->errors[] = 'minimal 1 angka';
        }

        // Check special characters
        if ($this->requireSpecialChars && ! preg_match('/[^A-Za-z0-9]/', $value)) {
            $this->errors[] = 'minimal 1 karakter khusus (!@#$%^&*)';
        }

        // Check for common weak patterns
        $weakPatterns = [
            '/(.)\1{2,}/', // Three or more repeated characters
            '/123|abc|qwe/i', // Sequential patterns
            '/password|admin|user|login/i', // Common words
        ];

        foreach ($weakPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                $this->errors[] = 'tidak boleh menggunakan pola yang mudah ditebak';
                break;
            }
        }

        return empty($this->errors);
    }

    public function message(): string
    {
        $baseMessage = 'Password harus memenuhi kriteria: ';

        return $baseMessage.implode(', ', $this->errors).'.';
    }
}
