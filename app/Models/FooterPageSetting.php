<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterPageSetting extends Model
{
    use HasFactory;

    protected $table = 'homepage_settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'section',
        'max_length',
        'language'
    ];

    protected $casts = [
        'max_length' => 'integer'
    ];

    /**
     * Get setting value by key and language
     */
    public static function getValue($key, $language = 'en')
    {
        return self::where('key', $key)
            ->where('language', $language)
            ->value('value');
    }

    /**
     * Get all footer settings for a language
     */
    public static function getSettings($language = 'en')
    {
        return self::where('language', $language)
            ->get()
            ->keyBy('key');
    }
}