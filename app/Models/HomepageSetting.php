<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSetting extends Model
{

    protected $fillable = [
        'key',
        'value',
        'type',
        'section',
        'max_length',
        'language',
    ];

    /**
     * Get all settings for a section and language with English fallback.
     */
    public static function getSectionValues(string $section, string $language = 'en'): array
    {
        static $cache = [];

        $langKey = $section . '|' . $language;
        if (!isset($cache[$langKey])) {
            $cache[$langKey] = self::where('section', $section)
                ->where('language', $language)
                ->pluck('value', 'key')
                ->toArray();
        }

        if ($language !== 'en') {
            $enKey = $section . '|en';
            if (!isset($cache[$enKey])) {
                $cache[$enKey] = self::where('section', $section)
                    ->where('language', 'en')
                    ->pluck('value', 'key')
                    ->toArray();
            }
            return array_merge($cache[$enKey], $cache[$langKey]);
        }

        return $cache[$langKey];
    }

    /**
     * Get all settings for a language with English fallback.
     */
    public static function getAllSettings(string $language = 'en', ?string $prefix = null): array
    {
        static $cache = [];

        $cacheKey = $language . ($prefix ? '|' . $prefix : '');

        if (!isset($cache[$cacheKey])) {
            $enQuery = self::where('language', 'en');
            if ($prefix) {
                $enQuery->where('key', 'like', $prefix . '%');
            }
            $enSettings = $enQuery->pluck('value', 'key')->toArray();

            if ($language !== 'en') {
                $langQuery = self::where('language', $language);
                if ($prefix) {
                    $langQuery->where('key', 'like', $prefix . '%');
                }
                $langSettings = $langQuery->pluck('value', 'key')->toArray();
                $cache[$cacheKey] = array_merge($enSettings, $langSettings);
            } else {
                $cache[$cacheKey] = $enSettings;
            }
        }

        return $cache[$cacheKey];
    }
}
