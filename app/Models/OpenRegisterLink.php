<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenRegisterLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'token',
        'status',
        'created_by',
        'expires_at',
        'used_at',
        'used_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function usedBy()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    public function registeredUsers()
    {
        return $this->hasMany(User::class, 'open_register_link_id');
    }

    public function getUrlAttribute(): string
    {
        $role = str_replace('_', '-', strtolower((string) $this->role));
        return url('/open-register/' . $role . '/signature=' . $this->token);
    }
}
