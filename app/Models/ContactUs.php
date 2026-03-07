<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $table = 'contact_us_messages';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'user_type',
        'message',
    ];

    protected $casts = [
        'user_type' => 'array',
    ];
}
