<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentBoarder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'booking_id',
        'approval_status',
        'next_payment_due',
        'room_assignment_notes',
        'kick_notice_sent',
    ];

    protected $casts = [
        'next_payment_due' => 'date',
        'kick_notice_sent' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($boarder) {
            if ($boarder->isDirty('approval_status') && $boarder->approval_status === 'approved') {
                // Auto-approve booking
                $booking = $boarder->booking;
                $booking->update(['status' => 'active']);

                // Only update room status if room exists
                if ($booking->room) {
                    $booking->room->update(['status' => 'occupied']);
                } else {
                    // Log or handle the case where room is missing
                    \Log::warning("Booking {$booking->id} approved but has no room assigned");
                }

                // Generate initial bill if room exists and has price
                if ($booking->room && $booking->room->price) {
                    $billGenerator = new \App\Services\BillGenerator();
                    $billGenerator->generateInitialBill($booking);
                }

                // Sync next due date
                $boarder->next_payment_due = $booking->monthly_due_date ? 
                    now()->startOfMonth()->addDays($booking->monthly_due_date - 1) : null;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function room(): HasOneThrough
    {
        return $this->hasOneThrough(
            Room::class,
            Booking::class,
            'id',
            'id',
            'booking_id',
            'room_id'
        );
    }

    public function currentBill(): BelongsTo
    {
        return $this->belongsTo(Bill::class, 'booking_id', 'booking_id')
            ->where('status', 'pending')
            ->latest('due_date')
            ->limit(1);
    }

    public function directedAnnouncements()
    {
        return $this->hasMany(Announcement::class, 'directed_to_user_id', 'user_id')
            ->where('content', 'like', '%kick%')
            ->latest();
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function isPaymentOverdue(): bool
    {
        return $this->next_payment_due && $this->next_payment_due->isPast();
    }

    // Add accessor for current month paid status (keep this)
    public function getIsCurrentMonthPaidAttribute()
    {
        $currentMonth = now()->startOfMonth();
        $nextMonth = $currentMonth->copy()->addMonth();
        
        $latestBill = $this->booking->bills()
            ->whereBetween('due_date', [$currentMonth, $nextMonth->subDay()])
            ->latest('due_date')
            ->first();
        
        return $latestBill && $latestBill->status === 'paid';
    }
}