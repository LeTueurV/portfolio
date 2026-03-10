<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

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
Route::get('/realisations', [ApiController::class, 'realisations']);
Route::get('/companies', [ApiController::class, 'companies']);
Route::get('/competences', [ApiController::class, 'competences']);

// Route complète avec toutes les données
Route::get('/all', [ApiController::class, 'all']);
