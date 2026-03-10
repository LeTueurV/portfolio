<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Realisation;
use App\Models\RealisationImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    /**
     * Upload une image pour un projet
     */
    public function uploadProjectImage(Request $request, int $projectId): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'caption' => 'nullable|string|max:255',
        ]);

        $project = Project::find($projectId);
        if (!$project) {
            return response()->json(['error' => 'Projet non trouvé'], 404);
        }

        $file = $request->file('image');
        $filename = 'project_' . $projectId . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('projects', $filename, 'public');

        $maxOrder = ProjectImage::where('project_id', $projectId)->max('order') ?? 0;

        $image = ProjectImage::create([
            'project_id' => $projectId,
            'image_url' => '/storage/' . $path,
            'caption' => $request->input('caption'),
            'order' => $maxOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'image' => $image,
        ]);
    }

    /**
     * Upload une image pour une réalisation
     */
    public function uploadRealisationImage(Request $request, int $realisationId): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'caption' => 'nullable|string|max:255',
        ]);

        $realisation = Realisation::find($realisationId);
        if (!$realisation) {
            return response()->json(['error' => 'Réalisation non trouvée'], 404);
        }

        $file = $request->file('image');
        $filename = 'realisation_' . $realisationId . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('realisations', $filename, 'public');

        $maxOrder = RealisationImage::where('realisation_id', $realisationId)->max('order') ?? 0;

        $image = RealisationImage::create([
            'realisation_id' => $realisationId,
            'image_url' => '/storage/' . $path,
            'caption' => $request->input('caption'),
            'order' => $maxOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'image' => $image,
        ]);
    }

    /**
     * Supprimer une image de projet
     */
    public function deleteProjectImage(int $imageId): JsonResponse
    {
        $image = ProjectImage::find($imageId);
        if (!$image) {
            return response()->json(['error' => 'Image non trouvée'], 404);
        }

        // Supprimer le fichier
        $path = str_replace('/storage/', '', $image->image_url);
        Storage::disk('public')->delete($path);

        $image->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Supprimer une image de réalisation
     */
    public function deleteRealisationImage(int $imageId): JsonResponse
    {
        $image = RealisationImage::find($imageId);
        if (!$image) {
            return response()->json(['error' => 'Image non trouvée'], 404);
        }

        // Supprimer le fichier
        $path = str_replace('/storage/', '', $image->image_url);
        Storage::disk('public')->delete($path);

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
            return response()->json(['error' => 'Projet non trouvé'], 404);
        }

        return response()->json($project->images);
    }

    /**
     * Lister les images d'une réalisation
     */
    public function listRealisationImages(int $realisationId): JsonResponse
    {
        $realisation = Realisation::with('images')->find($realisationId);
        if (!$realisation) {
            return response()->json(['error' => 'Réalisation non trouvée'], 404);
        }

        return response()->json($realisation->images);
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
}
