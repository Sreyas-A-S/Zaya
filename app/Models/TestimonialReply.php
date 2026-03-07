<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimonialReply extends Model
{
    protected $fillable = ['testimonial_id', 'name', 'role', 'reply', 'status'];

    public function testimonial()
    {
        return $this->belongsTo(Testimonial::class);
    }
}
