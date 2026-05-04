<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'native_name',
        'status',
    ];

    public function getDisplayNameAttribute()
    {
        if (str_contains($this->code, '-')) {
            $parts = explode('-', $this->code);
            $region = strtoupper($parts[1]);
            // If the name is already descriptive (contains space or parenthesis), don't add suffix
            if (str_contains($this->name, ' ') || str_contains($this->name, '(')) {
                return $this->name;
            }
            return $this->name . ' (' . $region . ')';
        }
        return $this->name;
    }
}
