<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PractitionerQualification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function profile()
    {
        return $this->belongsTo(PractitionerProfile::class, 'practitioner_profile_id');
    }
}