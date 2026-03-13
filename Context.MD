# 📚 Documentation Complète API Portfolio

**Version:** 2.0 (Mars 2026)  
**Base URL:** `http://localhost:8000/api`  
**Base URL Render:** `https://portfolio-mlb3.onrender.com/api`

---

## 🗂️ Table des Matières

1. [Informations Générales](#informations-générales)
2. [Routes Publiques](#routes-publiques)
3. [Routes Dashboard - CRUD](#routes-dashboard---crud)
4. [Routes Images](#routes-images)
5. [Routes Legacy](#routes-legacy)
6. [Erreurs & Statuts HTTP](#erreurs--statuts-http)
7. [Infrastructure](#infrastructure)

---

## Informations Générales

### Structure de Réponse Standard

#### ✅ Réponse réussite (Créée)
```json
{
  "success": true,
  "message": "Action réalisée avec succès",
  "data": { /* objet ou array */ }
}
// HTTP 201
```

#### ✅ Réponse réussite (OK)
```json
{
  "success": true,
  "message": "Données récupérées",
  "data": { /* objet ou array */ }
}
// HTTP 200
```

#### ❌ Erreur
```json
{
  "success": false,
  "error": "Description de l'erreur"
}
// HTTP 400/404/422/500 selon le cas
```

### Codes HTTP Utilisés

| Code | Signification | Cas d'usage |
|------|---------------|-----------|
| **200** | OK | GET réussi, UPDATE réussi |
| **201** | Créé | POST réussi, Image uploadée |
| **400** | Mauvaise requête | Validation échouée, upload échoué |
| **404** | Non trouvé | Ressource inexistante |
| **422** | Donnée invalide | Champs obligatoires manquants |
| **500** | Erreur serveur | Erreur base de données, erreur R2 |

### Authentification

Actuellement, l'API **n'a pas d'authentification**. Toutes les routes sont publiques.

⚠️ **À venir:** Middleware d'authentification pour les routes dashboard.

---

## 🌐 Routes Publiques

Ces routes sont accessibles **sans authentification** et retournent des données en **lecture seule**.

### Health Check

#### **POST `/ping`**
Teste si le serveur est actif.

```bash
curl -X GET http://localhost:8000/api/ping
```

**Réponse:**
```json
{
  "status": "ok",
  "message": "pong",
  "timestamp": "2026-03-13T16:00:00Z"
}
```

---

### Portfolio

#### **GET `/portfolio`**
Récupère les informations du portfolio.

```bash
curl http://localhost:8000/api/portfolio
```

**Réponse:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "bio": "Développeur Full Stack",
    "photo_url": "https://a55c6e5cc8c8a996e287abce953ac92c.r2.cloudflarestorage.com/portfolio/photo.jpg",
    "email": "john@example.com",
    "phone": "+33612345678",
    "location": "Paris, France",
    "linkedin_url": "https://linkedin.com/in/johndoe",
    "github_url": "https://github.com/johndoe",
    "year_start": 2020,
    "year_end": 2025,
    "contact_message": "N'hésitez pas à me contacter!"
  }
}
```

---

### Entreprises

#### **GET `/companies`**
Liste toutes les entreprises.

```bash
curl http://localhost:8000/api/companies
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Entreprise ABC",
      "sector": "Tech",
      "description": "Description...",
      "location": "Paris",
      "photo_url": "https://..../company_logo.png",
      "created_at": "2025-03-04T10:00:00Z"
    }
  ]
}
```

---

### Stages

#### **GET `/stages`**
Liste tous les stages.

```bash
curl http://localhost:8000/api/stages
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "company_id": 1,
      "company": { "id": 1, "name": "Entreprise ABC" },
      "start_date": "2024-01-15",
      "end_date": "2024-06-15",
      "duration": "6 mois",
      "role": "Développeur Full Stack",
      "description": "Stage chez...",
      "competences": [
        { "id": 1, "code": "C1", "label": "Compétence" }
      ]
    }
  ]
}
```

---

### Projets

#### **GET `/projects`**
Liste tous les projets.

```bash
curl http://localhost:8000/api/projects
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "company_id": 1,
      "title": "Application Mobile",
      "type": "Flutter",
      "description": "Description courte",
      "year": "2025",
      "tags": ["Flutter", "API", "Firebase"]
    }
  ]
}
```

#### **GET `/projects/{id}`**
Récupère les détails d'un projet.

```bash
curl http://localhost:8000/api/projects/5
```

**Réponse:**
```json
{
  "success": true,
  "data": {
    "id": 5,
    "company_id": 3,
    "title": "Application Gestion de congé",
    "type": "Application C#",
    "description": "Permet au praticiens de prendre des congés...",
    "long_description": "## Application C# – Gestion des congés\n\nDans le cadre d'un projet...",
    "year": "2025",
    "tags": [
      { "id": 32, "tag": "C#" },
      { "id": 33, "tag": "MySQL" },
      { "id": 34, "tag": "Git" }
    ],
    "images": [
      {
        "id": 10,
        "image_url": "https://.../project_5_image1.jpg",
        "caption": "Interface principale",
        "description": "Vue d'ensemble...",
        "order": 1
      }
    ],
    "competences": [...]
  }
}
```

---

### Réalisations

#### **GET `/realisations`**
Liste toutes les réalisations.

```bash
curl http://localhost:8000/api/realisations
```

#### **GET `/realisations/{id}`**
Détail d'une réalisation.

```bash
curl http://localhost:8000/api/realisations/3
```

**Structure identique à `/projects/{id}`**

---

### Compétences

#### **GET `/competences`**
Liste toutes les compétences.

```bash
curl http://localhost:8000/api/competences
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "code": "C1",
      "label": "Développer une application sécurisée",
      "bloc": "Bloc 1",
      "description": "Description détaillée"
    }
  ]
}
```

---

### Formations

#### **GET `/formations`**
Liste toutes les formations (diplômes, parcours scolaire).

```bash
curl http://localhost:8000/api/formations
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "BTS SIO option SLAM",
      "school": "Lycée Exemple",
      "location": "Paris, France",
      "degree_type": "BTS",
      "start_date": "2023-09-01",
      "end_date": "2025-06-30",
      "is_current": false,
      "description": "Formation en développement d'applications",
      "logo_url": "https://.../formation_1_logo.png",
      "diploma_url": "https://.../diploma.pdf",
      "order": 0,
      "period": "2023 — 2025"
    }
  ]
}
```

---

### Messages Importants

#### **GET `/messages`**
Liste les messages importants **actifs uniquement**.

```bash
curl http://localhost:8000/api/messages
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Offre de stage",
      "message": "Actuellement en recherche de stage...",
      "is_active": true,
      "priority": "high",
      "created_at": "2026-03-10T10:00:00Z"
    }
  ]
}
```

---

### Données Complètes

#### **GET `/all`**
Récupère **TOUTES** les données en une seule requête.

```bash
curl http://localhost:8000/api/all
```

⚠️ **Attention:** Réponse volumineuse! À utiliser avec modération.

---

## 📊 Routes Dashboard - CRUD

**Préfixe:** `/dashboard`

Routes complètes de gestion (Create, Read, Update, Delete).

### 📈 Stats

#### **GET `/dashboard/stats`**

Récupère les statistiques globales du portfolio.

```bash
curl http://localhost:8000/api/dashboard/stats
```

**Réponse:**
```json
{
  "success": true,
  "data": {
    "companies_count": 5,
    "stages_count": 3,
    "formations_count": 4,
    "projects_count": 12,
    "realisations_count": 8,
    "competences_count": 15,
    "messages_count": 2,
    "active_messages_count": 1,
    "recent_projects": [...],
    "recent_realisations": [...]
  }
}
```

---

### 👤 Portfolio (Dashboard)

#### **GET `/dashboard/portfolio`**
Récupère les infos complètes du portfolio.

```bash
curl http://localhost:8000/api/dashboard/portfolio
```

#### **PUT `/dashboard/portfolio`**
Met à jour le portfolio.

```bash
curl -X PUT http://localhost:8000/api/dashboard/portfolio \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "bio": "Développeur Full Stack passionné",
    "email": "john@example.com",
    "phone": "+33612345678",
    "location": "Paris, France",
    "linkedin_url": "https://linkedin.com/in/johndoe",
    "github_url": "https://github.com/johndoe",
    "year_start": 2020,
    "year_end": 2025,
    "contact_message": "Contactez-moi!"
  }'
