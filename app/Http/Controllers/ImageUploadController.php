<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Formation;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Realisation;
use App\Models\RealisationImage;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    protected ImageUploadService $uploadService;

    public function __construct(ImageUploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    // ==========================================
    // PORTFOLIO
    // ==========================================

    /**
     * Upload la photo du portfolio
     */
    public function uploadPortfolioPhoto(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $portfolio = Portfolio::first();
        if (!$portfolio) {
            return response()->json([
                'success' => false,
                'error' => 'Portfolio non trouvé'
            ], 404);
        }

        $result = $this->uploadService->replace(
            $request->file('image'),
            $portfolio->photo_url,
            'portfolio',
            'photo'
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error']
            ], 400);
        }

        $portfolio->update(['photo_url' => $result['url']]);

        return response()->json([
            'success' => true,
            'url' => $result['url'],
            'portfolio' => $portfolio
        ]);
    }

    /**
     * Supprimer la photo du portfolio
     */
    public function deletePortfolioPhoto(): JsonResponse
    {
        $portfolio = Portfolio::first();
        if (!$portfolio || !$portfolio->photo_url) {
            return response()->json([
                'success' => false,
                'error' => 'Photo non trouvée'
            ], 404);
        }

        $this->uploadService->delete($portfolio->photo_url);
        $portfolio->update(['photo_url' => null]);

        return response()->json(['success' => true]);
    }

    // ==========================================
    // COMPANIES
    // ==========================================

    /**
     * Upload la photo d'une entreprise
     */
    public function uploadCompanyPhoto(Request $request, int $companyId): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
        ]);

        $company = Company::find($companyId);
        if (!$company) {
            return response()->json([
                'success' => false,
                'error' => 'Entreprise non trouvée'
            ], 404);
        }

        $result = $this->uploadService->replace(
            $request->file('image'),
            $company->photo_url,
            'companies',
            "company_{$companyId}"
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error']
            ], 400);
        }

        $company->update(['photo_url' => $result['url']]);

        return response()->json([
            'success' => true,
            'url' => $result['url'],
            'company' => $company
        ]);
    }

    /**
     * Supprimer la photo d'une entreprise
     */
    public function deleteCompanyPhoto(int $companyId): JsonResponse
    {
        $company = Company::find($companyId);
        if (!$company || !$company->photo_url) {
            return response()->json([
                'success' => false,
                'error' => 'Photo non trouvée'
            ], 404);
        }

        $this->uploadService->delete($company->photo_url);
        $company->update(['photo_url' => null]);

        return response()->json(['success' => true]);
    }

    // ==========================================
    // FORMATIONS
    // ==========================================

    /**
     * Upload le logo d'une formation
     */
    public function uploadFormationLogo(Request $request, int $formationId): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
        ]);

        $formation = Formation::find($formationId);
        if (!$formation) {
            return response()->json([
                'success' => false,
                'error' => 'Formation non trouvée'
            ], 404);
        }

        $result = $this->uploadService->replace(
            $request->file('image'),
            $formation->logo_url,
            'formations',
            "formation_{$formationId}_logo"
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error']
            ], 400);
        }

        $formation->update(['logo_url' => $result['url']]);

        return response()->json([
            'success' => true,
            'url' => $result['url'],
            'formation' => $formation
        ]);
    }

    /**
     * Supprimer le logo d'une formation
     */
    public function deleteFormationLogo(int $formationId): JsonResponse
    {
        $formation = Formation::find($formationId);
        if (!$formation || !$formation->logo_url) {
            return response()->json([
                'success' => false,
                'error' => 'Logo non trouvé'
            ], 404);
        }

        $this->uploadService->delete($formation->logo_url);
        $formation->update(['logo_url' => null]);

        return response()->json(['success' => true]);
    }

    /**
     * Upload le diplôme d'une formation (PDF ou image)
     */
    public function uploadFormationDiploma(Request $request, int $formationId): JsonResponse
    {
        $request->validate([
            'file' => 'required|mimes:pdf,jpeg,png,jpg|max:10240',
        ]);

        $formation = Formation::find($formationId);
        if (!$formation) {
            return response()->json([
                'success' => false,
                'error' => 'Formation non trouvée'
            ], 404);
        }

        $result = $this->uploadService->replace(
            $request->file('file'),
            $formation->diploma_url,
            'diplomas',
            "diploma_{$formationId}",
            true // isDocument = true pour autoriser les PDF
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error']
            ], 400);
        }

        $formation->update(['diploma_url' => $result['url']]);

        return response()->json([
            'success' => true,
            'url' => $result['url'],
            'formation' => $formation
        ]);
    }

    /**
     * Supprimer le diplôme d'une formation
     */
    public function deleteFormationDiploma(int $formationId): JsonResponse
    {
        $formation = Formation::find($formationId);
        if (!$formation || !$formation->diploma_url) {
            return response()->json([
                'success' => false,
                'error' => 'Diplôme non trouvé'
            ], 404);
        }

        $this->uploadService->delete($formation->diploma_url);
        $formation->update(['diploma_url' => null]);

        return response()->json(['success' => true]);
    }

    // ==========================================
    // PROJECTS (existant, refactorisé)
    // ==========================================

    /**
     * Upload une image pour un projet
     */
    public function uploadProjectImage(Request $request, int $projectId): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'caption' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
        ]);

        $project = Project::find($projectId);
        if (!$project) {
            return response()->json([
                'success' => false,
                'error' => 'Projet non trouvé'
            ], 404);
        }

        $result = $this->uploadService->upload(
            $request->file('image'),
            'projects',
            "project_{$projectId}"
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error']
            ], 400);
        }

        $maxOrder = ProjectImage::where('project_id', $projectId)->max('order') ?? 0;

        $image = ProjectImage::create([
            'project_id' => $projectId,
            'image_url' => $result['url'],
            'caption' => $request->input('caption'),
            'description' => $request->input('description'),
            'order' => $maxOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'image' => $image,
            'url' => $result['url']
        ]);
    }

    /**
     * Supprimer une image de projet
     */
    public function deleteProjectImage(int $imageId): JsonResponse
    {
        $image = ProjectImage::find($imageId);
        if (!$image) {
            return response()->json([
                'success' => false,
                'error' => 'Image non trouvée'
            ], 404);
        }

        $this->uploadService->delete($image->image_url);
        $image->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Lister les images d'un projet
     */
    public function listProjectImages(int $projectId): JsonResponse
    {
        $project = Project::with('images')->find($projectId);
        if (!$project) {
            return response()->json([
                'success' => false,
                'error' => 'Projet non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $project->images
        ]);
    }

    /**
     * Mettre à jour l'ordre des images d'un projet
     */
    public function updateProjectImagesOrder(Request $request, int $projectId): JsonResponse
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|integer',
            'images.*.order' => 'required|integer',
        ]);

        foreach ($request->input('images') as $imageData) {
            ProjectImage::where('id', $imageData['id'])
                ->where('project_id', $projectId)
                ->update(['order' => $imageData['order']]);
        }

        return response()->json(['success' => true]);
    }

    // ==========================================
    // REALISATIONS (existant, refactorisé)
    // ==========================================

    /**
     * Upload une image pour une réalisation
     */
    public function uploadRealisationImage(Request $request, int $realisationId): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'caption' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
        ]);

        $realisation = Realisation::find($realisationId);
        if (!$realisation) {
            return response()->json([
                'success' => false,
                'error' => 'Réalisation non trouvée'
            ], 404);
        }

        $result = $this->uploadService->upload(
            $request->file('image'),
            'realisations',
            "realisation_{$realisationId}"
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error']
            ], 400);
        }

        $maxOrder = RealisationImage::where('realisation_id', $realisationId)->max('order') ?? 0;

        $image = RealisationImage::create([
            'realisation_id' => $realisationId,
            'image_url' => $result['url'],
            'caption' => $request->input('caption'),
            'description' => $request->input('description'),
            'order' => $maxOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'image' => $image,
            'url' => $result['url']
        ]);
    }

    /**
     * Supprimer une image de réalisation
     */
    public function deleteRealisationImage(int $imageId): JsonResponse
    {
        $image = RealisationImage::find($imageId);
        if (!$image) {
            return response()->json([
                'success' => false,
                'error' => 'Image non trouvée'
            ], 404);
        }

        $this->uploadService->delete($image->image_url);
        $image->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Lister les images d'une réalisation
     */
    public function listRealisationImages(int $realisationId): JsonResponse
    {
        $realisation = Realisation::with('images')->find($realisationId);
        if (!$realisation) {
            return response()->json([
                'success' => false,
                'error' => 'Réalisation non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $realisation->images
        ]);
    }

    /**
     * Mettre à jour l'ordre des images d'une réalisation
     */
    public function updateRealisationImagesOrder(Request $request, int $realisationId): JsonResponse
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|integer',
            'images.*.order' => 'required|integer',
        ]);

        foreach ($request->input('images') as $imageData) {
            RealisationImage::where('id', $imageData['id'])
                ->where('realisation_id', $realisationId)
                ->update(['order' => $imageData['order']]);
        }

        return response()->json(['success' => true]);
    }

    // ==========================================
    // LEGACY (compatibilité)
    // ==========================================

    /**
     * Mettre à jour la description longue d'un projet
     */
    public function updateProjectLongDescription(Request $request, int $projectId): JsonResponse
    {
        $request->validate([
            'long_description' => 'nullable|string|max:10000',
        ]);

        $project = Project::find($projectId);
        if (!$project) {
            return response()->json([
                'success' => false,
                'error' => 'Projet non trouvé'
            ], 404);
        }

        $project->update([
            'long_description' => $request->input('long_description'),
        ]);

        return response()->json([
            'success' => true,
            'project' => $project,
        ]);
    }

    /**
     * Mettre à jour la description longue d'une réalisation
     */
    public function updateRealisationLongDescription(Request $request, int $realisationId): JsonResponse
    {
        $request->validate([
            'long_description' => 'nullable|string|max:10000',
        ]);

        $realisation = Realisation::find($realisationId);
        if (!$realisation) {
            return response()->json([
                'success' => false,
                'error' => 'Réalisation non trouvée'
            ], 404);
        }

        $realisation->update([
            'long_description' => $request->input('long_description'),
        ]);

        return response()->json([
            'success' => true,
            'realisation' => $realisation,
        ]);
    }
}
