<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceManager extends Model
{
       use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'country_id',
        'language_id',
        'status'
    ];

    protected $hidden = [
        'password',
    ];
}
