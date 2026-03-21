<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\DashboardApiController;
use App\Http\Middleware\JwtMiddleware;

// Route ping pour garder le serveur éveillé
Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'pong',
        'timestamp' => now()->toISOString()
    ]);
});

// Route pour Swagger/OpenAPI documentation (dynamique, basée sur APP_URL)
Route::get('/docs', function () {
    $yaml = file_get_contents(base_path('openapi.yaml'));
    $appUrl = config('app.url');
    $spec = str_replace(
        'https://portfolio-mlb3.onrender.com/api',
        rtrim($appUrl, '/') . '/api',
        $yaml
    );
    
    return view('swagger', [
        'spec' => $spec
    ]);
})->name('api.docs');

// Route pour OpenAPI spec en YAML (dynamique, basée sur APP_URL)
Route::get('/openapi.yaml', function () {
    $yaml = file_get_contents(base_path('openapi.yaml'));
    
    // Remplacer les URLs serveur par l'URL réelle (APP_URL)
    $appUrl = config('app.url');
    $yaml = str_replace(
        'https://portfolio-mlb3.onrender.com/api',
        rtrim($appUrl, '/') . '/api',
        $yaml
    );
    
    return response($yaml, 200, [
        'Content-Type' => 'application/x-yaml'
    ]);
})->name('api.openapi');

// ==========================================
// ROUTES AUTHENTIFICATION
// ==========================================

Route::prefix('auth')->group(function () {
    // Register (Créer un nouvel utilisateur - Lecteur par défaut)
    Route::post('/register', [AuthController::class, 'register']);
    
    // Login (Obtenir un token JWT)
    Route::post('/login', [AuthController::class, 'login']);
    
    // Routes protégées par JWT
    Route::middleware(JwtMiddleware::class)->group(function () {
        // Refresh token
        Route::post('/refresh', [AuthController::class, 'refresh']);
        
        // Logout
        Route::post('/logout', [AuthController::class, 'logout']);
        
        // Get current user profile
        Route::get('/me', [AuthController::class, 'me']);
    });
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

// Formulaire de contact (public)
Route::post('/contact', [ContactController::class, 'send']);

// ==========================================
// ROUTES DASHBOARD PROTÉGÉES (CRUD complet - Admin seulement)
// ==========================================

Route::prefix('dashboard')->middleware(['App\\Http\\Middleware\\JwtMiddleware:admin'])
->group(function () {

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
// ROUTES IMAGES PROTÉGÉES (Upload, modification, suppression - Admin seulement)
// ==========================================

Route::prefix('images')->middleware(['App\\Http\\Middleware\\JwtMiddleware:admin'])
->group(function () {
    // ==========================================
    // PORTFOLIO
    // ==========================================
    Route::prefix('portfolio')->group(function () {
        Route::post('/', [ImageUploadController::class, 'uploadPortfolioPhoto']);
        Route::put('/', [ImageUploadController::class, 'uploadPortfolioPhoto']);
        Route::delete('/', [ImageUploadController::class, 'deletePortfolioPhoto']);
    });

    // ==========================================
    // COMPANIES
    // ==========================================
    Route::prefix('companies')->group(function () {
        Route::post('/{companyId}', [ImageUploadController::class, 'uploadCompanyPhoto']);
        Route::delete('/{companyId}', [ImageUploadController::class, 'deleteCompanyPhoto']);
    });

    // ==========================================
    // FORMATIONS
    // ==========================================
    Route::prefix('formations/{formationId}')->group(function () {
        // Logo
        Route::prefix('logo')->group(function () {
            Route::post('/', [ImageUploadController::class, 'uploadFormationLogo']);
            Route::put('/', [ImageUploadController::class, 'uploadFormationLogo']);
            Route::delete('/', [ImageUploadController::class, 'deleteFormationLogo']);
        });

        // Diploma
        Route::prefix('diploma')->group(function () {
            Route::post('/', [ImageUploadController::class, 'uploadFormationDiploma']);
            Route::put('/', [ImageUploadController::class, 'uploadFormationDiploma']);
            Route::delete('/', [ImageUploadController::class, 'deleteFormationDiploma']);
        });
    });

    // ==========================================
    // PROJECTS - Galerie d'images
    // ==========================================
    Route::prefix('projects')->group(function () {
        // Lister et ajouter des images
        Route::get('/{projectId}', [ImageUploadController::class, 'listProjectImages']);
        Route::post('/{projectId}', [ImageUploadController::class, 'uploadProjectImage']);
        
        // Supprimer plusieurs images
        Route::delete('/{projectId}', [ImageUploadController::class, 'deleteProjectImages']);
        
        // Mettre à jour l'ordre
        Route::put('/{projectId}/order', [ImageUploadController::class, 'updateProjectImagesOrder']);

        // Image individuelle
        Route::get('/image/{imageId}', [ImageUploadController::class, 'getProjectImage']);
        Route::put('/image/{imageId}', [ImageUploadController::class, 'updateProjectImage']);
        Route::delete('/image/{imageId}', [ImageUploadController::class, 'deleteProjectImage']);
    });

    // ==========================================
    // REALISATIONS - Galerie d'images
    // ==========================================
    Route::prefix('realisations')->group(function () {
        // Lister et ajouter des images
        Route::get('/{realisationId}', [ImageUploadController::class, 'listRealisationImages']);
        Route::post('/{realisationId}', [ImageUploadController::class, 'uploadRealisationImage']);
        
        // Supprimer plusieurs images
        Route::delete('/{realisationId}', [ImageUploadController::class, 'deleteRealisationImages']);
        
        // Mettre à jour l'ordre
        Route::put('/{realisationId}/order', [ImageUploadController::class, 'updateRealisationImagesOrder']);

        // Image individuelle
        Route::get('/image/{imageId}', [ImageUploadController::class, 'getRealisationImage']);
        Route::put('/image/{imageId}', [ImageUploadController::class, 'updateRealisationImage']);
        Route::delete('/image/{imageId}', [ImageUploadController::class, 'deleteRealisationImage']);
    });
});

// Routes legacy pour compatibilité
Route::put('/projects/{id}/description', [ImageUploadController::class, 'updateProjectLongDescription']);
Route::put('/realisations/{id}/description', [ImageUploadController::class, 'updateRealisationLongDescription']);

