<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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

// Route de test R2 (DEBUG) - Simple inline
Route::get('/test-r2-simple', function () {
    return response()->json([
        'status' => 'endpoint works',
        'disk_configured' => env('FILESYSTEM_DISK'),
        'bucket_name' => env('CLOUDFLARE_R2_BUCKET'),
        'has_key' => !empty(env('CLOUDFLARE_R2_ACCESS_KEY_ID')),
        'has_secret' => !empty(env('CLOUDFLARE_R2_SECRET_ACCESS_KEY')),
    ]);
});

// Route de test R2 - Avec Storage
Route::get('/test-r2', function () {
    try {
        $response = [
            'success' => true,
            'bucket' => env('CLOUDFLARE_R2_BUCKET'),
            'filesystem_disk' => env('FILESYSTEM_DISK'),
            'endpoint' => env('CLOUDFLARE_R2_ENDPOINT'),
            'disk_exists' => true
        ];
        
        // Essayer d'accéder au disque R2
        $disk = Storage::disk('r2');
        $response['disk_accessible'] = true;
        $response['message'] = 'Disque R2 OK';
        
        return response()->json($response);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'bucket' => env('CLOUDFLARE_R2_BUCKET'),
            'filesystem_disk' => env('FILESYSTEM_DISK'),
        ], 500);
    }
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
// ROUTES IMAGES (Upload)
// ==========================================

Route::prefix('images')->group(function () {
    // Portfolio
    Route::post('/portfolio', [ImageUploadController::class, 'uploadPortfolioPhoto']);
    Route::put('/portfolio', [ImageUploadController::class, 'uploadPortfolioPhoto']);
    Route::delete('/portfolio', [ImageUploadController::class, 'deletePortfolioPhoto']);

    // Companies
    Route::post('/companies/{companyId}', [ImageUploadController::class, 'uploadCompanyPhoto']);
    Route::put('/companies/{companyId}', [ImageUploadController::class, 'uploadCompanyPhoto']);
    Route::delete('/companies/{companyId}', [ImageUploadController::class, 'deleteCompanyPhoto']);

    // Formations
    Route::post('/formations/{formationId}/logo', [ImageUploadController::class, 'uploadFormationLogo']);
    Route::put('/formations/{formationId}/logo', [ImageUploadController::class, 'uploadFormationLogo']);
    Route::delete('/formations/{formationId}/logo', [ImageUploadController::class, 'deleteFormationLogo']);
    Route::post('/formations/{formationId}/diploma', [ImageUploadController::class, 'uploadFormationDiploma']);
    Route::put('/formations/{formationId}/diploma', [ImageUploadController::class, 'uploadFormationDiploma']);
    Route::delete('/formations/{formationId}/diploma', [ImageUploadController::class, 'deleteFormationDiploma']);

    // Projects (galerie d'images)
    Route::get('/projects/{projectId}', [ImageUploadController::class, 'listProjectImages']);
    Route::post('/projects/{projectId}', [ImageUploadController::class, 'uploadProjectImage']);
    Route::put('/projects/image/{imageId}', [ImageUploadController::class, 'updateProjectImage']);
    Route::delete('/projects/image/{imageId}', [ImageUploadController::class, 'deleteProjectImage']);
    Route::put('/projects/{projectId}/order', [ImageUploadController::class, 'updateProjectImagesOrder']);

    // Realisations (galerie d'images)
    Route::get('/realisations/{realisationId}', [ImageUploadController::class, 'listRealisationImages']);
    Route::post('/realisations/{realisationId}', [ImageUploadController::class, 'uploadRealisationImage']);
    Route::put('/realisations/image/{imageId}', [ImageUploadController::class, 'updateRealisationImage']);
    Route::delete('/realisations/image/{imageId}', [ImageUploadController::class, 'deleteRealisationImage']);
    Route::put('/realisations/{realisationId}/order', [ImageUploadController::class, 'updateRealisationImagesOrder']);
});

// Routes legacy pour compatibilité
Route::put('/projects/{id}/description', [ImageUploadController::class, 'updateProjectLongDescription']);
Route::put('/realisations/{id}/description', [ImageUploadController::class, 'updateRealisationLongDescription']);
