<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Realisation;
use App\Models\RealisationImage;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Exemple d'ajout d'images aux projets
        $projects = Project::all();
        
        foreach ($projects as $project) {
            // Ajouter 2-3 images par projet (exemple)
            ProjectImage::create([
                'project_id' => $project->id,
                'image_url' => 'projects/' . $project->id . '/screenshot-1.png',
                'caption' => 'Capture d\'écran principale',
                'order' => 1,
            ]);

            ProjectImage::create([
                'project_id' => $project->id,
                'image_url' => 'projects/' . $project->id . '/screenshot-2.png',
                'caption' => 'Interface utilisateur',
                'order' => 2,
            ]);
        }

        // Exemple d'ajout d'images aux réalisations
        $realisations = Realisation::all();
        
        foreach ($realisations as $realisation) {
            RealisationImage::create([
                'realisation_id' => $realisation->id,
                'image_url' => 'realisations/' . $realisation->id . '/image-1.png',
                'caption' => 'Aperçu du projet',
                'order' => 1,
            ]);
        }
    }
}
