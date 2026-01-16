<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    protected $guarded = [];

    protected $casts = [
        'dob' => 'date',
        'consultation_preferences' => 'array',
        'languages_spoken' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
