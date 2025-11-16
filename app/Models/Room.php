<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'room_code',
        'type',        // single, twin, quad, premium
        'price',
        'status',      // available, occupied, maintenance
        'image',
        'amenities',   // JSON array
        'description'
    ];

    protected $casts = [
        'amenities' => 'array',
        'price'     => 'decimal:2',
    ];

    // Add this relationship for multiple images
    public function images()
    {
        return $this->hasMany(RoomImage::class)->orderBy('is_primary', 'desc')->orderBy('order');
    }

    // Your existing relationships
    public function bookings()          { return $this->hasMany(Booking::class); }
    public function currentBooking()    { return $this->hasOne(Booking::class)->where('status', 'active'); }
    public function maintenanceRequests(){ return $this->hasMany(MaintenanceRequest::class); }

    // Scopes
    public function scopeAvailable($query)  { return $query->where('status', 'available'); }
    public function scopeOccupied($query)   { return $query->where('status', 'occupied'); }
}