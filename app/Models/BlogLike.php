<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogLike extends Model
{
    protected $fillable = ['post_id', 'ip_address', 'user_agent'];
}
