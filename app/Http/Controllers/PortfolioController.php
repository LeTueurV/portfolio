<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Stage;
use App\Models\Project;
use App\Models\Realisation;
use App\Models\Company;
use App\Models\Competence;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolio = Portfolio::first();
        $stages = Stage::with('company', 'competences')->get();
        $projects = Project::with('tags', 'competences')->get();
        $realisations = Realisation::with('tags', 'company')->get();
        $companies = Company::all();
        $competences = Competence::all();

        // Organiser les réalisations par entreprise
        $realisationsByCompany = $realisations->groupBy('company_id');

        return view('index', compact(
            'portfolio',
            'stages',
            'projects',
            'realisations',
            'companies',
            'competences',
            'realisationsByCompany'
        ));
    }
}
