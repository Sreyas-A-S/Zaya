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

        // 1. MANDATORY: If not Super Admin, always restrict to assigned countries/languages
        if (!$isSuperAdmin) {
            if (!empty($user->national_id)) {
                $assignedCountryIds = is_array($user->national_id) ? $user->national_id : [$user->national_id];
                
                $query->where(function ($q) use ($assignedCountryIds, $type) {
                    $tableName = $q->getQuery()->from;
                    if ($type === 'user') {
                        if ($tableName === 'users') {
                            $q->where(function ($sq) use ($assignedCountryIds) {
                                foreach ($assignedCountryIds as $id) {
                                    $sq->orWhereJsonContains('national_id', (string)$id)
                                      ->orWhereJsonContains('national_id', (int)$id)
                                      ->orWhere('national_id', $id);
                                }
                            });
                        } else {
                            $assignedCountryNames = Country::whereIn('id', $assignedCountryIds)->pluck('name')->toArray();
                            $q->whereIn($tableName . '.country', $assignedCountryNames);
                        }
                    } elseif ($type === 'finance_manager') {
                        $q->whereIn('finance_managers.country_id', $assignedCountryIds);
                    }
                });
            }

            if (!empty($user->languages)) {
                $assignedLangIds = is_array($user->languages) ? $user->languages : json_decode($user->languages, true);
                if (!empty($assignedLangIds)) {
                    $query->where(function ($q) use ($assignedLangIds, $type) {
                        $tableName = $q->getQuery()->from;
                        if ($type === 'user' && $tableName === 'users') {
                            $q->where(function ($sq) use ($assignedLangIds) {
                                foreach ($assignedLangIds as $id) {
                                    $sq->orWhereJsonContains('languages', (string)$id)
                                      ->orWhereJsonContains('languages', (int)$id)
                                      ->orWhere('languages', $id);
                                }
                            });
                        }
                    });
                }
            }
        }

        // 2. OPTIONAL: Apply navbar filters (only if not 'all')
        if ($adminCountryCode && $adminCountryCode !== 'all') {
            $country = Country::where('code', strtoupper($adminCountryCode))->first();
            if ($country) {
                if ($type === 'user') {
                    $query->where(function ($q) use ($country) {
                        $tableName = $q->getQuery()->from;
                        if ($tableName === 'users') {
                            $q->whereJsonContains('national_id', (string)$country->id)
                              ->orWhereJsonContains('national_id', (int)$country->id);
                        } else {
                            $q->where($tableName . '.country', $country->name);
                        }
                    });
                } elseif ($type === 'finance_manager') {
                    $query->where('finance_managers.country_id', $country->id);
                }
            }
        }

        if ($adminLocale && $adminLocale !== 'all') {
            $language = Language::where('code', $adminLocale)->first();
            if ($language) {
                if ($type === 'user') {
                    $query->where(function ($q) use ($language) {
                        $tableName = $q->getQuery()->from;
                        if ($tableName === 'users') {
                            $q->whereJsonContains('languages', (string)$language->id)
                              ->orWhereJsonContains('languages', (int)$language->id);
                        }
                    });
                } elseif ($type === 'homepage_setting') {
                    $query->where('homepage_settings.language', $adminLocale);
                } elseif ($type === 'finance_manager') {
                    $query->where('finance_managers.language_id', $language->id);
                }
            }
        }

        return $query;
    }
}
