<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'language',
        'question',
        'answer',
        'status',
    ];
}
