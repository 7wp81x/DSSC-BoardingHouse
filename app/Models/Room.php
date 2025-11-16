<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'room_code',
        'type',        // single, twin, quad, premium
        'price',
        'status',      // available, occupied, full
        'amenities',   // JSON array
        'description'
    ];

    protected $casts = [
        'amenities' => 'array',
        'price'     => 'decimal:2',
    ];

    // Add this relationship for multiple images
    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class)->orderBy('is_primary', 'desc')->orderBy('order');
    }

    // Current students (active boarders)
    public function currentStudents()
    {
        return $this->hasManyThrough(
            StudentBoarder::class,
            Booking::class,
            'room_id', // Foreign key on bookings table
            'booking_id', // Foreign key on student_boarders table
            'id', // Local key on rooms table
            'id' // Local key on bookings table
        )->whereHas('booking', function ($q) {
            $q->where('status', 'active');
        })->with('user');
    }

    // Your existing relationships
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
    public function currentBooking(): HasOne { return $this->hasOne(Booking::class)->where('status', 'active'); }
    public function maintenanceRequests(): HasMany { return $this->hasMany(MaintenanceRequest::class); }

    // Scopes
    public function scopeAvailable($query)  { return $query->where('status', 'available'); }
    public function scopeOccupied($query)   { return $query->where('status', 'occupied'); }
    public function scopeFull($query)       { return $query->where('status', 'full'); }
}