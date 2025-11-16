<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceRequest extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'room_id', 'title', 'description', 'priority', 'status', 'resolved_by', 'resolved_at'];

    public function user() { return $this->belongsTo(User::class); }
    public function room() { return $this->belongsTo(Room::class); }
    public function resolver() { return $this->belongsTo(User::class, 'resolved_by'); }
}