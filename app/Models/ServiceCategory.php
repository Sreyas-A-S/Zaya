<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = \Illuminate\Support\Str::slug($model->name);
            $count = 1;
            while (\App\Models\ServiceCategory::where('slug', $model->slug)->exists()) {
                $model->slug = \Illuminate\Support\Str::slug($model->name) . '-' . $count++;
            }
        });
    }
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'status',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_service_category');
    }

    public function parent()
    {
        return $this->belongsTo(ServiceCategory::class, 'parent_id');
    }

    public function subcategories()
    {
        return $this->hasMany(ServiceCategory::class, 'parent_id');
    }
}
