<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bill_id', 
        'user_id', 
        'amount', 
        'payment_method', 
        'reference_number', 
        'receipt_image', 
        'status',
        'rejection_reason',
        'created_by',
        'processed_by',
        'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2', 
        'paid_at' => 'datetime',
        'processed_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    public function bill() 
    { 
        return $this->belongsTo(Bill::class); 
    }

    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }

    public function creator() 
    { 
        return $this->belongsTo(User::class, 'created_by'); 
    }

    public function processor() 
    { 
        return $this->belongsTo(User::class, 'processed_by'); 
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('user_id', $studentId);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Helpers
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function getStatusColor()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_APPROVED => 'green',
            self::STATUS_REJECTED => 'red',
            self::STATUS_CANCELLED => 'gray',
            default => 'gray'
        };
    }

    public function getStatusIcon()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'clock',
            self::STATUS_APPROVED => 'check-circle',
            self::STATUS_REJECTED => 'x-circle',
            self::STATUS_CANCELLED => 'ban',
            default => 'question-mark'
        };
    }
}