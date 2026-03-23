<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\HomepageSetting;

class InvoiceSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            // EN Settings
            ['key' => 'invoice_main_title', 'value' => 'Your Session has been Booked!', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'en'],
            ['key' => 'invoice_subtitle', 'value' => 'Please check your email for confirmation and further instruction.', 'type' => 'textarea', 'section' => 'invoice_settings', 'max_length' => 255, 'language' => 'en'],
            ['key' => 'invoice_client_name', 'value' => 'Client Name', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_client_id', 'value' => 'Client ID', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_client_dob', 'value' => 'DOB', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_client_location', 'value' => 'Location', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_sessions_title', 'value' => 'Sessions', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_service_col', 'value' => 'Service', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_date_col', 'value' => 'Date', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_time_col', 'value' => 'Time', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_no_services', 'value' => 'No services listed.', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'en'],
            ['key' => 'invoice_total_amount', 'value' => 'Total Amount', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_practitioner_title', 'value' => 'Sessions with Practitioner', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'en'],
            ['key' => 'invoice_footer_thanks', 'value' => 'Thanks for Booking!', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'en'],
            ['key' => 'invoice_footer_queries', 'value' => 'For more queries', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'en'],
            ['key' => 'invoice_btn_share', 'value' => 'Share', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_btn_download', 'value' => 'Download', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],

            // FR Settings
            ['key' => 'invoice_main_title', 'value' => 'Votre session a été réservée !', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'fr'],
            ['key' => 'invoice_subtitle', 'value' => 'Veuillez vérifier votre e-mail pour obtenir une confirmation et des instructions supplémentaires.', 'type' => 'textarea', 'section' => 'invoice_settings', 'max_length' => 255, 'language' => 'fr'],
            ['key' => 'invoice_client_name', 'value' => 'Nom du client', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_client_id', 'value' => 'ID client', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_client_dob', 'value' => 'Date de naissance', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_client_location', 'value' => 'Lieu', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_sessions_title', 'value' => 'Sessions', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_service_col', 'value' => 'Service', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_date_col', 'value' => 'Date', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_time_col', 'value' => 'Heure', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_no_services', 'value' => 'Aucun service répertorié.', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'fr'],
            ['key' => 'invoice_total_amount', 'value' => 'Montant total', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_practitioner_title', 'value' => 'Sessions avec le praticien', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'fr'],
            ['key' => 'invoice_footer_thanks', 'value' => 'Merci pour votre réservation !', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'fr'],
            ['key' => 'invoice_footer_queries', 'value' => 'Pour plus de questions', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'fr'],
            ['key' => 'invoice_btn_share', 'value' => 'Partager', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_btn_download', 'value' => 'Télécharger', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
        ];

        foreach ($settings as $setting) {
            HomepageSetting::updateOrCreate(
                [
                    'key' => $setting['key'],
                    'language' => $setting['language']
                ],
                $setting
            );
        }
    }
}
