<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translator extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'dob' => 'date',
        'source_languages' => 'array',
        'target_languages' => 'array',
        'additional_languages' => 'array',
        'fields_of_specialization' => 'array',
        'certificates_path' => 'array',
        'sample_work_path' => 'array',
        'services_offered' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
