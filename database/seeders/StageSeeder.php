<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Stage;
use App\Models\Competence;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();

        if ($companies->count() < 2) {
            $this->call(CompanySeeder::class);
            $companies = Company::all();
        }

        $competences = Competence::all();

        if ($competences->count() === 0) {
            $this->call(CompetenceSeeder::class);
            $competences = Competence::all();
        }

        // Stage 1 - Entreprise A
        $stage1 = Stage::create([
            'company_id' => $companies->first()->id,
            'start_date' => '2025-01-01',
            'end_date' => '2025-03-15',
            'duration' => '10 semaines',
            'role' => 'Développeur Web Stagiaire',
            'description' => 'Description des missions réalisées lors du stage. Technologies utilisées, projets développés et compétences acquises ou renforcées au sein de l\'entreprise.',
        ]);

        $stage1->competences()->attach([
            $competences->where('code', 'B2.1')->first()->id,
            $competences->where('code', 'B2.2')->first()->id,
            $competences->where('code', 'B1.4')->first()->id,
            $competences->where('code', 'B3.1')->first()->id,
        ]);

        // Stage 2 - Entreprise B
        $stage2 = Stage::create([
            'company_id' => $companies->last()->id,
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-15',
            'duration' => '10 semaines',
            'role' => 'Développeur Logiciel Stagiaire',
            'description' => 'Description des missions réalisées lors du stage. Technologies utilisées, projets développés et compétences acquises ou renforcées au sein de l\'entreprise.',
        ]);

        $stage2->competences()->attach([
            $competences->where('code', 'B2.1')->first()->id,
            $competences->where('code', 'B2.3')->first()->id,
            $competences->where('code', 'B1.3')->first()->id,
            $competences->where('code', 'B3.4')->first()->id,
        ]);
    }
}
