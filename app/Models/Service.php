<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'slug',
        'status',
        'order_column',
    ];

    public function categories()
    {
        return $this->belongsToMany(ServiceCategory::class, 'service_service_category');
    }

    public function images()
    {
        return $this->hasMany(ServiceImage::class);
    }
}
