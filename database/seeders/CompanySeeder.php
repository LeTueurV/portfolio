<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Nom de l\'Entreprise A',
                'sector' => 'Développement logiciel',
                'location' => 'Ville, Région',
                'description' => 'Présentation de l\'entreprise : son activité, sa taille, ses clients et son environnement technique. Contexte dans lequel les missions ont été réalisées lors du premier stage.',
                'photo_url' => 'https://images.unsplash.com/photo-1758813237985-f2259be96b60?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=800',
            ],
            [
                'name' => 'Nom de l\'Entreprise B',
                'sector' => 'Conseil & Intégration IT',
                'location' => 'Ville, Région',
                'description' => 'Présentation de l\'entreprise : son activité, sa taille, ses clients et son environnement technique. Contexte dans lequel les missions ont été réalisées lors du second stage.',
                'photo_url' => 'https://images.unsplash.com/photo-1760611656007-f767a8082758?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=800',
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
