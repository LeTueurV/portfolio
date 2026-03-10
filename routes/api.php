<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ImageUploadController;

// Route ping pour garder le serveur éveillé
Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'pong',
        'timestamp' => now()->toISOString()
    ]);
});

// Routes API Portfolio
Route::get('/portfolio', [ApiController::class, 'portfolio']);
Route::get('/stages', [ApiController::class, 'stages']);
Route::get('/projects', [ApiController::class, 'projects']);
Route::get('/projects/{id}', [ApiController::class, 'projectDetail']);
Route::put('/projects/{id}/description', [ImageUploadController::class, 'updateProjectLongDescription']);
Route::get('/realisations', [ApiController::class, 'realisations']);
Route::get('/realisations/{id}', [ApiController::class, 'realisationDetail']);
Route::put('/realisations/{id}/description', [ImageUploadController::class, 'updateRealisationLongDescription']);
Route::get('/companies', [ApiController::class, 'companies']);
Route::get('/competences', [ApiController::class, 'competences']);

// Route complète avec toutes les données
Route::get('/all', [ApiController::class, 'all']);

// Routes pour l'upload d'images
Route::prefix('images')->group(function () {
    // Images de projets
    Route::get('/projects/{projectId}', [ImageUploadController::class, 'listProjectImages']);
    Route::post('/projects/{projectId}', [ImageUploadController::class, 'uploadProjectImage']);
    Route::delete('/projects/image/{imageId}', [ImageUploadController::class, 'deleteProjectImage']);
    Route::put('/projects/{projectId}/order', [ImageUploadController::class, 'updateProjectImagesOrder']);

    // Images de réalisations
    Route::get('/realisations/{realisationId}', [ImageUploadController::class, 'listRealisationImages']);
    Route::post('/realisations/{realisationId}', [ImageUploadController::class, 'uploadRealisationImage']);
    Route::delete('/realisations/image/{imageId}', [ImageUploadController::class, 'deleteRealisationImage']);
    Route::put('/realisations/{realisationId}/order', [ImageUploadController::class, 'updateRealisationImagesOrder']);
});
