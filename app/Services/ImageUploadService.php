<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Dossiers autorisés pour l'upload
     */
    private array $allowedFolders = [
        'portfolio',
        'companies',
        'formations',
        'projects',
        'realisations',
        'diplomas',
        'messages',
    ];

    /**
     * Extensions autorisées
     */
    private array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

    /**
     * Extensions autorisées pour les documents
     */
    private array $allowedDocumentExtensions = ['pdf', 'jpg', 'jpeg', 'png'];

    /**
     * Taille max en KB (5MB par défaut)
     */
    private int $maxSizeKB = 5120;

    /**
     * Upload une image
     *
     * @param UploadedFile $file Fichier uploadé
     * @param string $folder Dossier de destination (portfolio, companies, formations, etc.)
     * @param string|null $prefix Préfixe pour le nom du fichier
     * @param bool $isDocument Si true, autorise les PDF
     * @return array ['success' => bool, 'url' => string|null, 'error' => string|null]
     */
    public function upload(UploadedFile $file, string $folder, ?string $prefix = null, bool $isDocument = false): array
    {
        // Vérifier le dossier
        if (!in_array($folder, $this->allowedFolders)) {
            return [
                'success' => false,
                'url' => null,
                'error' => "Dossier non autorisé: {$folder}"
            ];
        }

        // Vérifier l'extension
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedExt = $isDocument ? $this->allowedDocumentExtensions : $this->allowedExtensions;
        
        if (!in_array($extension, $allowedExt)) {
            return [
                'success' => false,
                'url' => null,
                'error' => "Extension non autorisée: {$extension}. Extensions autorisées: " . implode(', ', $allowedExt)
            ];
        }

        // Vérifier la taille
        if ($file->getSize() > $this->maxSizeKB * 1024) {
            return [
                'success' => false,
                'url' => null,
                'error' => "Fichier trop volumineux. Taille max: " . ($this->maxSizeKB / 1024) . "MB"
            ];
        }

        // Générer le nom du fichier
        $filename = $this->generateFilename($prefix, $extension);

        try {
            // Vérifier que le disque R2 existe et est configuré
            if (!config('filesystems.disks.r2')) {
                throw new \Exception('Disque R2 non configuré. Vérifiez filesystems.php');
            }

            // Stocker le fichier sur Cloudflare R2
            $path = $file->storeAs($folder, $filename, 'r2');
            
            // Récupérer l'URL publique du fichier
            $url = Storage::disk('r2')->url($path);

            return [
                'success' => true,
                'url' => $url,
                'filename' => $filename,
                'path' => $path,
                'error' => null
            ];
        } catch (\Exception $e) {
            \Log::error('ImageUpload Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'folder' => $folder,
                'filename' => $filename ?? 'unknown'
            ]);
            
            return [
                'success' => false,
                'url' => null,
                'error' => "Erreur lors de l'upload: " . $e->getMessage()
            ];
        }
    }

    /**
     * Supprime une image
     *
     * @param string $url URL de l'image (URL complète depuis Cloudflare R2)
     * @return array ['success' => bool, 'error' => string|null]
     */
    public function delete(string $url): array
    {
        try {
            // Extraire le chemin du fichier depuis l'URL R2
            // L'URL est au format: https://[bucket-id].r2.cloudflarestorage.com/[path]
            $publicUrl = env('CLOUDFLARE_R2_PUBLIC_URL', '');
            
            if (empty($publicUrl)) {
                return [
                    'success' => false,
                    'error' => 'URL publique R2 non configurée'
                ];
            }

            // Extraire le chemin relatif de l'URL
            if (strpos($url, $publicUrl) === 0) {
                $path = str_replace($publicUrl, '', $url);
                $path = ltrim($path, '/');
            } else {
                // Si l'URL ne correspond pas, on ne peut pas supprimer
                return [
                    'success' => false,
                    'error' => 'URL invalide ou non reconnu'
                ];
            }

            // Vérifier que le fichier existe
            if (!Storage::disk('r2')->exists($path)) {
                return [
                    'success' => false,
                    'error' => 'Fichier non trouvé'
                ];
            }

            // Supprimer le fichier
            Storage::disk('r2')->delete($path);

            return [
                'success' => true,
                'error' => null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => "Erreur lors de la suppression: " . $e->getMessage()
            ];
        }
    }

    /**
     * Remplace une image (supprime l'ancienne et upload la nouvelle)
     *
     * @param UploadedFile $file Nouveau fichier
     * @param string|null $oldUrl URL de l'ancienne image
     * @param string $folder Dossier de destination
     * @param string|null $prefix Préfixe pour le nom
     * @param bool $isDocument Si true, autorise les PDF
     * @return array
     */
    public function replace(UploadedFile $file, ?string $oldUrl, string $folder, ?string $prefix = null, bool $isDocument = false): array
    {
        // Upload la nouvelle image
        $result = $this->upload($file, $folder, $prefix, $isDocument);

        if (!$result['success']) {
            return $result;
        }

        // Supprimer l'ancienne image si elle existe
        if ($oldUrl && !empty($oldUrl)) {
            $this->delete($oldUrl);
        }

        return $result;
    }

    /**
     * Génère un nom de fichier unique
     */
    private function generateFilename(?string $prefix, string $extension): string
    {
        $timestamp = now()->format('Ymd_His');
        $random = Str::random(8);
        
        if ($prefix) {
            return "{$prefix}_{$timestamp}_{$random}.{$extension}";
        }
        
        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Récupère la liste des fichiers dans un dossier
     *
     * @param string $folder Dossier
     * @return array Liste des URLs
     */
    public function listFiles(string $folder): array
    {
        if (!in_array($folder, $this->allowedFolders)) {
            return [];
        }

        try {
            $files = Storage::disk('r2')->files($folder);
            
            return array_map(function ($file) {
                return Storage::disk('r2')->url($file);
            }, $files);
        } catch (\Exception $e) {
            return [];
        }
    }
}
