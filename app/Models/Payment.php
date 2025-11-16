<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = ['bill_id', 'user_id', 'amount', 'payment_method', 'reference_number', 'receipt_image', 'created_by'];
    protected $casts = ['amount' => 'decimal:2', 'paid_at' => 'datetime'];

    public function bill() { return $this->belongsTo(Bill::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}