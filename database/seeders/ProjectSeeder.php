<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectTag;
use App\Models\Competence;
use App\Models\Company;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();
        $competences = Competence::all();

        $projectsData = [
            [
                'type' => 'Application Web',
                'title' => 'Nom du Projet 1',
                'description' => 'Description du projet réalisé lors de la formation ou en stage. Présentation des technologies utilisées et des compétences mobilisées.',
                'tags' => ['PHP', 'MySQL', 'HTML/CSS'],
                'competences' => ['B2.1', 'B2.3', 'B1.4'],
                'year' => '2025',
            ],
            [
                'type' => 'Application Mobile',
                'title' => 'Nom du Projet 2',
                'description' => 'Description du projet réalisé lors de la formation ou en stage. Présentation des technologies utilisées et des compétences mobilisées.',
                'tags' => ['Java', 'Android', 'SQLite'],
                'competences' => ['B2.1', 'B2.2', 'B1.3'],
                'year' => '2025',
            ],
            [
                'type' => 'Script & Automatisation',
                'title' => 'Nom du Projet 3',
                'description' => 'Description du projet réalisé lors de la formation ou en stage. Présentation des technologies utilisées et des compétences mobilisées.',
                'tags' => ['Python', 'API REST', 'JSON'],
                'competences' => ['B1.1', 'B2.3', 'B3.3'],
                'year' => '2024',
            ],
            [
                'type' => 'Base de données',
                'title' => 'Nom du Projet 4',
                'description' => 'Description du projet réalisé lors de la formation ou en stage. Présentation des technologies utilisées et des compétences mobilisées.',
                'tags' => ['SQL', 'PostgreSQL', 'MCD/MLD'],
                'competences' => ['B2.3', 'B3.4', 'B1.4'],
                'year' => '2024',
            ],
        ];

        foreach ($projectsData as $data) {
            $project = Project::create([
                'company_id' => $company->id,
                'type' => $data['type'],
                'title' => $data['title'],
                'description' => $data['description'],
                'year' => $data['year'],
            ]);

            // Ajouter les tags
            foreach ($data['tags'] as $tag) {
                ProjectTag::create([
                    'project_id' => $project->id,
                    'tag' => $tag,
                ]);
            }

            // Ajouter les compétences
            foreach ($data['competences'] as $competenceCode) {
                $competence = $competences->where('code', $competenceCode)->first();
                if ($competence) {
                    $project->competences()->attach($competence->id);
                }
            }
        }
    }
}