```

**Champs (tous optionnels):**
- `first_name` (string, max 255)
- `last_name` (string, max 255)
- `bio` (string, max 5000)
- `email` (email, max 255)
- `phone` (string, max 50)
- `location` (string, max 255)
- `linkedin_url` (url, max 500)
- `github_url` (url, max 500)
- `year_start` (integer)
- `year_end` (integer)
- `contact_message` (string, max 2000)

---

### 🏢 Companies

#### **GET `/dashboard/companies`**
Liste toutes les entreprises.

```bash
curl http://localhost:8000/api/dashboard/companies
```

#### **GET `/dashboard/companies/{id}`**
Détail d'une entreprise.

```bash
curl http://localhost:8000/api/dashboard/companies/1
```

#### **POST `/dashboard/companies`**
Crée une entreprise.

```bash
curl -X POST http://localhost:8000/api/dashboard/companies \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Nouvelle Entreprise",
    "sector": "Digital",
    "description": "Description...",
    "location": "Lyon, France"
  }'
```

**Champs:**
- `name` (string, REQUIS, max 255)
- `sector` (string, max 255)
- `description` (string, max 5000)
- `location` (string, max 255)
- `photo_url` (url, max 500)

#### **PUT `/dashboard/companies/{id}`**
Met à jour une entreprise.

```bash
curl -X PUT http://localhost:8000/api/dashboard/companies/1 \
  -H "Content-Type: application/json" \
  -d '{ "name": "Nom mis à jour", ... }'
