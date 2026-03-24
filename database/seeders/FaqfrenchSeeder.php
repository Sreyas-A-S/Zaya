<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqfrenchSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            [
                'language' => 'fr',
                'question' => "Qu’est-ce que l’Ayurveda et en quoi peut-il m’aider ?",
                'answer' => "L’Ayurveda est un système de médecine traditionnelle originaire d’Inde, qui vise à équilibrer le corps, l’esprit et le mental pour une santé optimale. Nos praticiens peuvent créer des plans de bien‑être personnalisés, adaptés à votre constitution unique.",
                'status' => true,
            ],
            [
                'language' => 'fr',
                'question' => "Comment réserver une consultation en ligne ?",
                'answer' => "Vous pouvez réserver une consultation en parcourant notre annuaire de praticiens, en sélectionnant celui qui correspond à vos besoins, puis en planifiant une séance depuis sa page de profil.",
                'status' => true,
            ],
            [
                'language' => 'fr',
                'question' => "Quels types de praticiens sont disponibles ?",
                'answer' => "Nous disposons d’un large éventail de praticiens, notamment des médecins ayurvédiques, des yogathérapeutes, des conseillers en pleine conscience et des guides spirituels — tous vérifiés et expérimentés.",
                'status' => true,
            ],
            [
                'language' => 'fr',
                'question' => "Puis-je obtenir une consultation depuis l’étranger ?",
                'answer' => "Oui ! Zaya propose des consultations en présentiel et en ligne, ce qui permet aux clients du monde entier d’accéder facilement à notre réseau de praticiens.",
                'status' => true,
            ],
            [
                'language' => 'fr',
                'question' => "Comment rejoindre Zaya en tant que praticien ?",
                'answer' => "Inscrivez‑vous via notre page d’inscription praticien, soumettez vos diplômes et certifications, puis notre équipe vérifiera votre profil avant de vous référencer sur la plateforme.",
                'status' => true,
            ],
            [
                'language' => 'fr',
                'question' => "Quelles affections vos praticiens traitent-ils ?",
                'answer' => "Nos praticiens prennent en charge un large éventail de problématiques : stress, troubles digestifs, affections cutanées, douleurs chroniques, difficultés liées à la santé mentale, ainsi que l’optimisation globale du bien‑être.",
                'status' => true,
            ],
            [
                'language' => 'fr',
                'question' => "Vos praticiens sont-ils certifiés et vérifiés ?",
                'answer' => "Absolument. Chaque praticien sur Zaya suit un processus de vérification rigoureux afin de garantir qu’il répond à nos standards de qualité et possède des certifications valides.",
                'status' => true,
            ],
            [
                'language' => 'fr',
                'question' => "Comment annuler ou reprogrammer une séance ?",
                'answer' => "Vous pouvez gérer vos réservations depuis votre tableau de bord. Les annulations et reprogrammations sont possibles jusqu’à 24 heures avant la séance prévue.",
                'status' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}

