<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'service_ids',
        'status',
        'order_column',
    ];

    protected $casts = [
        'service_ids' => 'array',
        'status' => 'boolean',
        'order_column' => 'integer',
    ];

    public function getServicesAttribute()
    {
        $ids = array_values(array_filter((array) $this->service_ids));

        if ($ids === []) {
            return collect();
        }

        $services = Service::whereIn('id', $ids)->get()->keyBy('id');

        return collect($ids)
            ->map(fn ($id) => $services->get($id))
            ->filter()
            ->values();
    }
}