```

#### **DELETE `/dashboard/companies/{id}`**
Supprime une entreprise.

```bash
curl -X DELETE http://localhost:8000/api/dashboard/companies/1
```

---

### 📅 Stages

#### **GET `/dashboard/stages`**
Liste tous les stages.

#### **GET `/dashboard/stages/{id}`**
Détail d'un stage.

#### **POST `/dashboard/stages`**
Crée un stage.

```bash
curl -X POST http://localhost:8000/api/dashboard/stages \
  -H "Content-Type: application/json" \
  -d '{
    "company_id": 1,
    "start_date": "2024-01-15",
    "end_date": "2024-06-15",
    "duration": "6 mois",
    "role": "Développeur Full Stack",
    "description": "Description du stage",
    "competence_ids": [1, 2, 3]
  }'
```

**Champs:**
- `company_id` (integer, REQUIS)
- `start_date` (date, REQUIS, format: YYYY-MM-DD)
- `end_date` (date, REQUIS, format: YYYY-MM-DD)
- `duration` (string)
- `role` (string, REQUIS)
- `description` (string)
- `competence_ids` (array)

#### **PUT `/dashboard/stages/{id}`**
Met à jour un stage.

#### **DELETE `/dashboard/stages/{id}`**
Supprime un stage.

---

### 🎓 Formations

#### **GET `/dashboard/formations`**
Liste toutes les formations.

#### **GET `/dashboard/formations/{id}`**
Détail d'une formation.

#### **POST `/dashboard/formations`**
Crée une formation.

```bash
curl -X POST http://localhost:8000/api/dashboard/formations \
  -H "Content-Type: application/json" \
  -d '{
    "title": "BTS SIO option SLAM",
    "school": "Lycée Exemple",
    "location": "Paris, France",
    "degree_type": "BTS",
    "start_date": "2023-09-01",
    "end_date": "2025-06-30",
    "is_current": false,
    "description": "Formation en développement d'applications",
    "order": 0
  }'
```

**Champs:**
- `title` (string, REQUIS, max 255)
- `school` (string, REQUIS, max 255)
- `location` (string)
- `degree_type` (string) - BTS, Licence, Master, BAC, etc.
- `start_date` (date, REQUIS, YYYY-MM-DD)
- `end_date` (date, nullable, YYYY-MM-DD)
- `is_current` (boolean)
- `description` (string, max 5000)
- `logo_url` (url)
- `diploma_url` (url) - PDF ou image
- `order` (integer)

#### **PUT `/dashboard/formations/{id}`**
Met à jour une formation.

#### **DELETE `/dashboard/formations/{id}`**
Supprime une formation.

---

### 🎯 Competences

#### **GET `/dashboard/competences`**
Liste toutes les compétences.

#### **GET `/dashboard/competences/{id}`**
Détail d'une compétence avec projets/stages associés.

#### **POST `/dashboard/competences`**
Crée une compétence.

```bash
curl -X POST http://localhost:8000/api/dashboard/competences \
  -H "Content-Type: application/json" \
  -d '{
    "code": "C1",
    "label": "Développer une application sécurisée",
    "bloc": "Bloc 1",
    "description": "Description détaillée..."
  }'
