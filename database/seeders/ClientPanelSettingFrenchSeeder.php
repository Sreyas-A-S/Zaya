<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSetting;

class ClientPanelSettingFrenchSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'client_panel_book_session_btn', 'value' => 'Réserver une nouvelle consultation', 'type' => 'text', 'section' => 'client_panel_general'],
            ['key' => 'client_panel_back_btn', 'value' => 'Retour', 'type' => 'text', 'section' => 'client_panel_general'],

            // Identity Hub
            ['key' => 'client_panel_identity_hub_title', 'value' => 'Centre d\'identité', 'type' => 'text', 'section' => 'client_panel_identity'],
            ['key' => 'client_panel_age_label', 'value' => 'Âge', 'type' => 'text', 'section' => 'client_panel_identity', 'max_length' => 20],
            ['key' => 'client_panel_gender_label', 'value' => 'Genre', 'type' => 'text', 'section' => 'client_panel_identity', 'max_length' => 20],
            ['key' => 'client_panel_dob_label', 'value' => 'Date de naissance', 'type' => 'text', 'section' => 'client_panel_identity', 'max_length' => 20],
            ['key' => 'client_panel_email_label', 'value' => 'E-mail', 'type' => 'text', 'section' => 'client_panel_identity', 'max_length' => 20],
            ['key' => 'client_panel_phone_label', 'value' => 'Téléphone', 'type' => 'text', 'section' => 'client_panel_identity', 'max_length' => 20],
            ['key' => 'client_panel_address_label', 'value' => 'Adresse', 'type' => 'text', 'section' => 'client_panel_identity', 'max_length' => 20],
            ['key' => 'client_panel_not_set', 'value' => 'Non défini', 'type' => 'text', 'section' => 'client_panel_identity'],
            ['key' => 'client_panel_years', 'value' => 'Ans', 'type' => 'text', 'section' => 'client_panel_identity'],
            ['key' => 'client_panel_location_not_set', 'value' => 'Emplacement non défini', 'type' => 'text', 'section' => 'client_panel_identity'],

            // Consultations
            ['key' => 'client_panel_consultations_title', 'value' => 'Consultations', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_upcoming_tab', 'value' => 'À venir', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_completed_tab', 'value' => 'Terminé', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_no_upcoming_msg', 'value' => 'Aucune session à venir.', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_no_completed_msg', 'value' => 'Aucune session terminée récemment.', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_view_all_bookings', 'value' => 'Voir toutes les réservations', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_session_with', 'value' => 'Session avec', 'type' => 'text', 'section' => 'client_panel_consultations'],
            ['key' => 'client_panel_client_label', 'value' => 'Client', 'type' => 'text', 'section' => 'client_panel_consultations'],

            // Documents
            ['key' => 'client_panel_clinical_portal_title', 'value' => 'Portail de documents cliniques', 'type' => 'text', 'section' => 'client_panel_documents'],
            ['key' => 'client_panel_drag_drop_heading', 'value' => 'Glisser et déposer les fichiers ici', 'type' => 'text', 'section' => 'client_panel_documents'],
            ['key' => 'client_panel_upload_description', 'value' => 'Télécharger des radiographies, des IRM, des analyses de sang et d\'autres documents cliniques', 'type' => 'textarea', 'section' => 'client_panel_documents'],
            ['key' => 'client_panel_file_types_info', 'value' => 'JPG, JPEG, PNG, WPS, DOC & PDF (Max 20 Mo)', 'type' => 'text', 'section' => 'client_panel_documents'],
            ['key' => 'client_panel_upload_btn', 'value' => 'Télécharger', 'type' => 'text', 'section' => 'client_panel_documents'],
            ['key' => 'client_panel_uploaded_documents_title', 'value' => 'Documents téléchargés', 'type' => 'text', 'section' => 'client_panel_documents'],
            ['key' => 'client_panel_see_all', 'value' => 'Voir tout', 'type' => 'text', 'section' => 'client_panel_documents'],

            // Transactions
            ['key' => 'client_panel_transaction_vault_title', 'value' => 'Coffre-fort des transactions', 'type' => 'text', 'section' => 'client_panel_transactions'],
            ['key' => 'client_panel_no_recent_invoices', 'value' => 'Aucune facture récente.', 'type' => 'text', 'section' => 'client_panel_transactions'],
            ['key' => 'client_panel_invoice_hash', 'value' => 'Facture #', 'type' => 'text', 'section' => 'client_panel_transactions'],
            ['key' => 'client_panel_open_invoice', 'value' => 'Ouvrir', 'type' => 'text', 'section' => 'client_panel_transactions'],

            // Reviews
            ['key' => 'client_panel_your_reviews_title', 'value' => 'Vos avis', 'type' => 'text', 'section' => 'client_panel_reviews'],
            ['key' => 'client_panel_no_reviews_msg', 'value' => 'Vous n\'avez pas encore écrit d\'avis.', 'type' => 'text', 'section' => 'client_panel_reviews'],
            ['key' => 'client_panel_rating_label', 'value' => 'Évaluation', 'type' => 'text', 'section' => 'client_panel_reviews'],
            ['key' => 'client_panel_comment_label', 'value' => 'Commentaire', 'type' => 'text', 'section' => 'client_panel_reviews'],

            // GDPR
            ['key' => 'client_panel_gdpr_title', 'value' => 'Centre de contrôle du règlement général sur la protection des données', 'type' => 'textarea', 'section' => 'client_panel_gdpr'],
            ['key' => 'client_panel_data_sharing_label', 'value' => 'Partage de données avec les praticiens', 'type' => 'text', 'section' => 'client_panel_gdpr'],
            ['key' => 'client_panel_gdpr_modal_title', 'value' => 'Mettre à jour le partage de données ?', 'type' => 'text', 'section' => 'client_panel_gdpr'],
            ['key' => 'client_panel_gdpr_confirm_btn', 'value' => 'Confirmer', 'type' => 'text', 'section' => 'client_panel_gdpr'],
            ['key' => 'client_panel_gdpr_cancel_btn', 'value' => 'Annuler', 'type' => 'text', 'section' => 'client_panel_gdpr'],

            // Sidebar
            ['key' => 'client_panel_sidebar_dashboard', 'value' => 'Tableau de bord', 'type' => 'text', 'section' => 'client_panel_sidebar'],
            ['key' => 'client_panel_sidebar_health_journey', 'value' => 'Parcours de santé', 'type' => 'text', 'section' => 'client_panel_sidebar'],
            ['key' => 'client_panel_sidebar_bookings', 'value' => 'Réservations', 'type' => 'text', 'section' => 'client_panel_sidebar'],
            ['key' => 'client_panel_sidebar_conference_history', 'value' => 'Historique des conférences', 'type' => 'text', 'section' => 'client_panel_sidebar'],
            ['key' => 'client_panel_sidebar_transaction_vault', 'value' => 'Coffre-fort des transactions', 'type' => 'text', 'section' => 'client_panel_sidebar'],
            ['key' => 'client_panel_sidebar_time_slots', 'value' => 'Créneaux horaires', 'type' => 'text', 'section' => 'client_panel_sidebar'],
            ['key' => 'client_panel_sidebar_logout', 'value' => 'Se déconnecter', 'type' => 'text', 'section' => 'client_panel_sidebar'],
        ];

        foreach ($settings as $setting) {
            HomepageSetting::updateOrCreate(
                ['key' => $setting['key'], 'language' => 'fr'],
                $setting
            );
        }
    }
}
