<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/languages.json');
        $languages = json_decode($json, true);

        foreach ($languages as $language) {
            try {
                Language::updateOrCreate(
                    ['code' => $language['Code']], // Use Code as unique identifier
                    [
                        'name' => $language['EnglishName'] ?? $language['NativeName'], // Fallback if EnglishName missing
                        'native_name' => $language['NativeName'],
                        'flag' => $language['Flag'] ?? null,
                    ]
                );
            } catch (\Exception $e) {
                // If it fails (likely due to emoji charset issue), try without flag
                Language::updateOrCreate(
                    ['code' => $language['Code']],
                    [
                        'name' => $language['EnglishName'] ?? $language['NativeName'],
                        'native_name' => $language['NativeName'],
                        'flag' => null,
                    ]
                );
            }
        }
    }
}