```

**Champs:**
- `code` (string, REQUIS, max 50)
- `label` (string, REQUIS, max 500)
- `bloc` (string)
- `description` (string, max 5000)

#### **PUT `/dashboard/competences/{id}`**
Met à jour une compétence.

#### **DELETE `/dashboard/competences/{id}`**
Supprime une compétence.

---

### 📋 Projects

#### **GET `/dashboard/projects`**
Liste tous les projets.

#### **GET `/dashboard/projects/{id}`**
Détail d'un projet.

#### **POST `/dashboard/projects`**
Crée un projet.

```bash
curl -X POST http://localhost:8000/api/dashboard/projects \
  -H "Content-Type: application/json" \
  -d '{
    "company_id": 3,
    "title": "Application Note",
    "type": "Flutter",
    "description": "Description courte",
    "long_description": "## Description longue markdown...",
    "year": "2025",
    "tag_list": ["Flutter", "API", "Firebase"],
    "competence_ids": [1, 2]
  }'
```

**Champs:**
- `company_id` (integer, REQUIS)
- `title` (string, REQUIS)
- `type` (string)
- `description` (string, max 5000)
- `long_description` (string, max 10000)
- `year` (string/integer)
- `tag_list` (array de strings)
- `competence_ids` (array d'integers)

#### **PUT `/dashboard/projects/{id}`**
Met à jour un projet.

#### **DELETE `/dashboard/projects/{id}`**
Supprime un projet.

---

### 📺 Realisations

#### **GET `/dashboard/realisations`**
Liste toutes les réalisations.

#### **GET `/dashboard/realisations/{id}`**
Détail d'une réalisation.

#### **POST `/dashboard/realisations`**
Crée une réalisation.

```bash
curl -X POST http://localhost:8000/api/dashboard/realisations \
  -H "Content-Type: application/json" \
  -d '{
    "company_id": 1,
    "type": "Web Application",
    "title": "Gestion des congés",
    "description": "Description courte",
    "long_description": "Description longue markdown...",
    "tag_list": ["Laravel", "MySQL"],
    "competence_ids": [1, 2]
  }'
```

**Champs:** (identiques à Projects)

#### **PUT `/dashboard/realisations/{id}`**
Met à jour une réalisation.

#### **DELETE `/dashboard/realisations/{id}`**
Supprime une réalisation.

---

### 📢 Messages Importants

#### **GET `/dashboard/messages`**
Liste tous les messages (actifs et inactifs).

#### **GET `/dashboard/messages/{id}`**
Détail d'un message.

#### **POST `/dashboard/messages`**
Crée un message.

```bash
curl -X POST http://localhost:8000/api/dashboard/messages \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Offre de stage",
    "message": "Actuellement en recherche de stage...",
    "is_active": true,
    "priority": "high"
  }'
```

**Champs:**
- `title` (string, REQUIS)
- `message` (string, REQUIS, max 5000)
- `is_active` (boolean, défaut: true)
- `priority` (string) - low, medium, high

#### **PUT `/dashboard/messages/{id}`**
Met à jour un message.

#### **PATCH `/dashboard/messages/{id}/toggle`**
Active/désactive un message.

```bash
curl -X PATCH http://localhost:8000/api/dashboard/messages/1/toggle
```

#### **DELETE `/dashboard/messages/{id}`**
Supprime un message.

---

## 🖼️ Routes Images

**Préfixe:** `/images`

Gestion complète des images (upload, modification, suppression).

### 📸 Portfolio Photo

#### **POST `/images/portfolio/`**
Upload la photo du portfolio.

```bash
curl -X POST http://localhost:8000/api/images/portfolio/ \
  -F "image=@photo.jpg"
