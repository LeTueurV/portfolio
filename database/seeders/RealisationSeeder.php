<?php

namespace Database\Seeders;

use App\Models\Realisation;
use App\Models\RealisationTag;
use App\Models\Company;
use Illuminate\Database\Seeder;

class RealisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();

        $realisationsData = [
            [
                'type' => 'stage',
                'company_id' => $companies->first()->id,
                'title' => 'Application web de gestion interne',
                'description' => 'Conception et développement d\'une application permettant de gérer les ressources internes. Modélisation de la base de données, développement back-end PHP et mise en place de l\'interface utilisateur.',
                'tags' => ['PHP', 'MySQL', 'HTML/CSS', 'Bootstrap'],
            ],
            [
                'type' => 'stage',
                'company_id' => $companies->first()->id,
                'title' => 'Maintenance d\'un logiciel existant',
                'description' => 'Correction de bugs et ajout de nouvelles fonctionnalités sur une application PHP/MySQL en production. Rédaction de documentation technique et réalisation de tests.',
                'tags' => ['PHP', 'MySQL', 'Git'],
            ],
            [
                'type' => 'stage',
                'company_id' => $companies->last()->id,
                'title' => 'Application mobile Android de suivi d\'interventions',
                'description' => 'Développement d\'une application mobile Android en Java pour le suivi des interventions des techniciens. Synchronisation des données avec une API REST sécurisée.',
                'tags' => ['Java', 'Android', 'API REST', 'JSON'],
            ],
            [
                'type' => 'stage',
                'company_id' => $companies->last()->id,
                'title' => 'Optimisation et sécurisation de la base de données',
                'description' => 'Audit de la base de données existante, optimisation des requêtes SQL, mise en place de sauvegardes automatisées et sécurisation des accès utilisateurs.',
                'tags' => ['SQL', 'PostgreSQL', 'Sécurité'],
            ],
            [
                'type' => 'projet',
                'company_id' => null,
                'title' => 'Script Python d\'automatisation de rapports',
                'description' => 'Développement d\'un script Python pour la génération automatique de rapports PDF à partir de données extraites d\'une API externe. Planification des tâches avec cron.',
                'tags' => ['Python', 'API REST', 'PDF', 'Cron'],
            ],
            [
                'type' => 'projet',
                'company_id' => null,
                'title' => 'Mise en place d\'une politique de sécurité réseau',
                'description' => 'Configuration d\'un pare-feu, mise en place d\'une politique de gestion des mots de passe et sensibilisation des utilisateurs aux bonnes pratiques de cybersécurité.',
                'tags' => ['Réseau', 'Sécurité', 'Configuration'],
            ],
        ];

        foreach ($realisationsData as $data) {
            $realisation = Realisation::create([
                'type' => $data['type'],
                'company_id' => $data['company_id'],
                'title' => $data['title'],
                'description' => $data['description'],
            ]);

            // Ajouter les tags
            foreach ($data['tags'] as $tag) {
                RealisationTag::create([
                    'realisation_id' => $realisation->id,
                    'tag' => $tag,
                ]);
            }
        }
    }
}
