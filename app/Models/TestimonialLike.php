<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimonialLike extends Model
{
    protected $fillable = ['testimonial_id', 'ip_address', 'user_agent'];

    public function testimonial()
    {
        return $this->belongsTo(Testimonial::class);
    }
}