```

**Réponse:**
```json
{
  "success": true,
  "message": "Photo du portfolio mise à jour avec succès",
  "data": {
    "id": 1,
    "photo_url": "https://.../portfolio/photo_abc123.jpg",
    "created_at": "2026-03-13T16:00:00Z"
  }
}
```

#### **PUT `/images/portfolio/`**
Remplace la photo du portfolio.

```bash
curl -X PUT http://localhost:8000/api/images/portfolio/ \
  -F "image=@new_photo.jpg"
```

#### **DELETE `/images/portfolio/`**
Supprime la photo du portfolio.

```bash
curl -X DELETE http://localhost:8000/api/images/portfolio/
```

---

### 🏢 Company Logo

#### **POST `/images/companies/{companyId}`**
Upload le logo d'une entreprise.

```bash
curl -X POST http://localhost:8000/api/images/companies/1 \
  -F "image=@logo.png"
```

#### **DELETE `/images/companies/{companyId}`**
Supprime le logo d'une entreprise.

---

### 🎓 Formation Logo & Diploma

#### **POST `/images/formations/{formationId}/logo/`**
Upload le logo d'une formation.

```bash
curl -X POST http://localhost:8000/api/images/formations/1/logo/ \
  -F "image=@school_logo.png"
```

#### **PUT `/images/formations/{formationId}/logo/`**
Remplace le logo.

#### **DELETE `/images/formations/{formationId}/logo/`**
Supprime le logo.

#### **POST `/images/formations/{formationId}/diploma/`**
Upload le diplôme (PDF ou image).

```bash
curl -X POST http://localhost:8000/api/images/formations/1/diploma/ \
  -F "file=@diploma.pdf"
```

#### **PUT `/images/formations/{formationId}/diploma/`**
Remplace le diplôme.

#### **DELETE `/images/formations/{formationId}/diploma/`**
Supprime le diplôme.

---

### 📷 Project Images (Gallery)

#### **GET `/images/projects/{projectId}`**
Liste toutes les images d'un projet.

```bash
curl http://localhost:8000/api/images/projects/5
```

**Réponse:**
```json
{
  "success": true,
  "message": "Images du projet récupérées",
  "data": [
    {
      "id": 10,
      "project_id": 5,
      "image_url": "https://.../project_5_img1.jpg",
      "caption": "Interface principale",
      "description": "Vue d'ensemble de l'application",
      "order": 1,
      "created_at": "2026-03-13T16:00:00Z"
    }
  ]
}
```

#### **POST `/images/projects/{projectId}`**
Ajoute une image au projet.

```bash
curl -X POST http://localhost:8000/api/images/projects/5 \
  -F "image=@screenshot.jpg" \
  -F "caption=Interface principale" \
  -F "description=Vue d'ensemble de l'application"
```

#### **GET `/images/projects/image/{imageId}`**
Récupère les détails d'une image spécifique.

```bash
curl http://localhost:8000/api/images/projects/image/10
```

#### **PUT `/images/projects/image/{imageId}`**
Met à jour une image (fichier ET/OU caption ET/OU description).

```bash
curl -X PUT http://localhost:8000/api/images/projects/image/10 \
  -F "image=@new_screenshot.jpg" \
  -F "caption=Nouvelle caption"
```

#### **DELETE `/images/projects/image/{imageId}`**
Supprime une image.

```bash
curl -X DELETE http://localhost:8000/api/images/projects/image/10
```

#### **DELETE `/images/projects/{projectId}`**
Supprime **plusieurs images** à la fois (batch delete).

```bash
curl -X DELETE http://localhost:8000/api/images/projects/5 \
  -H "Content-Type: application/json" \
  -d '{
    "image_ids": [10, 11, 12]
  }'
```

**Réponse:**
```json
{
  "success": true,
  "message": "Images supprimées avec succès",
  "data": {
    "deleted_count": 3
  }
}
```

#### **PUT `/images/projects/{projectId}/order`**
Met à jour l'ordre des images.

```bash
curl -X PUT http://localhost:8000/api/images/projects/5/order \
  -H "Content-Type: application/json" \
  -d '{
    "images": [
      {"id": 10, "order": 1},
      {"id": 11, "order": 2},
      {"id": 12, "order": 3}
    ]
  }'
