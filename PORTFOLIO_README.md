# 📁 Portfolio - API REST avec Laravel

API REST complète pour la gestion d'un portfolio professionnel avec stockage d'images sur **Cloudflare R2**.

## 🚀 Fonctionnalités

- ✅ **API RESTful** pour gérer le portfolio, projets, formations, entreprises, réalisations
- ☁️ **Stockage d'images** sur Cloudflare R2 (compatible S3)
- 📖 **API Publique** pour lire les données (portfolio, projets, formations)
- 🎛️ **Dashboard API** pour gérer complètement les données (CRUD)
- 🐳 **Docker** inclus pour déploiement facile
- 💾 **SQLite** par défaut (configurable MySQL, PostgreSQL)

## 📋 Installation rapide

### Prérequis
- PHP 8.3+
- Composer
- Node.js & npm (pour Vite/Frontend)
- Docker (optionnel)

### Étapes d'installation

#### 1️⃣ Cloner et configurer
```bash
git clone <repo-url>
cd Laravel
composer install
cp .env.example .env
php artisan key:generate
```

#### 2️⃣ Configurer Cloudflare R2
Éditer `.env` et ajouter:
```env
CLOUDFLARE_R2_ACCESS_KEY_ID=d912100a938a4ab3571d165ba99b2dd6
CLOUDFLARE_R2_SECRET_ACCESS_KEY=01f5f394a3f922c89258cc69452946b034d86958c64a5445f4a901bc640cdd9a
CLOUDFLARE_R2_BUCKET=a55c6e5cc8c8a996e287abce953ac92c
CLOUDFLARE_R2_ENDPOINT=https://a55c6e5cc8c8a996e287abce953ac92c.r2.cloudflarestorage.com
CLOUDFLARE_R2_PUBLIC_URL=https://a55c6e5cc8c8a996e287abce953ac92c.r2.cloudflarestorage.com
```

#### 3️⃣ Initialiser la base de données
```bash
php artisan migrate
php artisan db:seed  # Données de test
```

#### 4️⃣ Démarrer le serveur

**Option 1: Serveur de développement Laravel**
```bash
php artisan serve
# API accessible sur http://localhost:8000/api
```

**Option 2: Avec Vite (Frontend + API)**
```bash
composer dev
# Lance API + Vite watcher
```

**Option 3: Docker**
```bash
docker-compose up --build
# API accessible sur http://localhost:10000/api
```

## 📚 Documentation

| Document | Description |
|----------|-------------|
| **[Context.MD](Context.MD)** | Documentation API complète (routes, paramètres, exemples) |
| **[CLOUDFLARE_R2_SETUP.md](CLOUDFLARE_R2_SETUP.md)** | Guide complet Cloudflare R2 |
| **[scripts/migrate-images-to-r2.php](scripts/migrate-images-to-r2.php)** | Migration optionnelle des images locales vers R2 |

## 🔗 Endpoints API principaux

### 🌐 API Publique (Lecture seule)
```
GET  /api/ping                      Statut du serveur
GET  /api/portfolio                 Infos du portfolio
GET  /api/projects                  Liste des projets
GET  /api/projects/{id}             Détail d'un projet
GET  /api/realisations              Liste des réalisations
GET  /api/realisations/{id}         Détail d'une réalisation
GET  /api/companies                 Liste des entreprises
GET  /api/stages                    Liste des stages
GET  /api/formations                Liste des formations
GET  /api/competences               Liste des compétences
GET  /api/messages                  Messages importants actifs
GET  /api/all                       Toutes les données en une requête
```

### 🎛️ Dashboard API (CRUD complet)
Préfixe: `/api/dashboard/`

```
Stats
GET  /stats                         Statistiques globales

Portfolio
GET  /portfolio                     Récupérer les infos
PUT  /portfolio                     Mettre à jour

Projets
GET  /projects                      Liste avec filtres
GET  /projects/{id}                 Détail
POST /projects                      Créer
PUT  /projects/{id}                 Modifier
DELETE /projects/{id}               Supprimer

Images
POST /images/projects/{projectId}                Upload image
PUT  /images/projects/image/{imageId}            Modifier
DELETE /images/projects/image/{imageId}          Supprimer
PUT  /images/projects/{projectId}/order          Réordonner

... (voir Context.MD pour tous les endpoints)
```

### 📤 Upload d'images
```
POST /api/images/portfolio                      Upload photo profil
POST /api/images/companies/{companyId}          Upload logo
POST /api/images/formations/{formationId}/logo  Upload logo formation
POST /api/images/projects/{projectId}           Upload image projet
POST /api/images/realisations/{realisationId}   Upload image réalisation
```

**Exemple:**
```bash
curl -X POST http://localhost:8000/api/images/portfolio \
  -F "image=@photo.jpg"

# Response:
# {
#   "success": true,
#   "url": "https://a55c6e5cc8c8a996e287abce953ac92c.r2.cloudflarestorage.com/portfolio/photo_..._abc12345.jpg",
#   "filename": "photo_..._abc12345.jpg",
#   "path": "portfolio/photo_..._abc12345.jpg"
# }
```

## 🐳 Docker

### Déploiement avec Docker Compose

```bash
# Build et démarrage
docker-compose up --build

# Arrêt
docker-compose down

# Logs
docker-compose logs -f app
```

**Ports:**
- API: `http://localhost:10000`
- Database: SQLite dans `/storage/database.sqlite`

