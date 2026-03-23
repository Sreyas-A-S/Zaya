<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PractitionerAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'practitioner_id',
        'day_of_week',
        'specific_date',
        'start_time',
        'end_time',
        'slot_duration',
        'min_notice_hours',
        'is_available'
    ];

    protected $casts = [
        'specific_date' => 'date',
        'is_available' => 'boolean',
        'slot_duration' => 'integer',
        'min_notice_hours' => 'integer',
    ];

    public function practitioner()
    {
        return $this->belongsTo(Practitioner::class);
    }
}
