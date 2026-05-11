<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Competence;
use App\Models\Formation;
use App\Models\ImportantMessage;
use App\Models\PersonalProject;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\ProjectTag;
use App\Models\Realisation;
use App\Models\RealisationTag;
use App\Models\Stage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DashboardApiController extends Controller
{
    // ==========================================
    // PORTFOLIO
    // ==========================================

    /**
     * Récupérer les informations du portfolio
     */
    public function getPortfolio(): JsonResponse
    {
        $portfolio = Portfolio::first();
        return response()->json([
            'success' => true,
            'data' => $portfolio
        ]);
    }

    /**
     * Mettre à jour le portfolio
     */
    public function updatePortfolio(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'bio' => 'nullable|string|max:5000',
            'photo_url' => 'nullable|string|max:500',
            'email' => 'sometimes|email|max:255',
            'phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url|max:500',
            'github_url' => 'nullable|url|max:500',
            'year_start' => 'nullable|integer|min:1900|max:2100',
            'year_end' => 'nullable|integer|min:1900|max:2100',
            'contact_message' => 'nullable|string|max:2000',
            'cv_url' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $portfolio = Portfolio::first();
        if (!$portfolio) {
            $portfolio = Portfolio::create($validator->validated());
        } else {
            $portfolio->update($validator->validated());
        }

        return response()->json([
            'success' => true,
            'data' => $portfolio,
            'message' => 'Portfolio mis à jour avec succès'
        ]);
    }

    // ==========================================
    // COMPANIES
    // ==========================================

    /**
     * Lister toutes les entreprises
     */
    public function listCompanies(): JsonResponse
    {
        $companies = Company::withCount(['stages', 'projects', 'realisations'])->get();
        return response()->json([
            'success' => true,
            'data' => $companies
        ]);
    }

    /**
     * Récupérer une entreprise par ID
     */
    public function getCompany(int $id): JsonResponse
    {
        $company = Company::with(['stages', 'projects', 'realisations'])->find($id);

        if (!$company) {
            return response()->json([
                'success' => false,
                'error' => 'Entreprise non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $company
        ]);
    }

    /**
     * Créer une entreprise
     */
    public function createCompany(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sector' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'location' => 'nullable|string|max:255',
            'photo_url' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $company = Company::create($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $company,
            'message' => 'Entreprise créée avec succès'
        ], 201);
    }

    /**
     * Mettre à jour une entreprise
     */
    public function updateCompany(Request $request, int $id): JsonResponse
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json([
                'success' => false,
                'error' => 'Entreprise non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'sector' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'location' => 'nullable|string|max:255',
            'photo_url' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $company->update($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $company,
            'message' => 'Entreprise mise à jour avec succès'
        ]);
    }

    /**
     * Supprimer une entreprise
     */
    public function deleteCompany(int $id): JsonResponse
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json([
                'success' => false,
                'error' => 'Entreprise non trouvée'
            ], 404);
        }

        // Vérifier si l'entreprise a des relations
        if ($company->stages()->count() > 0 || $company->projects()->count() > 0 || $company->realisations()->count() > 0) {
            return response()->json([
                'success' => false,
                'error' => 'Impossible de supprimer cette entreprise car elle possède des stages, projets ou réalisations associés'
            ], 409);
        }

        $company->delete();

        return response()->json([
            'success' => true,
            'message' => 'Entreprise supprimée avec succès'
        ]);
    }

    // ==========================================
    // STAGES
    // ==========================================

    /**
     * Lister tous les stages
     */
    public function listStages(): JsonResponse
    {
        $stages = Stage::with(['company', 'competences'])->orderBy('start_date', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $stages
        ]);
    }

    /**
     * Récupérer un stage par ID
     */
    public function getStage(int $id): JsonResponse
    {
        $stage = Stage::with(['company', 'competences'])->find($id);

        if (!$stage) {
            return response()->json([
                'success' => false,
                'error' => 'Stage non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $stage
        ]);
    }

    /**
     * Créer un stage
     */
    public function createStage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'duration' => 'nullable|string|max:100',
            'role' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'competence_ids' => 'nullable|array',
            'competence_ids.*' => 'exists:competences,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $stage = Stage::create($validator->safe()->except('competence_ids'));

        // Attacher les compétences si fournies
        if ($request->has('competence_ids')) {
            $stage->competences()->sync($request->input('competence_ids'));
        }

        $stage->load(['company', 'competences']);

        return response()->json([
            'success' => true,
            'data' => $stage,
            'message' => 'Stage créé avec succès'
        ], 201);
    }

    /**
     * Mettre à jour un stage
     */
    public function updateStage(Request $request, int $id): JsonResponse
    {
        $stage = Stage::find($id);

        if (!$stage) {
            return response()->json([
                'success' => false,
                'error' => 'Stage non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'company_id' => 'sometimes|exists:companies,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'duration' => 'nullable|string|max:100',
            'role' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:5000',
            'competence_ids' => 'nullable|array',
            'competence_ids.*' => 'exists:competences,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $stage->update($validator->safe()->except('competence_ids'));

        // Mettre à jour les compétences si fournies
        if ($request->has('competence_ids')) {
            $stage->competences()->sync($request->input('competence_ids'));
        }

        $stage->load(['company', 'competences']);

        return response()->json([
            'success' => true,
            'data' => $stage,
            'message' => 'Stage mis à jour avec succès'
        ]);
    }

    /**
     * Supprimer un stage
     */
    public function deleteStage(int $id): JsonResponse
    {
        $stage = Stage::find($id);

        if (!$stage) {
            return response()->json([
                'success' => false,
                'error' => 'Stage non trouvé'
            ], 404);
        }

        // Détacher les compétences avant suppression
        $stage->competences()->detach();
        $stage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stage supprimé avec succès'
        ]);
    }

    // ==========================================
    // COMPETENCES
    // ==========================================

    /**
     * Lister toutes les compétences
     */
    public function listCompetences(): JsonResponse
    {
        $competences = Competence::withCount(['projects', 'stages'])->orderBy('bloc')->orderBy('code')->get();
        return response()->json([
            'success' => true,
            'data' => $competences
        ]);
    }

    /**
     * Récupérer une compétence par ID
     */
    public function getCompetence(int $id): JsonResponse
    {
        $competence = Competence::with(['projects', 'stages'])->find($id);

        if (!$competence) {
            return response()->json([
                'success' => false,
                'error' => 'Compétence non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $competence
        ]);
    }

    /**
     * Créer une compétence
     */
    public function createCompetence(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:competences,code',
            'label' => 'required|string|max:255',
            'bloc' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $competence = Competence::create($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $competence,
            'message' => 'Compétence créée avec succès'
        ], 201);
    }

    /**
     * Mettre à jour une compétence
     */
    public function updateCompetence(Request $request, int $id): JsonResponse
    {
        $competence = Competence::find($id);

        if (!$competence) {
            return response()->json([
                'success' => false,
                'error' => 'Compétence non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|string|max:50|unique:competences,code,' . $id,
            'label' => 'sometimes|string|max:255',
            'bloc' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $competence->update($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $competence,
            'message' => 'Compétence mise à jour avec succès'
        ]);
    }

    /**
     * Supprimer une compétence
     */
    public function deleteCompetence(int $id): JsonResponse
    {
        $competence = Competence::find($id);

        if (!$competence) {
            return response()->json([
                'success' => false,
                'error' => 'Compétence non trouvée'
            ], 404);
        }

        // Détacher des projets et stages avant suppression
        $competence->projects()->detach();
        $competence->stages()->detach();
        $competence->delete();

        return response()->json([
            'success' => true,
            'message' => 'Compétence supprimée avec succès'
        ]);
    }

    // ==========================================
    // PROJECTS
    // ==========================================

    /**
     * Lister tous les projets
     */
    public function listProjects(): JsonResponse
    {
        $projects = Project::with(['tags', 'competences', 'company', 'images'])
            ->orderBy('year', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    /**
     * Récupérer un projet par ID
     */
    public function getProject(int $id): JsonResponse
    {
        $project = Project::with(['tags', 'competences', 'company', 'images'])->find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'error' => 'Projet non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    /**
     * Créer un projet
     */
    public function createProject(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:5000',
            'long_description' => 'nullable|string|max:50000',
            'year' => 'nullable|integer|min:1900|max:2100',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:100',
            'competence_ids' => 'nullable|array',
            'competence_ids.*' => 'exists:competences,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $project = Project::create($validator->safe()->except(['tags', 'competence_ids']));

            // Ajouter les tags
            if ($request->has('tags')) {
                foreach ($request->input('tags') as $tag) {
                    ProjectTag::create([
                        'project_id' => $project->id,
                        'tag' => $tag
                    ]);
                }
            }

            // Attacher les compétences
            if ($request->has('competence_ids')) {
                $project->competences()->sync($request->input('competence_ids'));
            }

            DB::commit();

            $project->load(['tags', 'competences', 'company', 'images']);

            return response()->json([
                'success' => true,
                'data' => $project,
                'message' => 'Projet créé avec succès'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la création du projet: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour un projet
     */
    public function updateProject(Request $request, int $id): JsonResponse
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'error' => 'Projet non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'sometimes|string|max:255',
            'type' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:5000',
            'long_description' => 'nullable|string|max:50000',
            'year' => 'nullable|integer|min:1900|max:2100',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:100',
            'competence_ids' => 'nullable|array',
            'competence_ids.*' => 'exists:competences,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $project->update($validator->safe()->except(['tags', 'competence_ids']));

            // Mettre à jour les tags (supprimer les anciens et ajouter les nouveaux)
            if ($request->has('tags')) {
                $project->tags()->delete();
                foreach ($request->input('tags') as $tag) {
                    ProjectTag::create([
                        'project_id' => $project->id,
                        'tag' => $tag
                    ]);
                }
            }

            // Mettre à jour les compétences
            if ($request->has('competence_ids')) {
                $project->competences()->sync($request->input('competence_ids'));
            }

            DB::commit();

            $project->load(['tags', 'competences', 'company', 'images']);

            return response()->json([
                'success' => true,
                'data' => $project,
                'message' => 'Projet mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la mise à jour du projet: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un projet
     */
    public function deleteProject(int $id): JsonResponse
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'error' => 'Projet non trouvé'
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Supprimer les relations
            $project->tags()->delete();
            $project->images()->delete();
            $project->competences()->detach();
            $project->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Projet supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la suppression du projet: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // REALISATIONS
    // ==========================================

    /**
     * Lister toutes les réalisations
     */
    public function listRealisations(): JsonResponse
    {
        try {
            $realisations = Realisation::with(['tags', 'company', 'images'])->get();

            // Transformer les données pour éviter les erreurs de sérialisation
            $data = $realisations->map(function ($realisation) {
                return [
                    'id' => $realisation->id,
                    'type' => $realisation->type,
                    'company_id' => $realisation->company_id,
                    'title' => $realisation->title,
                    'description' => $realisation->description,
                    'long_description' => $realisation->long_description,
                    'created_at' => $realisation->created_at,
                    'updated_at' => $realisation->updated_at,
                    'company' => $realisation->company ? [
                        'id' => $realisation->company->id,
                        'name' => $realisation->company->name,
                    ] : null,
                    'tags' => $realisation->tags->map(fn($tag) => ['id' => $tag->id, 'tag' => $tag->tag])->values(),
                    'images' => $realisation->images->map(fn($img) => [
                        'id' => $img->id,
                        'image_url' => $img->image_url,
                        'caption' => $img->caption,
                        'description' => $img->description,
                        'order' => $img->order,
                    ])->values(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error listing realisations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du chargement des réalisations',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Récupérer une réalisation par ID
     */
    public function getRealisation(int $id): JsonResponse
    {
        try {
            $realisation = Realisation::with(['tags', 'company', 'images'])->find($id);

            if (!$realisation) {
                return response()->json([
                    'success' => false,
                    'error' => 'Réalisation non trouvée'
                ], 404);
            }

            // Transformer les données pour éviter les erreurs de sérialisation
            $data = [
                'id' => $realisation->id,
                'type' => $realisation->type,
                'company_id' => $realisation->company_id,
                'title' => $realisation->title,
                'description' => $realisation->description,
                'long_description' => $realisation->long_description,
                'created_at' => $realisation->created_at,
                'updated_at' => $realisation->updated_at,
                'company' => $realisation->company ? [
                    'id' => $realisation->company->id,
                    'name' => $realisation->company->name,
                ] : null,
                'tags' => $realisation->tags->map(fn($tag) => ['id' => $tag->id, 'tag' => $tag->tag])->values(),
                'images' => $realisation->images->map(fn($img) => [
                    'id' => $img->id,
                    'image_url' => $img->image_url,
                    'caption' => $img->caption,
                    'description' => $img->description,
                    'order' => $img->order,
                ])->values(),
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting realisation ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du chargement de la réalisation',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Créer une réalisation
     */
    public function createRealisation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'nullable|string|max:100',
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'long_description' => 'nullable|string|max:50000',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $realisation = Realisation::create($validator->safe()->except(['tags']));

            // Ajouter les tags
            if ($request->has('tags')) {
                foreach ($request->input('tags') as $tag) {
                    RealisationTag::create([
                        'realisation_id' => $realisation->id,
                        'tag' => $tag
                    ]);
                }
            }

            DB::commit();

            $realisation->load(['tags', 'company', 'images']);

            return response()->json([
                'success' => true,
                'data' => $realisation,
                'message' => 'Réalisation créée avec succès'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la création de la réalisation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour une réalisation
     */
    public function updateRealisation(Request $request, int $id): JsonResponse
    {
        $realisation = Realisation::find($id);

        if (!$realisation) {
            return response()->json([
                'success' => false,
                'error' => 'Réalisation non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'nullable|string|max:100',
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:5000',
            'long_description' => 'nullable|string|max:50000',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $realisation->update($validator->safe()->except(['tags']));

            // Mettre à jour les tags
            if ($request->has('tags')) {
                $realisation->tags()->delete();
                foreach ($request->input('tags') as $tag) {
                    RealisationTag::create([
                        'realisation_id' => $realisation->id,
                        'tag' => $tag
                    ]);
                }
            }

            DB::commit();

            $realisation->load(['tags', 'company', 'images']);

            return response()->json([
                'success' => true,
                'data' => $realisation,
                'message' => 'Réalisation mise à jour avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la mise à jour de la réalisation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une réalisation
     */
    public function deleteRealisation(int $id): JsonResponse
    {
        $realisation = Realisation::find($id);

        if (!$realisation) {
            return response()->json([
                'success' => false,
                'error' => 'Réalisation non trouvée'
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Supprimer les relations
            $realisation->tags()->delete();
            $realisation->images()->delete();
            $realisation->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Réalisation supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la suppression de la réalisation: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // STATS & DASHBOARD
    // ==========================================

    /**
     * Récupérer les statistiques du dashboard
     */
    public function getStats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'companies_count' => Company::count(),
                'stages_count' => Stage::count(),
                'formations_count' => Formation::count(),
                'projects_count' => Project::count(),
                'personal_projects_count' => PersonalProject::count(),
                'realisations_count' => Realisation::count(),
                'competences_count' => Competence::count(),
                'messages_count' => ImportantMessage::count(),
                'active_messages_count' => ImportantMessage::active()->count(),
                'recent_projects' => Project::with('company')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(),
                'recent_personal_projects' => PersonalProject::orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(),
                'recent_realisations' => Realisation::with('company')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(),
            ]
        ]);
    }

    // ==========================================
    // IMPORTANT MESSAGES
    // ==========================================

    /**
     * Lister tous les messages importants
     */
    public function listMessages(): JsonResponse
    {
        $messages = ImportantMessage::orderBy('order')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Lister uniquement les messages actifs (pour l'affichage public)
     */
    public function listActiveMessages(): JsonResponse
    {
        $messages = ImportantMessage::active()->orderBy('order')->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Récupérer un message par ID
     */
    public function getMessage(int $id): JsonResponse
    {
        $message = ImportantMessage::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'error' => 'Message non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    /**
     * Créer un message important
     */
    public function createMessage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'type' => 'nullable|string|in:info,success,warning,urgent',
            'icon' => 'nullable|string|max:100',
            'link_url' => 'nullable|url|max:500',
            'link_text' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $message = ImportantMessage::create($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $message,
            'message' => 'Message créé avec succès'
        ], 201);
    }

    /**
     * Mettre à jour un message important
     */
    public function updateMessage(Request $request, int $id): JsonResponse
    {
        $message = ImportantMessage::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'error' => 'Message non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'message' => 'sometimes|string|max:5000',
            'type' => 'nullable|string|in:info,success,warning,urgent',
            'icon' => 'nullable|string|max:100',
            'link_url' => 'nullable|url|max:500',
            'link_text' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $message->update($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $message,
            'message' => 'Message mis à jour avec succès'
        ]);
    }

    /**
     * Activer/Désactiver un message
     */
    public function toggleMessage(int $id): JsonResponse
    {
        $message = ImportantMessage::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'error' => 'Message non trouvé'
            ], 404);
        }

        $message->update(['is_active' => !$message->is_active]);

        return response()->json([
            'success' => true,
            'data' => $message,
            'message' => $message->is_active ? 'Message activé' : 'Message désactivé'
        ]);
    }

    /**
     * Supprimer un message important
     */
    public function deleteMessage(int $id): JsonResponse
    {
        $message = ImportantMessage::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'error' => 'Message non trouvé'
            ], 404);
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message supprimé avec succès'
        ]);
    }

    // ==========================================
    // FORMATIONS (Parcours/Diplômes)
    // ==========================================

    /**
     * Lister toutes les formations
     */
    public function listFormations(): JsonResponse
    {
        $formations = Formation::ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $formations
        ]);
    }

    /**
     * Récupérer une formation par ID
     */
    public function getFormation(int $id): JsonResponse
    {
        $formation = Formation::find($id);

        if (!$formation) {
            return response()->json([
                'success' => false,
                'error' => 'Formation non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $formation
        ]);
    }

    /**
     * Créer une formation
     */
    public function createFormation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'school' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'degree_type' => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'nullable|boolean',
            'description' => 'nullable|string|max:5000',
            'logo_url' => 'nullable|string|max:500',
            'diploma_url' => 'nullable|string|max:500',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $formation = Formation::create($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $formation,
            'message' => 'Formation créée avec succès'
        ], 201);
    }

    /**
     * Mettre à jour une formation
     */
    public function updateFormation(Request $request, int $id): JsonResponse
    {
        $formation = Formation::find($id);

        if (!$formation) {
            return response()->json([
                'success' => false,
                'error' => 'Formation non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'school' => 'sometimes|string|max:255',
            'location' => 'nullable|string|max:255',
            'degree_type' => 'nullable|string|max:100',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'nullable|boolean',
            'description' => 'nullable|string|max:5000',
            'logo_url' => 'nullable|string|max:500',
            'diploma_url' => 'nullable|string|max:500',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $formation->update($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $formation,
            'message' => 'Formation mise à jour avec succès'
        ]);
    }

    /**
     * Supprimer une formation
     */
    public function deleteFormation(int $id): JsonResponse
    {
        $formation = Formation::find($id);

        if (!$formation) {
            return response()->json([
                'success' => false,
                'error' => 'Formation non trouvée'
            ], 404);
        }

        $formation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Formation supprimée avec succès'
        ]);
    }
}
