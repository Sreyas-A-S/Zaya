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
                    } elseif ($effectiveTable === 'finance_managers') {
                        $q->whereIn('finance_managers.country_id', $assignedCountryIds);
                    } else {
                        // For specialized tables like doctors, practitioners, etc.
                        $assignedCountryNames = \App\Models\Country::whereIn('id', $assignedCountryIds)->pluck('name')->toArray();
                        $q->whereIn($effectiveTable . '.country', $assignedCountryNames);
                    }
                });
            }

            if (!empty($user->languages)) {
                $assignedLangIds = is_array($user->languages) ? $user->languages : json_decode($user->languages, true);
                if (!empty($assignedLangIds) && is_array($assignedLangIds)) {
                    $query->where(function ($q) use ($assignedLangIds, $type, $effectiveTable) {
                        if ($effectiveTable === 'users' && $type === 'user') {
                            $q->where(function ($sq) use ($assignedLangIds) {
                                foreach ($assignedLangIds as $id) {
                                    $sq->orWhereJsonContains('users.languages', (string)$id)
                                      ->orWhereJsonContains('users.languages', (int)$id)
                                      ->orWhere('users.languages', $id);
                                }
                            });
                        } elseif (in_array($effectiveTable, ['doctors', 'yoga_therapists', 'mindfulness_practitioners', 'practitioners', 'patients', 'clients'])) {
                            $assignedLangNames = \App\Models\Language::whereIn('id', $assignedLangIds)->pluck('name')->toArray();
                            $q->where(function($sq) use ($effectiveTable, $assignedLangNames) {
                                foreach ($assignedLangNames as $name) {
                                    $sq->orWhereNotNull($effectiveTable . '.languages_spoken->' . $name);
                                }
                            });
                        }
                    });
                }
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
                        $query->where($effectiveTable . '.country', $country->name);
                    }
                } elseif ($type === 'finance_manager') {
                    $query->where('finance_managers.country_id', $country->id);
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
                } elseif ($type === 'finance_manager') {
                    $query->where('finance_managers.language_id', $language->id);
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
            if (in_array($join->table, $specializedTables)) {
                return $join->table;
            }
        }
        
        return $baseTable;
    }
}
