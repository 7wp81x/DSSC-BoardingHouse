<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;  // For studentBoarder

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'room_id', 'check_in_date', 'check_out_date', 'monthly_due_date', 'status'];

    protected $casts = [
        'check_in_date' => 'date',      // ← Cast as Carbon date
        'check_out_date' => 'date',     // ← Cast as Carbon date
        'monthly_due_date' => 'integer',   // ← Cast as Carbon date (if full date; or 'integer' if day-only)
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function room() { return $this->belongsTo(Room::class); }
    public function bills() { return $this->hasMany(Bill::class); }

    public function getNextPaymentDayAttribute()
    {
        if (!$this->monthly_due_date) return null;
        
        $dueDay = $this->monthly_due_date;
        $now = now();
        $dueThisMonth = $now->copy()->startOfMonth()->addDays($dueDay - 1);
        
        // If due this month is past, or if it's before check-in, push to next month
        if ($dueThisMonth->isPast() || ($this->check_in_date && $dueThisMonth->lt($this->check_in_date))) {
            $dueThisMonth->addMonth();
        }
        
        return $dueThisMonth;
    }

    public function studentBoarder(): HasOne
    {
        return $this->hasOne(StudentBoarder::class);
    }
}