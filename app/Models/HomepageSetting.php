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
        'country_code',
    ];

    /**
     * Get all settings for a section and language with English and Global fallback.
     */
    public static function getSectionValues(string $section, string $language = 'en', string $countryCode = 'all'): array
    {
        static $cache = [];

        $langKey = $section . '|' . $language . '|' . $countryCode;
        if (!isset($cache[$langKey])) {
            $cache[$langKey] = self::where('section', $section)
                ->where('language', $language)
                ->where('country_code', $countryCode)
                ->pluck('value', 'key')
                ->toArray();
        }

        $result = $cache[$langKey];

        // If not 'all' country, fallback to 'all' country for the same language
        if ($countryCode !== 'all') {
            $allCountryKey = $section . '|' . $language . '|all';
            if (!isset($cache[$allCountryKey])) {
                $cache[$allCountryKey] = self::where('section', $section)
                    ->where('language', $language)
                    ->where('country_code', 'all')
                    ->pluck('value', 'key')
                    ->toArray();
            }
            $result = array_merge($cache[$allCountryKey], $result);
        }

        // If not English, fallback to English (with appropriate country logic)
        if ($language !== 'en') {
            $enKey = $section . '|en|' . $countryCode;
            if (!isset($cache[$enKey])) {
                $cache[$enKey] = self::where('section', $section)
                    ->where('language', 'en')
                    ->where('country_code', $countryCode)
                    ->pluck('value', 'key')
                    ->toArray();
            }
            
            $enAllKey = $section . '|en|all';
            if (!isset($cache[$enAllKey])) {
                $cache[$enAllKey] = self::where('section', $section)
                    ->where('language', 'en')
                    ->where('country_code', 'all')
                    ->pluck('value', 'key')
                    ->toArray();
            }

            $enSettings = array_merge($cache[$enAllKey], $cache[$enKey]);
            $result = array_merge($enSettings, $result);
        }

        return $result;
    }

    /**
     * Get all settings for a language with English and Global fallback.
     */
    public static function getAllSettings(string $language = 'en', ?string $prefix = null, string $countryCode = 'all'): array
    {
        static $cache = [];

        $cacheKey = $language . '|' . $countryCode . ($prefix ? '|' . $prefix : '');

        if (!isset($cache[$cacheKey])) {
            // 1. English + All (the absolute base)
            $enAllQuery = self::where('language', 'en')->where('country_code', 'all');
            if ($prefix) {
                $enAllQuery->where('key', 'like', $prefix . '%');
            }
            $result = $enAllQuery->pluck('value', 'key')->toArray();

            // 2. English + Specific Country (if applicable)
            if ($countryCode !== 'all') {
                $enCountryQuery = self::where('language', 'en')->where('country_code', $countryCode);
                if ($prefix) {
                    $enCountryQuery->where('key', 'like', $prefix . '%');
                }
                $result = array_merge($result, $enCountryQuery->pluck('value', 'key')->toArray());
            }

            // 3. Language + All (if applicable)
            if ($language !== 'en') {
                $langAllQuery = self::where('language', $language)->where('country_code', 'all');
                if ($prefix) {
                    $langAllQuery->where('key', 'like', $prefix . '%');
                }
                $result = array_merge($result, $langAllQuery->pluck('value', 'key')->toArray());
            }

            // 4. Language + Specific Country (the most specific)
            if ($language !== 'en' && $countryCode !== 'all') {
                $langCountryQuery = self::where('language', $language)->where('country_code', $countryCode);
                if ($prefix) {
                    $langCountryQuery->where('key', 'like', $prefix . '%');
                }
                $result = array_merge($result, $langCountryQuery->pluck('value', 'key')->toArray());
            }

            $cache[$cacheKey] = $result;
        }

        return $cache[$cacheKey];
    }
}
