<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AyurvedaExpertise extends Model
{
    protected $table = 'ayurveda_expertises';
    protected $fillable = ['name', 'status'];
}