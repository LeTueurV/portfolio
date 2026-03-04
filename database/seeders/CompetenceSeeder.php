<?php

namespace Database\Seeders;

use App\Models\Competence;
use Illuminate\Database\Seeder;

class CompetenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $competences = [
            // Bloc B1
            ['code' => 'B1.1', 'label' => 'Gérer le patrimoine informatique', 'bloc' => 'B1'],
            ['code' => 'B1.2', 'label' => 'Répondre aux incidents et demandes d\'assistance', 'bloc' => 'B1'],
            ['code' => 'B1.3', 'label' => 'Développer la présence en ligne de l\'organisation', 'bloc' => 'B1'],
            ['code' => 'B1.4', 'label' => 'Travailler en mode projet', 'bloc' => 'B1'],
            ['code' => 'B1.5', 'label' => 'Mettre à disposition des utilisateurs un service informatique', 'bloc' => 'B1'],
            ['code' => 'B1.6', 'label' => 'Organiser son développement professionnel', 'bloc' => 'B1'],

            // Bloc B2
            ['code' => 'B2.1', 'label' => 'Concevoir et développer une solution applicative', 'bloc' => 'B2'],
            ['code' => 'B2.2', 'label' => 'Assurer la maintenance corrective ou évolutive d\'une solution', 'bloc' => 'B2'],
            ['code' => 'B2.3', 'label' => 'Gérer les données', 'bloc' => 'B2'],

            // Bloc B3
            ['code' => 'B3.1', 'label' => 'Protéger les données à caractère personnel', 'bloc' => 'B3'],
            ['code' => 'B3.2', 'label' => 'Préserver l\'identité numérique de l\'organisation', 'bloc' => 'B3'],
            ['code' => 'B3.3', 'label' => 'Sécuriser les équipements et les usages', 'bloc' => 'B3'],
            ['code' => 'B3.4', 'label' => 'Garantir la disponibilité, l\'intégrité et la confidentialité', 'bloc' => 'B3'],
        ];

        foreach ($competences as $competence) {
            Competence::create($competence);
        }
    }
}
