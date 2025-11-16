<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'instructions',
        'account_name',
        'account_number',
        'email',
        'qr_code_image',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_method', 'name');
    }

    // Scope for active methods
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordered methods
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}