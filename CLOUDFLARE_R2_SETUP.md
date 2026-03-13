# Configuration Cloudflare R2 🪣

## Vue d'ensemble

Ce projet utilise **Cloudflare R2** pour stocker les images uploadées via l'API. R2 est une solution de stockage d'objets compatible avec l'API Amazon S3.

## Prérequis

- Laravel 12.x
- Composer
- `aws/aws-sdk-php` (déjà inclus via Laravel)

## Configuration

### 1. Variables d'environnement

Ajouter les variables suivantes au fichier `.env`:

```env
CLOUDFLARE_R2_ACCESS_KEY_ID=d912100a938a4ab3571d165ba99b2dd6
CLOUDFLARE_R2_SECRET_ACCESS_KEY=01f5f394a3f922c89258cc69452946b034d86958c64a5445f4a901bc640cdd9a
CLOUDFLARE_R2_BUCKET=a55c6e5cc8c8a996e287abce953ac92c
CLOUDFLARE_R2_ENDPOINT=https://a55c6e5cc8c8a996e287abce953ac92c.r2.cloudflarestorage.com
CLOUDFLARE_R2_PUBLIC_URL=https://a55c6e5cc8c8a996e287abce953ac92c.r2.cloudflarestorage.com
```

### 2. Configuration automatique

La configuration du disque R2 est déjà présente dans `config/filesystems.php`. Aucune modification supplémentaire n'est nécessaire.

## Structure des dossiers R2

Les images sont organisées par catégorie:

```
r2://
├── portfolio/          # Photo de profil
├── companies/          # Logos des entreprises
├── formations/         # Logos des écoles
├── diplomas/           # Diplômes (PDF/images)
├── projects/           # Images des projets
├── realisations/       # Images des réalisations
└── messages/           # Images des messages
```

## Routes API

### Upload d'image

Toutes les routes d'upload sont préfixées par `/api/images`:

- `POST /api/images/portfolio` - Upload photo profil
- `POST /api/images/companies/{companyId}` - Upload logo entreprise
- `POST /api/images/formations/{formationId}/logo` - Upload logo formation
- `POST /api/images/formations/{formationId}/diploma` - Upload diplôme
- `POST /api/images/projects/{projectId}` - Upload image projet
- `POST /api/images/realisations/{realisationId}` - Upload image réalisation

### Exemple d'upload

```bash
curl -X POST http://localhost:8000/api/images/portfolio \
  -F "image=@/path/to/image.jpg"
```

**Réponse (succès):**

```json
{
    "success": true,
    "url": "https://a55c6e5cc8c8a996e287abce953ac92c.r2.cloudflarestorage.com/portfolio/photo_20250311_143052_abc12345.jpg",
    "filename": "photo_20250311_143052_abc12345.jpg",
    "path": "portfolio/photo_20250311_143052_abc12345.jpg"
}
```

## Suppression d'image

Les images sont supprimées automatiquement lors d'une mise à jour ou manuellement via les routes DELETE.

Le service `ImageUploadService` gère:
- Extraction du chemin de l'URL publique
- Vérification de l'existence du fichier
- Suppression sur R2

## Sécurité

⚠️ **Attention:** Les credentials R2 ne doivent jamais être commitées. Assurez-vous que:

1. `.env` est dans `.gitignore`
2. `.env.example` contient des valeurs vides
3. Les credentials sont configurés au niveau du serveur/conteneur

## Dépannage

### Erreur "Fichier non trouvé"

Vérifier que:
- Les credentials R2 sont corrects
- Le bucket existe dans Cloudflare R2
- L'URL publique est correctement configurée

### Erreur d'upload

- Vérifier la taille du fichier (max 5MB par défaut)
- Vérifier le format du fichier (jpg, jpeg, png, gif, webp, svg)
- Vérifier les permissions S3 du bucket

### Erreur de suppression

Assurez-vous que l'URL est correctement formée et que le fichier existe dans le bucket.

## Docker

Le Dockerfile a été mis à jour pour:
- Ignorer la création de répertoires de stockage local pour les images
- Conserver les répertoires de stockage pour cache, logs, etc.

Pour déployer:

```bash
docker build -t portfolio-api .
docker run -e CLOUDFLARE_R2_ACCESS_KEY_ID=... -e CLOUDFLARE_R2_SECRET_ACCESS_KEY=... ...
```

## Documentation complète

Pour plus de détails, consulter [Context.MD](Context.MD) section "Images & Uploads".

## Ressources

- [Cloudflare R2 Documentation](https://developers.cloudflare.com/r2/)
- [AWS SDK pour PHP](https://docs.aws.amazon.com/sdk-for-php/)
- [Laravel Filesystem](https://laravel.com/docs/filesystem)
