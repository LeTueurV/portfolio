<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Stage;
use App\Models\Project;
use App\Models\Realisation;
use App\Models\Company;
use App\Models\Competence;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function portfolio(): JsonResponse
    {
        $portfolio = Portfolio::first();
        return response()->json($portfolio);
    }

    public function stages(): JsonResponse
    {
        $stages = Stage::with('company', 'competences')->get();
        return response()->json($stages);
    }

    public function projects(): JsonResponse
    {
        $projects = Project::with('tags', 'competences')->get();
        return response()->json($projects);
    }

    public function realisations(): JsonResponse
    {
        $realisations = Realisation::with('tags', 'company')->get();
        return response()->json($realisations);
    }

    public function companies(): JsonResponse
    {
        $companies = Company::all();
        return response()->json($companies);
    }

    public function competences(): JsonResponse
    {
        $competences = Competence::all();
        return response()->json($competences);
    }

    public function all(): JsonResponse
    {
        $portfolio = Portfolio::first();
        $stages = Stage::with('company', 'competences')->get();
        $projects = Project::with('tags', 'competences')->get();
        $realisations = Realisation::with('tags', 'company')->get();
        $companies = Company::all();
        $competences = Competence::all();

        // Organiser les réalisations par entreprise
        $realisationsByCompany = $realisations->groupBy('company_id');

        return response()->json([
            'portfolio' => $portfolio,
            'stages' => $stages,
            'projects' => $projects,
            'realisations' => $realisations,
            'companies' => $companies,
            'competences' => $competences,
            'realisationsByCompany' => $realisationsByCompany
        ]);
    }
}
