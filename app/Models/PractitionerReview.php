<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PractitionerReview extends Model
{
    protected $fillable = ['practitioner_id', 'user_id', 'rating', 'review', 'status'];

    public function practitioner()
    {
        return $this->belongsTo(Practitioner::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
