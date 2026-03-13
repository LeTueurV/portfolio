<?php

/**
 * MIGRATION DES IMAGES EXISTANTES VERS CLOUDFLARE R2
 * 
 * Ce script optionnel peut être utilisé pour migrer les images stockées
 * localement vers Cloudflare R2.
 * 
 * Usage:
 * php artisan tinker
 * include(base_path('scripts/migrate-images-to-r2.php'))
 * 
 * OU via une commande Artisan personnalisée
 */

use Illuminate\Support\Facades\Storage;
use App\Models\Portfolio;
use App\Models\Company;
use App\Models\Formation;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Realisation;
use App\Models\RealisationImage;

class ImageMigrationToR2
{
    private $errors = [];
    private $migrated = 0;

    public function migrate()
    {
        echo "\n🚀 Démarrage de la migration des images vers Cloudflare R2...\n";

        try {
            $this->migratePortfolioPhoto();
            $this->migrateCompanyPhotos();
            $this->migrateFormationLogos();
            $this->migrateFormationDiplomas();
            $this->migrateProjectImages();
            $this->migrateRealisationImages();

            $this->printSummary();
        } catch (\Exception $e) {
            echo "\n❌ Erreur fatale: " . $e->getMessage() . "\n";
        }
    }

    private function migratePortfolioPhoto()
    {
        echo "\n📸 Migration des photos de profil...\n";
        
        $portfolio = Portfolio::first();
        if (!$portfolio || !$portfolio->photo_url || !$this->isLocalPath($portfolio->photo_url)) {
            echo "  ℹ️  Aucune photo locale à migrer\n";
            return;
        }

        if ($this->migrateFile($portfolio->photo_url, 'portfolio')) {
            $newUrl = Storage::disk('r2')->url($portfolio->photo_url);
            // Note: Ne pas mettre à jour automatiquement pour éviter les pertes de données
            echo "  ✅ Photo migrable. Nouvelle URL: {$newUrl}\n";
            echo "  ⚠️  Mise à jour manuelle requise dans la base de données\n";
        }
    }

    private function migrateCompanyPhotos()
    {
        echo "\n🏢 Migration des logos d'entreprises...\n";
        
        $companies = Company::whereNotNull('photo_url')->get();
        
        if ($companies->isEmpty()) {
            echo "  ℹ️  Aucune photo locale à migrer\n";
            return;
        }

        foreach ($companies as $company) {
            if ($this->isLocalPath($company->photo_url)) {
                if ($this->migrateFile($company->photo_url, 'companies')) {
                    echo "  ✅ Logo entreprise #{$company->id} migrable\n";
                }
            }
        }
    }

    private function migrateFormationLogos()
    {
        echo "\n🎓 Migration des logos de formations...\n";
        
        $formations = Formation::whereNotNull('logo_url')->get();
        
        if ($formations->isEmpty()) {
            echo "  ℹ️  Aucun logo local à migrer\n";
            return;
        }

        foreach ($formations as $formation) {
            if ($this->isLocalPath($formation->logo_url)) {
                if ($this->migrateFile($formation->logo_url, 'formations')) {
                    echo "  ✅ Logo formation #{$formation->id} migrable\n";
                }
            }
        }
    }

    private function migrateFormationDiplomas()
    {
        echo "\n📄 Migration des diplômes...\n";
        
        $formations = Formation::whereNotNull('diploma_url')->get();
        
        if ($formations->isEmpty()) {
            echo "  ℹ️  Aucun diplôme local à migrer\n";
            return;
        }

        foreach ($formations as $formation) {
            if ($this->isLocalPath($formation->diploma_url)) {
                if ($this->migrateFile($formation->diploma_url, 'diplomas')) {
                    echo "  ✅ Diplôme formation #{$formation->id} migrable\n";
                }
            }
        }
    }

    private function migrateProjectImages()
    {
        echo "\n💼 Migration des images de projets...\n";
        
        $images = ProjectImage::whereNotNull('image_url')->get();
        
        if ($images->isEmpty()) {
            echo "  ℹ️  Aucune image locale à migrer\n";
            return;
        }

        $count = 0;
        foreach ($images as $image) {
            if ($this->isLocalPath($image->image_url)) {
                if ($this->migrateFile($image->image_url, 'projects')) {
                    $count++;
                }
            }
        }
        
        echo "  ✅ {$count} images de projets migrables\n";
    }

    private function migrateRealisationImages()
    {
        echo "\n🎨 Migration des images de réalisations...\n";
        
        $images = RealisationImage::whereNotNull('image_url')->get();
        
        if ($images->isEmpty()) {
            echo "  ℹ️  Aucune image locale à migrer\n";
            return;
        }

        $count = 0;
        foreach ($images as $image) {
            if ($this->isLocalPath($image->image_url)) {
                if ($this->migrateFile($image->image_url, 'realisations')) {
                    $count++;
                }
            }
        }
        
        echo "  ✅ {$count} images de réalisations migrables\n";
    }

    private function migrateFile($localPath, $targetFolder)
    {
        try {
            // Extraire le chemin relatif du stockage local
            if (strpos($localPath, '/storage/') === 0) {
                $relativePath = str_replace('/storage/', '', $localPath);
            } else {
                return false;
            }

            // Vérifier si le fichier existe localement
            if (!Storage::disk('public')->exists($relativePath)) {
                $this->errors[] = "Fichier local non trouvé: {$relativePath}";
                return false;
            }

            // Lire le contenu du fichier
            $content = Storage::disk('public')->get($relativePath);

            // Obtenir le nom du fichier
            $filename = basename($relativePath);

            // Copier vers R2
            Storage::disk('r2')->put("{$targetFolder}/{$filename}", $content);
            
            $this->migrated++;
            return true;

        } catch (\Exception $e) {
            $this->errors[] = "Erreur lors de la migration de {$localPath}: " . $e->getMessage();
            return false;
        }
    }

    private function isLocalPath($path)
    {
        return strpos($path, '/storage/') === 0;
    }

    private function printSummary()
    {
        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📊 RÉSUMÉ DE LA MIGRATION\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "✅ Fichiers migrés: {$this->migrated}\n";
        
        if (!empty($this->errors)) {
            echo "\n❌ Erreurs rencontrées:\n";
            foreach ($this->errors as $error) {
                echo "  - {$error}\n";
            }
        }

        echo "\n⚠️  Rappel important:\n";
        echo "  - Cette migration ne met PAS à jour automatiquement la base de données\n";
        echo "  - Vous devez manuellement mettre à jour les URLs dans la BD\n";
        echo "  - Les fichiers locaux ne sont pas supprimés automatiquement\n";
        echo "\n";
    }
}

// Exécuter la migration
$migration = new ImageMigrationToR2();
$migration->migrate();
