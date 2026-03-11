<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\DashboardApiController;

// Route ping pour garder le serveur éveillé
Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'pong',
        'timestamp' => now()->toISOString()
    ]);
});

// ==========================================
// ROUTES PUBLIQUES (Lecture seule)
// ==========================================

Route::get('/portfolio', [ApiController::class, 'portfolio']);
Route::get('/stages', [ApiController::class, 'stages']);
Route::get('/formations', [DashboardApiController::class, 'listFormations']);
Route::get('/projects', [ApiController::class, 'projects']);
Route::get('/projects/{id}', [ApiController::class, 'projectDetail']);
Route::get('/realisations', [ApiController::class, 'realisations']);
Route::get('/realisations/{id}', [ApiController::class, 'realisationDetail']);
Route::get('/companies', [ApiController::class, 'companies']);
Route::get('/competences', [ApiController::class, 'competences']);

// Route complète avec toutes les données
Route::get('/all', [ApiController::class, 'all']);

// Messages importants actifs (pour l'affichage public)
Route::get('/messages', [DashboardApiController::class, 'listActiveMessages']);

// ==========================================
// ROUTES DASHBOARD (CRUD complet)
// ==========================================

Route::prefix('dashboard')->group(function () {

    // Stats
    Route::get('/stats', [DashboardApiController::class, 'getStats']);

    // Portfolio
    Route::get('/portfolio', [DashboardApiController::class, 'getPortfolio']);
    Route::put('/portfolio', [DashboardApiController::class, 'updatePortfolio']);

    // Companies
    Route::get('/companies', [DashboardApiController::class, 'listCompanies']);
    Route::get('/companies/{id}', [DashboardApiController::class, 'getCompany']);
    Route::post('/companies', [DashboardApiController::class, 'createCompany']);
    Route::put('/companies/{id}', [DashboardApiController::class, 'updateCompany']);
    Route::delete('/companies/{id}', [DashboardApiController::class, 'deleteCompany']);

    // Stages
    Route::get('/stages', [DashboardApiController::class, 'listStages']);
    Route::get('/stages/{id}', [DashboardApiController::class, 'getStage']);
    Route::post('/stages', [DashboardApiController::class, 'createStage']);
    Route::put('/stages/{id}', [DashboardApiController::class, 'updateStage']);
    Route::delete('/stages/{id}', [DashboardApiController::class, 'deleteStage']);

    // Competences
    Route::get('/competences', [DashboardApiController::class, 'listCompetences']);
    Route::get('/competences/{id}', [DashboardApiController::class, 'getCompetence']);
    Route::post('/competences', [DashboardApiController::class, 'createCompetence']);
    Route::put('/competences/{id}', [DashboardApiController::class, 'updateCompetence']);
    Route::delete('/competences/{id}', [DashboardApiController::class, 'deleteCompetence']);

    // Projects
    Route::get('/projects', [DashboardApiController::class, 'listProjects']);
    Route::get('/projects/{id}', [DashboardApiController::class, 'getProject']);
    Route::post('/projects', [DashboardApiController::class, 'createProject']);
    Route::put('/projects/{id}', [DashboardApiController::class, 'updateProject']);
    Route::delete('/projects/{id}', [DashboardApiController::class, 'deleteProject']);

    // Realisations
    Route::get('/realisations', [DashboardApiController::class, 'listRealisations']);
    Route::get('/realisations/{id}', [DashboardApiController::class, 'getRealisation']);
    Route::post('/realisations', [DashboardApiController::class, 'createRealisation']);
    Route::put('/realisations/{id}', [DashboardApiController::class, 'updateRealisation']);
    Route::delete('/realisations/{id}', [DashboardApiController::class, 'deleteRealisation']);

    // Important Messages
    Route::get('/messages', [DashboardApiController::class, 'listMessages']);
    Route::get('/messages/{id}', [DashboardApiController::class, 'getMessage']);
    Route::post('/messages', [DashboardApiController::class, 'createMessage']);
    Route::put('/messages/{id}', [DashboardApiController::class, 'updateMessage']);
    Route::patch('/messages/{id}/toggle', [DashboardApiController::class, 'toggleMessage']);
    Route::delete('/messages/{id}', [DashboardApiController::class, 'deleteMessage']);

    // Formations (Parcours/Diplômes)
    Route::get('/formations', [DashboardApiController::class, 'listFormations']);
    Route::get('/formations/{id}', [DashboardApiController::class, 'getFormation']);
    Route::post('/formations', [DashboardApiController::class, 'createFormation']);
    Route::put('/formations/{id}', [DashboardApiController::class, 'updateFormation']);
    Route::delete('/formations/{id}', [DashboardApiController::class, 'deleteFormation']);
});

// ==========================================
// ROUTES IMAGES
// ==========================================

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

// Routes legacy pour compatibilité
Route::put('/projects/{id}/description', [ImageUploadController::class, 'updateProjectLongDescription']);
Route::put('/realisations/{id}/description', [ImageUploadController::class, 'updateRealisationLongDescription']);
