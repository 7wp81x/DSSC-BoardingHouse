<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'is_pinned', 'published_at', 'created_by'];

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function directedStudent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'directed_to_user_id');
    }
}