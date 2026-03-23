<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;

class ClientPanelSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'client_panel_book_session_btn', 'value' => 'Book a New Consultation', 'type' => 'text', 'section' => 'client_panel_general'],
            ['key' => 'client_panel_back_btn', 'value' => 'Back', 'type' => 'text', 'section' => 'client_panel_general'],

            // Identity Hub
            ['key' => 'client_panel_identity_hub_title', 'value' => 'Identity Hub', 'type' => 'text', 'section' => 'client_panel_identity'],
            ['key' => 'client_panel_age_label', 'value' => 'Age', 'type' => 'text', 'section' => 'client_panel_identity', 'max_length' => 20],
            ['key' => 'client_panel_gender_label', 'value' => 'Gender', 'type' => 'text', 'section' => 'client_panel_identity', 'max_length' => 20],
            ['key' => 'client_panel_dob_label', 'value' => 'DOB', 'type' => 'text', 'section' => 'client_panel_identity', 'max_length' => 20],
            ['key' => 'client_panel_email_label', 'value' => 'Email', 'type' => 'text', 'section' => 'client_panel_identity', 'max_length' => 20],
            ['key' => 'client_panel_phone_label', 'value' => 'Phone', 'type' => 'text', 'section' => 'client_panel_identity', 'max_length' => 20],
            ['key' => 'client_panel_address_label', 'value' => 'Address', 'type' => 'text', 'section' => 'client_panel_identity', 'max_length' => 20],
            ['key' => 'client_panel_not_set', 'value' => 'Not set', 'type' => 'text', 'section' => 'client_panel_identity'],
            ['key' => 'client_panel_years', 'value' => 'Years', 'type' => 'text', 'section' => 'client_panel_identity'],
            ['key' => 'client_panel_location_not_set', 'value' => 'Location not set', 'type' => 'text', 'section' => 'client_panel_identity'],

            // Consultations
            ['key' => 'client_panel_consultations_title', 'value' => 'Consultations', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_upcoming_tab', 'value' => 'Upcoming', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_completed_tab', 'value' => 'Completed', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_no_upcoming_msg', 'value' => 'No upcoming sessions.', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_no_completed_msg', 'value' => 'No completed sessions recently.', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_view_all_bookings', 'value' => 'View All Bookings', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_session_with', 'value' => 'Session with', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_client_label', 'value' => 'Client', 'type' => 'text', 'section' => 'client_panel_consultations'],

            // Documents
            ['key' => 'client_panel_clinical_portal_title', 'value' => 'Clinical Document Portal', 'type' => 'text', 'section' => 'client_panel_documents'],
            ['key' => 'client_panel_drag_drop_heading', 'value' => 'Drag and Drop files here', 'type' => 'text', 'section' => 'client_panel_documents'],
            ['key' => 'client_panel_upload_description', 'value' => 'Upload X-Rays, MRIs, Blood tests and other clinical documents', 'type' => 'textarea', 'section' => 'client_panel_documents'],
            ['key' => 'client_panel_file_types_info', 'value' => 'JPG, JPEG, PNG, WPS, DOC & PDF (Max 20MB)', 'type' => 'text', 'section' => 'client_panel_documents'],
            ['key' => 'client_panel_upload_btn', 'value' => 'Upload', 'type' => 'text', 'section' => 'client_panel_documents'],
            ['key' => 'client_panel_uploaded_documents_title', 'value' => 'Uploaded Documents', 'type' => 'text', 'section' => 'client_panel_documents'],
            ['key' => 'client_panel_see_all', 'value' => 'See all', 'type' => 'text', 'section' => 'client_panel_documents'],

            // Transactions
            ['key' => 'client_panel_transaction_vault_title', 'value' => 'Transaction Vault', 'type' => 'text', 'section' => 'client_panel_transactions'],
            ['key' => 'client_panel_no_recent_invoices', 'value' => 'No recent invoices.', 'type' => 'text', 'section' => 'client_panel_transactions'],
            ['key' => 'client_panel_invoice_hash', 'value' => 'Invoice #', 'type' => 'text', 'section' => 'client_panel_transactions'],
            ['key' => 'client_panel_open_invoice', 'value' => 'Open', 'type' => 'text', 'section' => 'client_panel_transactions'],

            // Reviews
            ['key' => 'client_panel_your_reviews_title', 'value' => 'Your Reviews', 'type' => 'text', 'section' => 'client_panel_reviews'],
            ['key' => 'client_panel_no_reviews_msg', 'value' => 'You haven\'t written any reviews yet.', 'type' => 'text', 'section' => 'client_panel_reviews'],
            ['key' => 'client_panel_rating_label', 'value' => 'Rating', 'type' => 'text', 'section' => 'client_panel_reviews'],
            ['key' => 'client_panel_comment_label', 'value' => 'Comment', 'type' => 'text', 'section' => 'client_panel_reviews'],

            // GDPR
            ['key' => 'client_panel_gdpr_title', 'value' => 'General Data Protection Regulation Control Center', 'type' => 'textarea', 'section' => 'client_panel_gdpr'],
            ['key' => 'client_panel_data_sharing_label', 'value' => 'Data sharing with Practitioners', 'type' => 'text', 'section' => 'client_panel_gdpr'],
            ['key' => 'client_panel_gdpr_modal_title', 'value' => 'Update Data Sharing?', 'type' => 'text', 'section' => 'client_panel_gdpr'],
            ['key' => 'client_panel_gdpr_confirm_btn', 'value' => 'Confirm', 'type' => 'text', 'section' => 'client_panel_gdpr'],
            ['key' => 'client_panel_gdpr_cancel_btn', 'value' => 'Cancel', 'type' => 'text', 'section' => 'client_panel_gdpr'],

            // Sidebar
            ['key' => 'client_panel_sidebar_dashboard', 'value' => 'Dashboard', 'type' => 'text', 'section' => 'client_panel_sidebar'],
            ['key' => 'client_panel_sidebar_health_journey', 'value' => 'Health Journey', 'type' => 'text', 'section' => 'client_panel_sidebar'],
            ['key' => 'client_panel_sidebar_bookings', 'value' => 'Bookings', 'type' => 'text', 'section' => 'client_panel_sidebar'],
            ['key' => 'client_panel_sidebar_conference_history', 'value' => 'Conference History', 'type' => 'text', 'section' => 'client_panel_sidebar'],
            ['key' => 'client_panel_sidebar_transaction_vault', 'value' => 'Transaction Vault', 'type' => 'text', 'section' => 'client_panel_sidebar'],
            ['key' => 'client_panel_sidebar_time_slots', 'value' => 'Time Slots', 'type' => 'text', 'section' => 'client_panel_sidebar'],
            ['key' => 'client_panel_sidebar_logout', 'value' => 'Logout', 'type' => 'text', 'section' => 'client_panel_sidebar'],
        ];

        foreach ($settings as $setting) {
            HomepageSetting::updateOrCreate(
                ['key' => $setting['key'], 'language' => 'en'],
                $setting
            );
        }
    }
}
