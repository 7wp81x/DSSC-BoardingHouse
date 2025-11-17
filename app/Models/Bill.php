<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_id', 
        'user_id',
        'amount', 
        'due_date', 
        'paid_at', 
        'status',
        'type',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_PENDING_PAYMENT = 'pending_payment';

    // Type constants
    const TYPE_RENT = 'rent';
    const TYPE_MAINTENANCE = 'maintenance';
    const TYPE_OTHER = 'other';

    public function booking() 
    { 
        return $this->belongsTo(Booking::class); 
    }

    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }

    public function payments() 
    { 
        return $this->hasMany(Payment::class); 
    }

    // Add this method to get room through booking
    public function room()
    {
        return $this->hasOneThrough(
            Room::class,
            Booking::class,
            'id', // Foreign key on bookings table
            'id', // Foreign key on rooms table  
            'booking_id', // Local key on bills table
            'room_id' // Local key on bookings table
        );
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE)
                    ->where('due_date', '<', now());
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('user_id', $studentId);
    }

    // Helpers
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPaid()
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isOverdue()
    {
        return $this->status === self::STATUS_OVERDUE || 
               ($this->isPending() && $this->due_date->isPast());
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now()
        ]);
    }

    public function markAsPendingPayment()
    {
        $this->update([
            'status' => self::STATUS_PENDING_PAYMENT
        ]);
    }

    public function getStatusColor()
    {
        return match($this->status) {
            self::STATUS_PAID => 'green',
            self::STATUS_PENDING => 'yellow',
            self::STATUS_OVERDUE => 'red',
            self::STATUS_PENDING_PAYMENT => 'blue',
            default => 'gray'
        };
    }
}