### Dockerfile

- **Frontend build** : Node.js + Vite (stage 1)
- **PHP Runtime** : PHP 8.3-CLI Alpine (stage 2)
- **Extensions** : GD, PDO, Mbstring, Zip, Xml, BCMath

## 💾 Configuration Base de Données

### SQLite (Par défaut)
```env
DB_CONNECTION=sqlite
# Database fichier: database/database.sqlite
```

### MySQL
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio
DB_USERNAME=root
DB_PASSWORD=secret
```

### PostgreSQL
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=portfolio
DB_USERNAME=postgres
DB_PASSWORD=secret
```

## 📁 Structure du projet

```
app/
├── Http/
│   └── Controllers/
│       ├── ApiController.php              Endpoints publics
│       ├── DashboardApiController.php     CRUD dashboard
│       └── ImageUploadController.php      Upload images
├── Models/
│   ├── Portfolio.php
│   ├── Company.php
│   ├── Project.php
│   ├── ProjectImage.php
│   ├── Realisation.php
│   └── ...
└── Services/
    └── ImageUploadService.php             Service upload R2

config/
├── app.php
├── database.php
├── filesystems.php                  Disques (public, r2)
└── ...

database/
├── migrations/                      Schémas
│   ├── *_create_portfolios_table.php
│   ├── *_create_projects_table.php
│   └── ...
└── seeders/                         Données de test
    ├── PortfolioSeeder.php
    └── ProjectSeeder.php

resources/
├── css/app.css                      Styles Vite
├── js/app.js                        Scripts Vite
└── views/                           Vues (optionnel)

routes/
├── api.php                          Routes API
└── web.php                          Routes Web

storage/
├── app/
│   ├── private/                     Fichiers privés
│   └── public/                      Fichiers publics (symlink)
├── framework/
│   ├── cache/
│   ├── sessions/
│   └── views/
└── logs/

scripts/
└── migrate-images-to-r2.php         Migration optionnelle
```

## 🔒 Sécurité

✅ **Best Practices implémentées:**
- Variables d'environnement pour credentials
- `.env` dans `.gitignore`
- Validation des entrées (FormRequests)
- Contrôle de taille fichiers (5MB images, 10MB diplômes)
- Formats autorisés: jpg, jpeg, png, gif, webp, svg, pdf
- CORS configuré
- Symlink vers `/storage/public` pour les fichiers publics

⚠️ **À faire:**
- Authentification API (middleware)
- Rate limiting
- HTTPS en production
- Backup R2 réguliers

## 🧪 Tests

```bash
# Tests unitaires
composer test

# PHPUnit
php artisan test

# Avec coverage
php artisan test --coverage
```

## 📊 Modèles & Relations

```
Portfolio (1)
  ├── Companies (many) 
  │   └── Stages (many)
  ├── Formations (many)
  ├── Projects (many)
  │   └── ProjectImages (many)
  ├── Realisations (many)
  │   └── RealisationImages (many)
  ├── Competences (many) [pivot]
  └── ImportantMessages (many)

Company
  ├── Stages (many)
  ├── Projects (many)
  └── Realisations (many)

Project
  ├── ProjectImages (many)
  ├── ProjectTags (many)
  └── Competences (many) [pivot]

Realisation
  ├── RealisationImages (many)
  ├── RealisationTags (many)
  └── Competences (many) [pivot]
```

## 🚀 Déploiement

### Production

1. **Cloner le repo**
   ```bash
   git clone <repo> portfolio
   cd portfolio
   ```

2. **Installer les dépendances**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install --production
   npm run build
   ```

3. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   # Éditer .env avec les variables de production
   php artisan key:generate
   ```

4. **Préparer la base de données**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

5. **Permissions**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

6. **Optimiser**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

7. **Lancer avec Supervisor**
   ```
   # Voir configuration serveur (Nginx, Apache, etc.)
   ```

### Docker Production

```bash
docker build -t portfolio-api .
docker run -d \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -e CLOUDFLARE_R2_ACCESS_KEY_ID=... \
  -e CLOUDFLARE_R2_SECRET_ACCESS_KEY=... \
  -p 8000:10000 \
  portfolio-api
```

## 📈 Performance

- ✅ Caching (Laravel cache, database queries)
- ✅ Pagination (30 items défaut)
- ✅ Eager loading (relationships)
- ✅ Compression R2 (images optimisées)
- ✅ CDN Cloudflare (via R2 public URL)

## 🐛 Dépannage

### Erreur: "CLOUDFLARE_R2_PUBLIC_URL non configurée"
**Solution:** Vérifier `.env` et les variables R2

### Erreur: "Fichier non trouvé" lors suppression
**Cause:** URL mal formée ou fichier supprimé de R2 manuellement
**Solution:** Vérifier l'URL dans la base de données

### Erreur: "Permission denied storage"
```bash
chmod -R 775 storage bootstrap/cache
```

### Erreur: "Class not found"
```bash
composer dump-autoload
php artisan cache:clear
```

## 📞 Support & Contribution

- 📖 **Issues:** Ouvrir une issue GitHub
- 📝 **PR:** Contributions bienvenues
- 💬 **Discussion:** Forum ou Discord

## 📄 Licence

MIT License - Voir LICENSE.md pour détails

---

**Version:** 1.0.0  
**Dernière mise à jour:** Mars 2025  
**Status:** ✅ Production Ready  
**Stockage:** Cloudflare R2 🪣
