<?php

namespace App\Http\Controllers;

use App\Models\PersonalProject;
use App\Models\PersonalProjectTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PersonalProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = PersonalProject::with(['tags', 'images'])
            ->orderBy('order')
            ->orderBy('year', 'desc')
            ->get();

        return response()->json($projects);
    }

    public function show(int $id): JsonResponse
    {
        $project = PersonalProject::with(['tags', 'images'])->find($id);

        if (!$project) {
            return response()->json(['error' => 'Projet perso non trouve'], 404);
        }

        return response()->json($project);
    }

    public function dashboardIndex(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => PersonalProject::with(['tags', 'images'])
                ->orderBy('order')
                ->orderBy('year', 'desc')
                ->get(),
        ]);
    }

    public function dashboardShow(int $id): JsonResponse
    {
        $project = PersonalProject::with(['tags', 'images'])->find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'error' => 'Projet perso non trouve',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $project,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $project = PersonalProject::create($validator->safe()->except(['tags']));
            $this->syncTags($project, $request->input('tags', []));

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $project->load(['tags', 'images']),
                'message' => 'Projet perso cree avec succes',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la creation du projet perso: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $project = PersonalProject::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'error' => 'Projet perso non trouve',
            ], 404);
        }

        $validator = $this->validator($request, true);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $project->update($validator->safe()->except(['tags']));

            if ($request->has('tags')) {
                $project->tags()->delete();
                $this->syncTags($project, $request->input('tags', []));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $project->load(['tags', 'images']),
                'message' => 'Projet perso mis a jour avec succes',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la mise a jour du projet perso: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $project = PersonalProject::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'error' => 'Projet perso non trouve',
            ], 404);
        }

        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Projet perso supprime avec succes',
        ]);
    }

    private function validator(Request $request, bool $isUpdate = false)
    {
        $titleRule = $isUpdate ? 'sometimes|string|max:255' : 'required|string|max:255';

        return Validator::make($request->all(), [
            'title' => $titleRule,
            'type' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:5000',
            'long_description' => 'nullable|string|max:50000',
            'year' => 'nullable|string|max:20',
            'github_url' => 'nullable|url|max:500',
            'demo_url' => 'nullable|url|max:500',
            'order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:100',
        ]);
    }

    private function syncTags(PersonalProject $project, array $tags): void
    {
        foreach ($tags as $tag) {
            PersonalProjectTag::create([
                'personal_project_id' => $project->id,
                'tag' => $tag,
            ]);
        }
    }
}
