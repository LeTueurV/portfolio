<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class TestR2Controller extends Controller
{
    /**
     * Test la configuration Cloudflare R2
     */
    public function testR2(): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => false,
            'checks' => [],
            'errors' => []
        ];

        // 1. Vérifier les variables d'environnement
        $response['checks']['env_variables'] = [
            'CLOUDFLARE_R2_ACCESS_KEY_ID' => !empty(env('CLOUDFLARE_R2_ACCESS_KEY_ID')),
            'CLOUDFLARE_R2_SECRET_ACCESS_KEY' => !empty(env('CLOUDFLARE_R2_SECRET_ACCESS_KEY')),
            'CLOUDFLARE_R2_BUCKET' => env('CLOUDFLARE_R2_BUCKET'),
            'CLOUDFLARE_R2_ENDPOINT' => env('CLOUDFLARE_R2_ENDPOINT'),
            'CLOUDFLARE_R2_PUBLIC_URL' => env('CLOUDFLARE_R2_PUBLIC_URL'),
            'FILESYSTEM_DISK' => env('FILESYSTEM_DISK'),
        ];

        // 2. Vérifier la configuration du disque
        $diskConfig = config('filesystems.disks.r2');
        $response['checks']['disk_config'] = [
            'exists' => !empty($diskConfig),
            'driver' => $diskConfig['driver'] ?? 'not set',
            'region' => $diskConfig['region'] ?? 'not set',
            'bucket' => $diskConfig['bucket'] ?? 'not set',
        ];

        // 3. Tester la connexion à R2
        try {
            // Tenter de lister les fichiers (sans les lire, juste vérifier la connexion)
            $files = Storage::disk('r2')->listContents('/');
            $response['checks']['connection'] = [
                'status' => 'connected',
                'file_count' => count($files->toArray())
            ];
            $response['success'] = true;
        } catch (\Exception $e) {
            $response['checks']['connection'] = [
                'status' => 'failed',
                'error' => $e->getMessage()
            ];
            $response['errors'][] = "Erreur de connexion R2: " . $e->getMessage();
        }

        // 4. Tester un mini-upload (test fichier)
        try {
            $testContent = "test_" . time();
            $testPath = ".test-r2-" . $testContent . ".txt";
            
            Storage::disk('r2')->put($testPath, "Test file for R2 verification");
            
            $url = Storage::disk('r2')->url($testPath);
            
            $response['checks']['upload_test'] = [
                'status' => 'success',
                'path' => $testPath,
                'url' => $url
            ];
            
            // Nettoyer le fichier de test
            Storage::disk('r2')->delete($testPath);
            
        } catch (\Exception $e) {
            $response['checks']['upload_test'] = [
                'status' => 'failed',
                'error' => $e->getMessage()
            ];
            $response['errors'][] = "Erreur upload test: " . $e->getMessage();
        }

        // 5. Résumé
        if (empty($response['errors'])) {
            $response['success'] = true;
            $response['message'] = '✅ Cloudflare R2 est correctement configuré et fonctionnel!';
        } else {
            $response['message'] = '❌ Erreurs détectées - voir errors et checks';
        }

        return response()->json($response);
    }
}
