<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Portfolio::create([
            'first_name' => 'Prénom',
            'last_name' => 'Nom',
            'bio' => 'Un étudiant passionné par le développement logiciel et l\'apprentissage continuous. Actuellement en formation BTS SIO SLAM, j\'explore les technologies modernes et j\'aime résoudre des problèmes complexes.',
            'photo_url' => 'https://images.unsplash.com/photo-1759661881353-5b9cc55e1cf4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            'email' => 'prenom.nom@email.com',
            'phone' => '+33 6 12 34 56 78',
            'location' => 'Ville, Région',
            'linkedin_url' => 'https://linkedin.com/in/prenom-nom',
            'github_url' => 'https://github.com/prenom-nom',
            'year_start' => 2024,
            'year_end' => 2026,
            'contact_message' => 'N\'hésitez pas à me contacter pour toute question relative à mon parcours, mes projets ou mon portfolio BTS SIO SLAM.',
        ]);
    }
}