```

---

### 📺 Realisation Images (Gallery)

#### **GET `/images/realisations/{realisationId}`**
Liste toutes les images d'une réalisation.

#### **POST `/images/realisations/{realisationId}`**
Ajoute une image à la réalisation.

#### **GET `/images/realisations/image/{imageId}`**
Récupère une image spécifique.

#### **PUT `/images/realisations/image/{imageId}`**
Met à jour une image.

#### **DELETE `/images/realisations/image/{imageId}`**
Supprime une image.

#### **DELETE `/images/realisations/{realisationId}`**
Supprime plusieurs images (batch delete).

```bash
curl -X DELETE http://localhost:8000/api/images/realisations/3 \
  -H "Content-Type: application/json" \
  -d '{ "image_ids": [1, 2] }'
```

#### **PUT `/images/realisations/{realisationId}/order`**
Met à jour l'ordre des images.

---

## 🔄 Routes Legacy

Routes anciennes mais toujours supportées pour la compatibilité.

#### **PUT `/projects/{id}/description`**
Met à jour la description longue d'un projet.

```bash
curl -X PUT http://localhost:8000/api/projects/5/description \
  -H "Content-Type: application/json" \
  -d '{ "long_description": "## Nouvelle description..." }'
```

#### **PUT `/realisations/{id}/description`**
Met à jour la description longue d'une réalisation.

---

## ⚠️ Erreurs & Statuts HTTP

### Codes HTTP Standard

| Code | Cas d'usage |
|------|------------|
| **200** | GET réussi, PUT réussi |
| **201** | POST réussi, Image uploadée |
| **400** | Erreur validation, upload échoué |
| **404** | Ressource non trouvée |
| **422** | Données invalides |
| **500** | Erreur serveur (BD, R2, etc.) |

### Exemples d'Erreurs

#### Ressource non trouvée
```json
{
  "success": false,
  "error": "Projet non trouvé"
}
```
**HTTP 404**

#### Validation échouée
```json
{
  "success": false,
  "error": "Aucune donnée à mettre à jour"
}
```
**HTTP 422**

#### Erreur upload
```json
{
  "success": false,
  "error": "Erreur lors de l'upload: File size exceeds limit"
}
```
**HTTP 400**

#### Erreur serveur
```json
{
  "success": false,
  "error": "Erreur lors de la récupération: Could not find driver (pgsql)"
}
```
**HTTP 500**

---

## 🏗️ Infrastructure

### Base de Données

- **Type:** PostgreSQL (Supabase)
- **Host:** `aws-1-eu-west-1.pooler.supabase.com:6543`
- **Database:** `postgres`
- **Driver PHP:** `pdo_pgsql` ✅ Activé

### Stockage d'Images

- **Provenance:** Cloudflare R2
- **Bucket:** `portfolio`
- **Account ID:** `a55c6e5cc8c8a996e287abce953ac92c`
- **Endpoint:** `https://a55c6e5cc8c8a996e287abce953ac92c.r2.cloudflarestorage.com`
- **Format URL:** `https://[bucket].r2.cloudflarestorage.com/[folder]/[filename]`

### Déploiement

- **Plateforme:** Render.com
- **URL Prod:** `https://portfolio-mlb3.onrender.com`
- **Base URL API Prod:** `https://portfolio-mlb3.onrender.com/api`
- **Région:** Europe

### Framework & Dépendances

- **Framework:** Laravel 12.x
- **PHP:** 8.3.26
- **Frontend:** Vite + Tailwind CSS
- **Storage:** league/flysystem-aws-s3-v3 v3.32.0
- **AWS SDK:** aws/aws-sdk-php v3.373.1

---

## 📝 Notes Importantes

### Limites de Fichiers

- **Images:** Max 5 MB, formats acceptés: JPEG, PNG, JPG, GIF, WebP
- **Diplômes:** Max 10 MB, formats acceptés: PDF, JPEG, PNG, JPG
- **SVG:** Accepté pour les logos d'entreprise

### Ordre des Images

Pour les galeries (projets, réalisations), les images sont automatiquement classées par le champ `order`. Utilisez `PUT /order` pour réorganiser.

### Suppression en Cascade

- Supprimer une **entreprise** échoue si elle a des stages/projets/réalisations associés
- Supprimer un **stage/projet/réalisation** supprime automatiquement ses images

### Authentification Future

Actuellement aucune authentification. À venir: système de tokens pour les routes dashboard.

---

**Dernière mise à jour:** 13 Mars 2026  
**Auteur:** Portfolio API Team
