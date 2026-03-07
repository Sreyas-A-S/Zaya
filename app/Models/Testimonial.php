<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'role', 'message', 'rating', 'image', 'status'];

    public function likes()
    {
        return $this->hasMany(TestimonialLike::class);
    }

    public function replies()
    {
        return $this->hasMany(TestimonialReply::class);
    }
}
