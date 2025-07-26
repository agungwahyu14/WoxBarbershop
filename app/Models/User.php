<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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

    /**
     * Boot method to set default role for new users
     */
    protected static function booted()
    {
        static::created(function ($user) {
            // Only assign default role if no roles are assigned
            if ($user->roles()->count() === 0) {
                $user->assignRole('pelanggan');
            }
        });

        // Update last login when user is authenticated
        static::updating(function ($user) {
            if ($user->isDirty('password')) {
                // Password was updated, could trigger additional actions
            }
        });
    }

    /**
     * Relationships
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function loyalties()
    {
        return $this->hasMany(Loyalty::class);
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for verified users
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope for users with specific role
     */
    public function scopeWithRole($query, $roleName)
    {
        return $query->whereHas('roles', function($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    /**
     * Check if user account is active
     */
    public function isActive()
    {
        return $this->is_active ?? true;
    }

    /**
     * Get user's avatar initials
     */
    public function getAvatarInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Get user's full display name with title if available
     */
    public function getDisplayNameAttribute()
    {
        return $this->name;
    }

    /**
     * Get user's formatted phone number
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->no_telepon) {
            return null;
        }

        // Format Indonesian phone numbers
        $phone = preg_replace('/[^0-9]/', '', $this->no_telepon);
        
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return '+' . $phone;
    }

    /**
     * Get user statistics
     */
    public function getStatsAttribute()
    {
        return [
            'total_bookings' => $this->bookings()->count(),
            'confirmed_bookings' => $this->bookings()->where('status', 'confirmed')->count(),
            'pending_bookings' => $this->bookings()->where('status', 'pending')->count(),
            'cancelled_bookings' => $this->bookings()->where('status', 'cancelled')->count(),
            'total_spent' => $this->transactions()->where('status', 'success')->sum('amount'),
            'member_since' => $this->created_at->diffForHumans(),
            'last_booking' => $this->bookings()->latest()->first()?->created_at?->diffForHumans(),
            'loyalty_points' => $this->loyalties()->sum('points'),
        ];
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Check if user has verified email and is active
     */
    public function isCompletelyVerified()
    {
        return $this->hasVerifiedEmail() && $this->isActive();
    }

    /**
     * Get user's primary role name
     */
    public function getPrimaryRoleAttribute()
    {
        return $this->roles->first()?->name ?? 'pelanggan';
    }

    /**
     * Check if user can perform action based on role hierarchy
     */
    public function canManageUser(User $targetUser)
    {
        // Admin can manage everyone except other admins
        if ($this->hasRole('admin')) {
            return !$targetUser->hasRole('admin') || $this->id === $targetUser->id;
        }

        // Pegawai can manage pelanggan only
        if ($this->hasRole('pegawai')) {
            return $targetUser->hasRole('pelanggan');
        }

        // Regular users can only manage themselves
        return $this->id === $targetUser->id;
    }

    /**
     * Send password reset notification with custom template
     */
    public function sendPasswordResetNotification($token)
    {
        // You can customize this to use your own notification class
        $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($token));
    }

    /**
     * Get all permissions including those from roles
     */
    public function getAllPermissionsAttribute()
    {
        return $this->getAllPermissions();
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            if ($this->can($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Deactivate user account
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);
        
        // You could add additional logic here like:
        // - Cancel active bookings
        // - Send notification email
        // - Log the action
    }

    /**
     * Activate user account
     */
    public function activate()
    {
        $this->update(['is_active' => true]);
        
        // You could add additional logic here like:
        // - Send welcome back email
        // - Log the action
    }

    /**
     * Get user's booking statistics for admin dashboard
     */
    public function getBookingStats($period = 'all')
    {
        $query = $this->bookings();

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }

        return [
            'total' => $query->count(),
            'confirmed' => $query->where('status', 'confirmed')->count(),
            'pending' => $query->where('status', 'pending')->count(),
            'cancelled' => $query->where('status', 'cancelled')->count(),
        ];
    }
}