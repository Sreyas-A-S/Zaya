<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAccessRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_id',
        'client_id',
        'otp',
        'status',
        'expires_at',
        'approved_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
