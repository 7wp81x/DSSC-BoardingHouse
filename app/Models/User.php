<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;  // ← Add this import for HasOne type hint
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',                    // ← our custom field
        'student_id',
        'phone',
        'emergency_contact',
        'emergency_phone',
        'address',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(\App\Models\MaintenanceRequest::class);
    }

    public function announcements()
    {
        return $this->hasMany(\App\Models\Announcement::class, 'created_by');
    }

    public function isAdmin(): bool
    {
        //return $this->role === 'admin'; // Adjust based on your role system
        // Or if using Laravel Permissions:
        return $this->hasRole('admin');
    }

    // Optional: keep your nice initials helper
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }


    public function getProfilePhotoUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff&bold=true';
    }

    // Accessor for formatted role
    public function getFormattedRoleAttribute()
    {
        return ucfirst($this->role);
    }

    public function studentBoarder(): HasOne
    {
        return $this->hasOne(StudentBoarder::class);
    }
}