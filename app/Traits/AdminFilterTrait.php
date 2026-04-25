<?php

namespace App\Traits;

use App\Models\Country;
use App\Models\Language;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

trait AdminFilterTrait
{
    /**
     * Apply country and language filters to the query.
     */
    public function applyAdminFilters($query, $type = 'user')
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) return $query;
        
        $role = $user->roleData();
        $isSuperAdmin = ($role && $role->name === 'Super Admin');

        $adminCountryCode = session('admin_country');
        $adminLocale = session('locale');

        $effectiveTable = $this->getEffectiveFilterTable($query);

        // 1. MANDATORY: If not Super Admin, always restrict to assigned countries/languages
        if (!$isSuperAdmin) {
            if (!empty($user->national_id)) {
                $assignedCountryIds = is_array($user->national_id) ? $user->national_id : [$user->national_id];
                
                $query->where(function ($q) use ($assignedCountryIds, $type, $effectiveTable) {
                    if ($effectiveTable === 'users' && $type === 'user') {
                        $q->where(function ($sq) use ($assignedCountryIds) {
                            foreach ($assignedCountryIds as $id) {
                                $sq->orWhereJsonContains('users.national_id', (string)$id)
                                  ->orWhereJsonContains('national_id', (int)$id)
                                  ->orWhere('national_id', $id);
                            }
                        });
                    } elseif ($effectiveTable === 'financial-managers') {
                        $q->whereIn('financial-managers.country_id', $assignedCountryIds);
                    } else {
                        // For specialized tables like doctors, practitioners, etc.
                        $countries = \App\Models\Country::whereIn('id', $assignedCountryIds)->get();
                        $assignedCountryNames = $countries->pluck('name')->toArray();
                        
                        // Add common abbreviations/variants for better matching
                        $variants = [];
                        foreach ($assignedCountryNames as $name) {
                            if ($name === 'United Arab Emirates') $variants[] = 'UAE';
                            if ($name === 'United Kingdom') $variants[] = 'UK';
                            if ($name === 'United States') $variants[] = 'USA';
                            // Add more if needed or use a mapping
                        }
                        $allSearchNames = array_merge($assignedCountryNames, $variants);
                        
                        $q->whereIn($effectiveTable . '.country', $allSearchNames);
                    }
                });
            } else {
                // If not Super Admin and has NO assigned countries, they should see NO data
                $query->whereRaw('1 = 0');
                return $query;
            }
        }

        // 2. OPTIONAL: Apply navbar filters (only if not 'all')
        if ($adminCountryCode && $adminCountryCode !== 'all') {
            $country = \App\Models\Country::where('code', strtoupper($adminCountryCode))->first();
            if ($country) {
                if ($type === 'user') {
                    if ($effectiveTable === 'users') {
                        $query->where(function ($q) use ($country) {
                            $q->whereJsonContains('users.national_id', (string)$country->id)
                              ->orWhereJsonContains('users.national_id', (int)$country->id);
                        });
                    } else {
                        $searchNames = [$country->name];
                        if ($country->name === 'United Arab Emirates') $searchNames[] = 'UAE';
                        if ($country->name === 'United Kingdom') $searchNames[] = 'UK';
                        if ($country->name === 'United States') $searchNames[] = 'USA';
                        $query->whereIn($effectiveTable . '.country', $searchNames);
                    }
                } elseif ($type === 'financial-manager') {
                    $query->where('financial-managers.country_id', $country->id);
                }
            }
        }

        if ($adminLocale && $adminLocale !== 'all') {
            $language = \App\Models\Language::where('code', $adminLocale)->first();
            if ($language) {
                if ($type === 'user') {
                    if ($effectiveTable === 'users') {
                        $query->where(function ($q) use ($language) {
                            $q->whereJsonContains('users.languages', (string)$language->id)
                              ->orWhereJsonContains('users.languages', (int)$language->id);
                        });
                    } elseif (in_array($effectiveTable, ['doctors', 'yoga_therapists', 'mindfulness_practitioners', 'practitioners', 'patients', 'clients'])) {
                        $query->whereNotNull($effectiveTable . '.languages_spoken->' . $language->name);
                    }
                } elseif ($type === 'homepage_setting') {
                    $query->where('homepage_settings.language', $adminLocale);
                } elseif ($type === 'financial-manager') {
                    $query->where('financial-managers.language_id', $language->id);
                }
            }
        }

        return $query;
    }

    /**
     * Helper to determine which table to use for filtering.
     */
    protected function getEffectiveFilterTable($query)
    {
        $baseTable = $query->getQuery()->from;
        
        // Check for joined specialized tables
        $joins = $query->getQuery()->joins ?? [];
        $specializedTables = [
            'doctors', 'yoga_therapists', 'mindfulness_practitioners', 
            'practitioners', 'translators', 'patients', 'clients'
        ];
        
        foreach ($joins as $join) {
            $tableName = is_string($join->table) ? trim($join->table) : $join->table;
            if (in_array($tableName, $specializedTables)) {
                return $tableName;
            }
        }
        
        return $baseTable;
    }
}
