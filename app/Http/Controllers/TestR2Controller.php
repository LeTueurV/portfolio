<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class TestR2Controller extends Controller
{
    /**
     * Test la configuration Cloudflare R2
     */
    public function testR2()
    {
        $response = [
            'success' => false,
            'checks' => [],
            'errors' => []
        ];

        try {
            // 1. Vérifier les variables d'environnement
            $response['checks']['env_variables'] = [
                'CLOUDFLARE_R2_ACCESS_KEY_ID' => !empty(env('CLOUDFLARE_R2_ACCESS_KEY_ID')) ? 'Set' : 'Missing',
                'CLOUDFLARE_R2_SECRET_ACCESS_KEY' => !empty(env('CLOUDFLARE_R2_SECRET_ACCESS_KEY')) ? 'Set' : 'Missing',
                'CLOUDFLARE_R2_BUCKET' => env('CLOUDFLARE_R2_BUCKET', 'Missing'),
                'CLOUDFLARE_R2_ENDPOINT' => env('CLOUDFLARE_R2_ENDPOINT', 'Missing'),
                'CLOUDFLARE_R2_PUBLIC_URL' => env('CLOUDFLARE_R2_PUBLIC_URL', 'Missing'),
                'FILESYSTEM_DISK' => env('FILESYSTEM_DISK', 'Missing'),
            ];

            // 2. Vérifier la configuration du disque
            $diskConfig = config('filesystems.disks.r2');
            if ($diskConfig) {
                $response['checks']['disk_config'] = [
                    'exists' => true,
                    'driver' => $diskConfig['driver'] ?? 'not set',
                    'region' => $diskConfig['region'] ?? 'not set',
                    'bucket' => $diskConfig['bucket'] ?? 'not set',
                ];
            } else {
                $response['checks']['disk_config'] = [
                    'exists' => false,
                    'error' => 'R2 disk not found in config'
                ];
                $response['errors'][] = "Disque R2 non trouvé dans la config";
            }

            // 3. Tester la connexion à R2
            try {
                $files = Storage::disk('r2')->listContents('/');
                $response['checks']['connection'] = [
                    'status' => 'connected',
                    'verified' => true
                ];
            } catch (\Exception $connError) {
                $response['checks']['connection'] = [
                    'status' => 'failed',
                    'error' => $connError->getMessage()
                ];
                $response['errors'][] = "Connexion R2 échouée: " . $connError->getMessage();
            }

            // 4. Résumé
            if (empty($response['errors'])) {
                $response['success'] = true;
                $response['message'] = '✅ Cloudflare R2 OK!';
            } else {
                $response['message'] = '❌ Erreurs détectées';
            }

        } catch (\Exception $e) {
            $response['success'] = false;
            $response['error'] = $e->getMessage();
            $response['message'] = '❌ Erreur générale: ' . $e->getMessage();
        }

        return response()->json($response);
    }
}

