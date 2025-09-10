<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'no_telepon',
        'password',
        'email_verified_at',
        'is_active',
        'last_login_at',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function loyalty()
    {
        return $this->hasOne(Loyalty::class);
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if user has verified email
     */
    public function isEmailVerified(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        try {
            $previousLogin = $this->last_login_at;
            $this->update(['last_login_at' => now()]);
            
            Log::info('User last login updated', [
                'user_id' => $this->id,
                'email' => $this->email,
                'previous_login' => $previousLogin,
                'current_login' => $this->fresh()->last_login_at
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update last login', [
                'user_id' => $this->id,
                'email' => $this->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Get user's full profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo && Storage::disk('public')->exists($this->profile_photo)) {
            return asset('storage/'.$this->profile_photo);
        }

        // Use a more reliable placeholder service or create initials-based avatar
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF&size=200';
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    // Forgot Password feature disabled - may be used in the future
    /*
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
    */
}
