<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Formation;
use App\Models\Portfolio;
use App\Models\PersonalProject;
use App\Models\PersonalProjectImage;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Realisation;
use App\Models\RealisationImage;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    protected ImageUploadService $uploadService;

    // Constantes de configuration
    const MAX_IMAGE_SIZE = 5120; // 5 MB en KB
    const MAX_FILE_SIZE = 10240; // 10 MB en KB
    const ALLOWED_IMAGE_MIMES = 'jpeg,png,jpg,gif,webp';
    const ALLOWED_FILE_MIMES = 'pdf,jpeg,png,jpg';

    public function __construct(ImageUploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * Réponse standardisée pour les succès
     */
    protected function successResponse($data = null, string $message = 'Opération réussie', int $statusCode = 200): JsonResponse
    {
        $response = ['success' => true];
        if ($message) $response['message'] = $message;
        if ($data) $response['data'] = $data;
        return response()->json($response, $statusCode);
    }

    /**
     * Réponse standardisée pour les erreurs
     */
    protected function errorResponse(string $error, int $statusCode = 400, ?string $field = null): JsonResponse
    {
        $response = [
            'success' => false,
            'error' => $error
        ];
        if ($field) $response['field'] = $field;
        return response()->json($response, $statusCode);
    }

    /**
     * Validation de base pour les images
     */
    protected function validateImage(Request $request, string $fieldName = 'image'): array
    {
        return $request->validate([
            $fieldName => "required|image|mimes:" . self::ALLOWED_IMAGE_MIMES . "|max:" . self::MAX_IMAGE_SIZE,
        ]);
    }

    /**
     * Validation pour les fichiers documents
     */
    protected function validateFile(Request $request, string $fieldName = 'file'): array
    {
        return $request->validate([
            $fieldName => "required|mimes:" . self::ALLOWED_FILE_MIMES . "|max:" . self::MAX_FILE_SIZE,
        ]);
    }

    // ==========================================
    // PORTFOLIO
    // ==========================================

    /**
     * Upload la photo du portfolio
     */
    public function uploadPortfolioPhoto(Request $request): JsonResponse
    {
        try {
            $this->validateImage($request);

            $portfolio = Portfolio::first();
            if (!$portfolio) {
                return $this->errorResponse('Portfolio non trouvé', 404);
            }

            $result = $this->uploadService->replace(
                $request->file('image'),
                $portfolio->photo_url,
                'portfolio',
                'photo'
            );

            if (!$result['success']) {
                Log::warning('Portfolio photo upload failed: ' . $result['error']);
                return $this->errorResponse($result['error']);
            }

            $portfolio->update(['photo_url' => $result['url']]);

            return $this->successResponse(
                $portfolio,
                'Photo du portfolio mise à jour avec succès',
                201
            );
        } catch (\Exception $e) {
            Log::error('Portfolio photo upload error: ' . $e->getMessage());
            return $this->errorResponse('Erreur lors de l\'upload: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Supprimer la photo du portfolio
     */
    public function deletePortfolioPhoto(): JsonResponse
    {
        try {
            $portfolio = Portfolio::first();
            if (!$portfolio) {
                return $this->errorResponse('Portfolio non trouvé', 404);
            }

            if (!$portfolio->photo_url) {
                return $this->errorResponse('Aucune photo à supprimer', 404);
            }

            $this->uploadService->delete($portfolio->photo_url);
            $portfolio->update(['photo_url' => null]);

            return $this->successResponse(null, 'Photo supprimée avec succès');
        } catch (\Exception $e) {
            Log::error('Portfolio photo delete error: ' . $e->getMessage());
            return $this->errorResponse('Erreur lors de la suppression: ' . $e->getMessage(), 500);
        }
    }

    // ==========================================
    // COMPANIES
    // ==========================================

    /**
     * Upload la photo d'une entreprise
     */
    public function uploadCompanyPhoto(Request $request, int $companyId): JsonResponse
    {
        try {
            $this->validateImage($request);

            $company = Company::find($companyId);
            if (!$company) {
                return $this->errorResponse('Entreprise non trouvée', 404);
            }

            $result = $this->uploadService->replace(
                $request->file('image'),
                $company->photo_url,
                'companies',
                "company_{$companyId}"
            );

            if (!$result['success']) {
                Log::warning("Company {$companyId} photo upload failed: " . $result['error']);
                return $this->errorResponse($result['error']);
            }

            $company->update(['photo_url' => $result['url']]);

            return $this->successResponse(
                $company,
                'Logo de l\'entreprise mis à jour avec succès',
                201
            );
        } catch (\Exception $e) {
            Log::error("Company {$companyId} photo upload error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de l\'upload: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Supprimer la photo d'une entreprise
     */
    public function deleteCompanyPhoto(int $companyId): JsonResponse
    {
        try {
            $company = Company::find($companyId);
            if (!$company) {
                return $this->errorResponse('Entreprise non trouvée', 404);
            }

            if (!$company->photo_url) {
                return $this->errorResponse('Aucune photo à supprimer', 404);
            }

            $this->uploadService->delete($company->photo_url);
            $company->update(['photo_url' => null]);

            return $this->successResponse(null, 'Photo supprimée avec succès');
        } catch (\Exception $e) {
            Log::error("Company {$companyId} photo delete error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la suppression: ' . $e->getMessage(), 500);
        }
    }

    // ==========================================
    // FORMATIONS
    // ==========================================

    /**
     * Upload le logo d'une formation
     */
    public function uploadFormationLogo(Request $request, int $formationId): JsonResponse
    {
        try {
            $this->validateImage($request);

            $formation = Formation::find($formationId);
            if (!$formation) {
                return $this->errorResponse('Formation non trouvée', 404);
            }

            $result = $this->uploadService->replace(
                $request->file('image'),
                $formation->logo_url,
                'formations',
                "formation_{$formationId}_logo"
            );

            if (!$result['success']) {
                Log::warning("Formation {$formationId} logo upload failed: " . $result['error']);
                return $this->errorResponse($result['error']);
            }

            $formation->update(['logo_url' => $result['url']]);

            return $this->successResponse(
                $formation,
                'Logo de la formation mis à jour avec succès',
                201
            );
        } catch (\Exception $e) {
            Log::error("Formation {$formationId} logo upload error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de l\'upload: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Supprimer le logo d'une formation
     */
    public function deleteFormationLogo(int $formationId): JsonResponse
    {
        try {
            $formation = Formation::find($formationId);
            if (!$formation) {
                return $this->errorResponse('Formation non trouvée', 404);
            }

            if (!$formation->logo_url) {
                return $this->errorResponse('Aucun logo à supprimer', 404);
            }

            $this->uploadService->delete($formation->logo_url);
            $formation->update(['logo_url' => null]);

            return $this->successResponse(null, 'Logo supprimé avec succès');
        } catch (\Exception $e) {
            Log::error("Formation {$formationId} logo delete error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la suppression: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Upload le diplôme d'une formation (PDF ou image)
     */
    public function uploadFormationDiploma(Request $request, int $formationId): JsonResponse
    {
        try {
            $this->validateFile($request, 'file');

            $formation = Formation::find($formationId);
            if (!$formation) {
                return $this->errorResponse('Formation non trouvée', 404);
            }

            $result = $this->uploadService->replace(
                $request->file('file'),
                $formation->diploma_url,
                'diplomas',
                "diploma_{$formationId}",
                true
            );

            if (!$result['success']) {
                Log::warning("Formation {$formationId} diploma upload failed: " . $result['error']);
                return $this->errorResponse($result['error']);
            }

            $formation->update(['diploma_url' => $result['url']]);

            return $this->successResponse(
                $formation,
                'Diplôme mis à jour avec succès',
                201
            );
        } catch (\Exception $e) {
            Log::error("Formation {$formationId} diploma upload error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de l\'upload: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Supprimer le diplôme d'une formation
     */
    public function deleteFormationDiploma(int $formationId): JsonResponse
    {
        try {
            $formation = Formation::find($formationId);
            if (!$formation) {
                return $this->errorResponse('Formation non trouvée', 404);
            }

            if (!$formation->diploma_url) {
                return $this->errorResponse('Aucun diplôme à supprimer', 404);
            }

            $this->uploadService->delete($formation->diploma_url);
            $formation->update(['diploma_url' => null]);

            return $this->successResponse(null, 'Diplôme supprimé avec succès');
        } catch (\Exception $e) {
            Log::error("Formation {$formationId} diploma delete error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la suppression: ' . $e->getMessage(), 500);
        }
    }

    // ==========================================
    // PROJECTS (Galerie d'images)
    // ==========================================

    /**
     * Lister les images d'un projet
     */
    public function listProjectImages(int $projectId): JsonResponse
    {
        try {
            $project = Project::with('images')->find($projectId);
            if (!$project) {
                return $this->errorResponse('Projet non trouvé', 404);
            }

            return $this->successResponse(
                $project->images,
                'Images du projet récupérées'
            );
        } catch (\Exception $e) {
            Log::error("Project {$projectId} images list error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la récupération des images', 500);
        }
    }

    /**
     * Récupérer une image spécifique d'un projet
     */
    public function getProjectImage(int $imageId): JsonResponse
    {
        try {
            $image = ProjectImage::find($imageId);
            if (!$image) {
                return $this->errorResponse('Image non trouvée', 404);
            }

            return $this->successResponse($image, 'Image récupérée');
        } catch (\Exception $e) {
            Log::error("Get project image {$imageId} error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la récupération de l\'image', 500);
        }
    }

    /**
     * Upload une image pour un projet
     */
    public function uploadProjectImage(Request $request, int $projectId): JsonResponse
    {
        try {
            $request->validate([
                'image' => self::ALLOWED_IMAGE_MIMES ? 'required|image|mimes:' . self::ALLOWED_IMAGE_MIMES . '|max:' . self::MAX_IMAGE_SIZE : 'required|image|max:' . self::MAX_IMAGE_SIZE,
                'caption' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:5000',
            ]);

            $project = Project::find($projectId);
            if (!$project) {
                return $this->errorResponse('Projet non trouvé', 404);
            }

            $result = $this->uploadService->upload(
                $request->file('image'),
                'projects',
                "project_{$projectId}"
            );

            if (!$result['success']) {
                Log::warning("Project {$projectId} image upload failed: " . $result['error']);
                return $this->errorResponse($result['error']);
            }

            $maxOrder = ProjectImage::where('project_id', $projectId)->max('order') ?? 0;

            $image = ProjectImage::create([
                'project_id' => $projectId,
                'image_url' => $result['url'],
                'caption' => $request->input('caption'),
                'description' => $request->input('description'),
                'order' => $maxOrder + 1,
            ]);

            return $this->successResponse($image, 'Image ajoutée avec succès', 201);
        } catch (\Exception $e) {
            Log::error("Project {$projectId} image upload error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de l\'upload: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Mettre à jour une image de projet
     */
    public function updateProjectImage(Request $request, int $imageId): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'sometimes|nullable|image|mimes:' . self::ALLOWED_IMAGE_MIMES . '|max:' . self::MAX_IMAGE_SIZE,
                'caption' => 'sometimes|nullable|string|max:255',
                'description' => 'sometimes|nullable|string|max:5000',
            ]);

            $image = ProjectImage::find($imageId);
            if (!$image) {
                return $this->errorResponse('Image non trouvée', 404);
            }

            if (!$request->hasFile('image') && !$request->filled('caption') && !$request->filled('description')) {
                return $this->errorResponse('Aucune donnée à mettre à jour', 422);
            }

            $updates = [];

            if ($request->hasFile('image')) {
                $result = $this->uploadService->replace(
                    $request->file('image'),
                    $image->image_url,
                    'projects',
                    "project_{$image->project_id}"
                );

                if (!$result['success']) {
                    Log::warning("Project image {$imageId} replace failed: " . $result['error']);
                    return $this->errorResponse($result['error']);
                }

                $updates['image_url'] = $result['url'];
            }

            if ($request->filled('caption')) {
                $updates['caption'] = $request->input('caption');
            }

            if ($request->filled('description')) {
                $updates['description'] = $request->input('description');
            }

            $image->update($updates);

            return $this->successResponse($image->fresh(), 'Image mise à jour avec succès');
        } catch (\Exception $e) {
            Log::error("Project image {$imageId} update error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la mise à jour: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Supprimer une image de projet
     */
    public function deleteProjectImage(int $imageId): JsonResponse
    {
        try {
            $image = ProjectImage::find($imageId);
            if (!$image) {
                return $this->errorResponse('Image non trouvée', 404);
            }

            $this->uploadService->delete($image->image_url);
            $image->delete();

            return $this->successResponse(null, 'Image supprimée avec succès');
        } catch (\Exception $e) {
            Log::error("Project image {$imageId} delete error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la suppression: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Supprimer plusieurs images d'un projet
     */
    public function deleteProjectImages(Request $request, int $projectId): JsonResponse
    {
        try {
            $request->validate([
                'image_ids' => 'required|array|min:1',
                'image_ids.*' => 'required|integer',
            ]);

            $images = ProjectImage::where('project_id', $projectId)
                ->whereIn('id', $request->input('image_ids'))
                ->get();

            if ($images->isEmpty()) {
                return $this->errorResponse('Aucune image trouvée', 404);
            }

            foreach ($images as $image) {
                $this->uploadService->delete($image->image_url);
                $image->delete();
            }

            return $this->successResponse(
                ['deleted_count' => $images->count()],
                'Images supprimées avec succès'
            );
        } catch (\Exception $e) {
            Log::error("Project {$projectId} batch delete error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la suppression: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Mettre à jour l'ordre des images d'un projet
     */
    public function updateProjectImagesOrder(Request $request, int $projectId): JsonResponse
    {
        try {
            $request->validate([
                'images' => 'required|array|min:1',
                'images.*.id' => 'required|integer',
                'images.*.order' => 'required|integer|min:1',
            ]);

            $project = Project::find($projectId);
            if (!$project) {
                return $this->errorResponse('Projet non trouvé', 404);
            }

            DB::beginTransaction();
            foreach ($request->input('images') as $imageData) {
                ProjectImage::where('id', $imageData['id'])
                    ->where('project_id', $projectId)
                    ->update(['order' => $imageData['order']]);
            }
            DB::commit();

            return $this->successResponse(null, 'Ordre des images mis à jour');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Project {$projectId} order update error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la mise à jour de l\'ordre: ' . $e->getMessage(), 500);
        }
    }

    // ==========================================
    // PERSONAL PROJECTS (Galerie d'images)
    // ==========================================

    public function listPersonalProjectImages(int $projectId): JsonResponse
    {
        try {
            $project = PersonalProject::with('images')->find($projectId);
            if (!$project) {
                return $this->errorResponse('Projet perso non trouve', 404);
            }

            return $this->successResponse($project->images, 'Images du projet perso recuperees');
        } catch (\Exception $e) {
            Log::error("Personal project {$projectId} images list error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la recuperation des images', 500);
        }
    }

    public function getPersonalProjectImage(int $imageId): JsonResponse
    {
        try {
            $image = PersonalProjectImage::find($imageId);
            if (!$image) {
                return $this->errorResponse('Image non trouvee', 404);
            }

            return $this->successResponse($image, 'Image recuperee');
        } catch (\Exception $e) {
            Log::error("Get personal project image {$imageId} error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la recuperation de l\'image', 500);
        }
    }

    public function uploadPersonalProjectImage(Request $request, int $projectId): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:' . self::ALLOWED_IMAGE_MIMES . '|max:' . self::MAX_IMAGE_SIZE,
                'caption' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:5000',
            ]);

            $project = PersonalProject::find($projectId);
            if (!$project) {
                return $this->errorResponse('Projet perso non trouve', 404);
            }

            $result = $this->uploadService->upload(
                $request->file('image'),
                'personal-projects',
                "personal_project_{$projectId}"
            );

            if (!$result['success']) {
                Log::warning("Personal project {$projectId} image upload failed: " . $result['error']);
                return $this->errorResponse($result['error']);
            }

            $maxOrder = PersonalProjectImage::where('personal_project_id', $projectId)->max('order') ?? 0;

            $image = PersonalProjectImage::create([
                'personal_project_id' => $projectId,
                'image_url' => $result['url'],
                'caption' => $request->input('caption'),
                'description' => $request->input('description'),
                'order' => $maxOrder + 1,
            ]);

            return $this->successResponse($image, 'Image ajoutee avec succes', 201);
        } catch (\Exception $e) {
            Log::error("Personal project {$projectId} image upload error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de l\'upload: ' . $e->getMessage(), 500);
        }
    }

    public function updatePersonalProjectImage(Request $request, int $imageId): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'sometimes|nullable|image|mimes:' . self::ALLOWED_IMAGE_MIMES . '|max:' . self::MAX_IMAGE_SIZE,
                'caption' => 'sometimes|nullable|string|max:255',
                'description' => 'sometimes|nullable|string|max:5000',
            ]);

            $image = PersonalProjectImage::find($imageId);
            if (!$image) {
                return $this->errorResponse('Image non trouvee', 404);
            }

            if (!$request->hasFile('image') && !$request->exists('caption') && !$request->exists('description')) {
                return $this->errorResponse('Aucune donnee a mettre a jour', 422);
            }

            $updates = [];

            if ($request->hasFile('image')) {
                $result = $this->uploadService->replace(
                    $request->file('image'),
                    $image->image_url,
                    'personal-projects',
                    "personal_project_{$image->personal_project_id}"
                );

                if (!$result['success']) {
                    Log::warning("Personal project image {$imageId} replace failed: " . $result['error']);
                    return $this->errorResponse($result['error']);
                }

                $updates['image_url'] = $result['url'];
            }

            if ($request->exists('caption')) {
                $updates['caption'] = $request->input('caption');
            }

            if ($request->exists('description')) {
                $updates['description'] = $request->input('description');
            }

            $image->update($updates);

            return $this->successResponse($image->fresh(), 'Image mise a jour avec succes');
        } catch (\Exception $e) {
            Log::error("Personal project image {$imageId} update error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la mise a jour: ' . $e->getMessage(), 500);
        }
    }

    public function deletePersonalProjectImage(int $imageId): JsonResponse
    {
        try {
            $image = PersonalProjectImage::find($imageId);
            if (!$image) {
                return $this->errorResponse('Image non trouvee', 404);
            }

            $this->uploadService->delete($image->image_url);
            $image->delete();

            return $this->successResponse(null, 'Image supprimee avec succes');
        } catch (\Exception $e) {
            Log::error("Personal project image {$imageId} delete error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la suppression: ' . $e->getMessage(), 500);
        }
    }

    public function deletePersonalProjectImages(Request $request, int $projectId): JsonResponse
    {
        try {
            $request->validate([
                'image_ids' => 'required|array|min:1',
                'image_ids.*' => 'required|integer',
            ]);

            $images = PersonalProjectImage::where('personal_project_id', $projectId)
                ->whereIn('id', $request->input('image_ids'))
                ->get();

            if ($images->isEmpty()) {
                return $this->errorResponse('Aucune image trouvee', 404);
            }

            foreach ($images as $image) {
                $this->uploadService->delete($image->image_url);
                $image->delete();
            }

            return $this->successResponse(['deleted_count' => $images->count()], 'Images supprimees avec succes');
        } catch (\Exception $e) {
            Log::error("Personal project {$projectId} batch delete error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la suppression: ' . $e->getMessage(), 500);
        }
    }

    public function updatePersonalProjectImagesOrder(Request $request, int $projectId): JsonResponse
    {
        try {
            $request->validate([
                'images' => 'required|array|min:1',
                'images.*.id' => 'required|integer',
                'images.*.order' => 'required|integer|min:1',
            ]);

            $project = PersonalProject::find($projectId);
            if (!$project) {
                return $this->errorResponse('Projet perso non trouve', 404);
            }

            DB::beginTransaction();
            foreach ($request->input('images') as $imageData) {
                PersonalProjectImage::where('id', $imageData['id'])
                    ->where('personal_project_id', $projectId)
                    ->update(['order' => $imageData['order']]);
            }
            DB::commit();

            return $this->successResponse(null, 'Ordre des images mis a jour');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Personal project {$projectId} order update error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la mise a jour de l\'ordre: ' . $e->getMessage(), 500);
        }
    }

    // ==========================================
    // REALISATIONS (Galerie d'images)
    // ==========================================

    /**
     * Lister les images d'une réalisation
     */
    public function listRealisationImages(int $realisationId): JsonResponse
    {
        try {
            $realisation = Realisation::with('images')->find($realisationId);
            if (!$realisation) {
                return $this->errorResponse('Réalisation non trouvée', 404);
            }

            return $this->successResponse(
                $realisation->images,
                'Images de la réalisation récupérées'
            );
        } catch (\Exception $e) {
            Log::error("Realisation {$realisationId} images list error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la récupération des images', 500);
        }
    }

    /**
     * Récupérer une image spécifique d'une réalisation
     */
    public function getRealisationImage(int $imageId): JsonResponse
    {
        try {
            $image = RealisationImage::find($imageId);
            if (!$image) {
                return $this->errorResponse('Image non trouvée', 404);
            }

            return $this->successResponse($image, 'Image récupérée');
        } catch (\Exception $e) {
            Log::error("Get realisation image {$imageId} error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la récupération de l\'image', 500);
        }
    }

    /**
     * Upload une image pour une réalisation
     */
    public function uploadRealisationImage(Request $request, int $realisationId): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:' . self::ALLOWED_IMAGE_MIMES . '|max:' . self::MAX_IMAGE_SIZE,
                'caption' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:5000',
            ]);

            $realisation = Realisation::find($realisationId);
            if (!$realisation) {
                return $this->errorResponse('Réalisation non trouvée', 404);
            }

            $result = $this->uploadService->upload(
                $request->file('image'),
                'realisations',
                "realisation_{$realisationId}"
            );

            if (!$result['success']) {
                Log::warning("Realisation {$realisationId} image upload failed: " . $result['error']);
                return $this->errorResponse($result['error']);
            }

            $maxOrder = RealisationImage::where('realisation_id', $realisationId)->max('order') ?? 0;

            $image = RealisationImage::create([
                'realisation_id' => $realisationId,
                'image_url' => $result['url'],
                'caption' => $request->input('caption'),
                'description' => $request->input('description'),
                'order' => $maxOrder + 1,
            ]);

            return $this->successResponse($image, 'Image ajoutée avec succès', 201);
        } catch (\Exception $e) {
            Log::error("Realisation {$realisationId} image upload error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de l\'upload: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Mettre à jour une image de réalisation
     */
    public function updateRealisationImage(Request $request, int $imageId): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'sometimes|nullable|image|mimes:' . self::ALLOWED_IMAGE_MIMES . '|max:' . self::MAX_IMAGE_SIZE,
                'caption' => 'sometimes|nullable|string|max:255',
                'description' => 'sometimes|nullable|string|max:5000',
            ]);

            $image = RealisationImage::find($imageId);
            if (!$image) {
                return $this->errorResponse('Image non trouvée', 404);
            }

            if (!$request->hasFile('image') && !$request->filled('caption') && !$request->filled('description')) {
                return $this->errorResponse('Aucune donnée à mettre à jour', 422);
            }

            $updates = [];

            if ($request->hasFile('image')) {
                $result = $this->uploadService->replace(
                    $request->file('image'),
                    $image->image_url,
                    'realisations',
                    "realisation_{$image->realisation_id}"
                );

                if (!$result['success']) {
                    Log::warning("Realisation image {$imageId} replace failed: " . $result['error']);
                    return $this->errorResponse($result['error']);
                }

                $updates['image_url'] = $result['url'];
            }

            if ($request->filled('caption')) {
                $updates['caption'] = $request->input('caption');
            }

            if ($request->filled('description')) {
                $updates['description'] = $request->input('description');
            }

            $image->update($updates);

            return $this->successResponse($image->fresh(), 'Image mise à jour avec succès');
        } catch (\Exception $e) {
            Log::error("Realisation image {$imageId} update error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la mise à jour: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Supprimer une image de réalisation
     */
    public function deleteRealisationImage(int $imageId): JsonResponse
    {
        try {
            $image = RealisationImage::find($imageId);
            if (!$image) {
                return $this->errorResponse('Image non trouvée', 404);
            }

            $this->uploadService->delete($image->image_url);
            $image->delete();

            return $this->successResponse(null, 'Image supprimée avec succès');
        } catch (\Exception $e) {
            Log::error("Realisation image {$imageId} delete error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la suppression: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Supprimer plusieurs images d'une réalisation
     */
    public function deleteRealisationImages(Request $request, int $realisationId): JsonResponse
    {
        try {
            $request->validate([
                'image_ids' => 'required|array|min:1',
                'image_ids.*' => 'required|integer',
            ]);

            $images = RealisationImage::where('realisation_id', $realisationId)
                ->whereIn('id', $request->input('image_ids'))
                ->get();

            if ($images->isEmpty()) {
                return $this->errorResponse('Aucune image trouvée', 404);
            }

            foreach ($images as $image) {
                $this->uploadService->delete($image->image_url);
                $image->delete();
            }

            return $this->successResponse(
                ['deleted_count' => $images->count()],
                'Images supprimées avec succès'
            );
        } catch (\Exception $e) {
            Log::error("Realisation {$realisationId} batch delete error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la suppression: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Mettre à jour l'ordre des images d'une réalisation
     */
    public function updateRealisationImagesOrder(Request $request, int $realisationId): JsonResponse
    {
        try {
            $request->validate([
                'images' => 'required|array|min:1',
                'images.*.id' => 'required|integer',
                'images.*.order' => 'required|integer|min:1',
            ]);

            $realisation = Realisation::find($realisationId);
            if (!$realisation) {
                return $this->errorResponse('Réalisation non trouvée', 404);
            }

            DB::beginTransaction();
            foreach ($request->input('images') as $imageData) {
                RealisationImage::where('id', $imageData['id'])
                    ->where('realisation_id', $realisationId)
                    ->update(['order' => $imageData['order']]);
            }
            DB::commit();

            return $this->successResponse(null, 'Ordre des images mis à jour');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Realisation {$realisationId} order update error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la mise à jour de l\'ordre: ' . $e->getMessage(), 500);
        }
    }

    // ==========================================
    // LEGACY (compatibilité)
    // ==========================================

    /**
     * Mettre à jour la description longue d'un projet
     */
    /**
     * Mettre à jour la description longue d'un projet (Legacy)
     */
    public function updateProjectLongDescription(Request $request, int $projectId): JsonResponse
    {
        try {
            $request->validate([
                'long_description' => 'nullable|string|max:10000',
            ]);

            $project = Project::find($projectId);
            if (!$project) {
                return $this->errorResponse('Projet non trouvé', 404);
            }

            $project->update([
                'long_description' => $request->input('long_description'),
            ]);

            return $this->successResponse($project, 'Description mise à jour avec succès');
        } catch (\Exception $e) {
            Log::error("Project {$projectId} long description update error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la mise à jour: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Mettre à jour la description longue d'une réalisation (Legacy)
     */
    public function updateRealisationLongDescription(Request $request, int $realisationId): JsonResponse
    {
        try {
            $request->validate([
                'long_description' => 'nullable|string|max:10000',
            ]);

            $realisation = Realisation::find($realisationId);
            if (!$realisation) {
                return $this->errorResponse('Réalisation non trouvée', 404);
            }

            $realisation->update([
                'long_description' => $request->input('long_description'),
            ]);

            return $this->successResponse($realisation, 'Description mise à jour avec succès');
        } catch (\Exception $e) {
            Log::error("Realisation {$realisationId} long description update error: " . $e->getMessage());
            return $this->errorResponse('Erreur lors de la mise à jour: ' . $e->getMessage(), 500);
        }
    }
}
