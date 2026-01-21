<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MindfulnessCounsellor extends Model
{
    use HasFactory;

    protected $table = 'mindfulness_counsellors';

    protected $guarded = ['id'];

    protected $casts = [
        'dob' => 'date',
        'website_social_links' => 'array',
        'certificates_path' => 'array',
        'services_offered' => 'array',
        'client_concerns' => 'array',
        'consultation_modes' => 'array',
        'languages_spoken' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
