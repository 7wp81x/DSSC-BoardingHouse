<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use SoftDeletes;

    protected $fillable = ['booking_id', 'amount', 'due_date', 'paid_at', 'status'];
    protected $casts = ['amount' => 'decimal:2'];

    public function booking() { return $this->belongsTo(Booking::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
