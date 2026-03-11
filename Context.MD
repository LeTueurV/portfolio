# Documentation API Portfolio - Context.MD

## Base URL

```
http://localhost:8000/api
```

---

## Structure de réponse standard

Toutes les réponses suivent la même structure :

### Succès

```json
{
    "success": true,
    "data": { ... },
    "message": "Message optionnel"
}
```

### Erreur

```json
{
    "success": false,
    "error": "Description de l'erreur"
}
```

### Erreur de validation

```json
{
    "success": false,
    "errors": {
        "field_name": ["Message d'erreur"]
    }
}
```

---

## Routes Publiques (Lecture seule)

Ces routes sont destinées à l'affichage public du portfolio.

| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/ping` | Vérifier si le serveur est actif |
| GET | `/portfolio` | Informations du portfolio |
| GET | `/stages` | Liste des stages |
| GET | `/formations` | Liste des formations/diplômes |
| GET | `/projects` | Liste des projets |
| GET | `/projects/{id}` | Détail d'un projet |
| GET | `/realisations` | Liste des réalisations |
| GET | `/realisations/{id}` | Détail d'une réalisation |
| GET | `/companies` | Liste des entreprises |
| GET | `/competences` | Liste des compétences |
| GET | `/messages` | Messages importants actifs |
| GET | `/all` | Toutes les données en une requête |

---

## Routes Dashboard (CRUD complet)

Toutes les routes dashboard sont préfixées par `/dashboard`.

---

## 📊 Stats

### GET `/dashboard/stats`

Récupère les statistiques globales du dashboard.

**Exemple de retour :**

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

## 👤 Portfolio

### GET `/dashboard/portfolio`

Récupère les informations du portfolio.

**Exemple de retour :**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "bio": "Développeur Full Stack",
        "photo_url": "/storage/photos/profile.jpg",
        "email": "john@example.com",
        "phone": "+33 6 12 34 56 78",
        "location": "Paris, France",
        "linkedin_url": "https://linkedin.com/in/johndoe",
        "github_url": "https://github.com/johndoe",
        "year_start": 2020,
        "year_end": 2025,
        "contact_message": "N'hésitez pas à me contacter !",
        "created_at": "2025-03-04T10:00:00.000000Z",
        "updated_at": "2025-03-04T10:00:00.000000Z"
    }
}
```

### PUT `/dashboard/portfolio`

Met à jour les informations du portfolio.

**Body (JSON) :**

```json
{
    "first_name": "John",
    "last_name": "Doe",
    "bio": "Développeur Full Stack passionné",
    "photo_url": "/storage/photos/profile.jpg",
    "email": "john@example.com",
    "phone": "+33 6 12 34 56 78",
    "location": "Paris, France",
    "linkedin_url": "https://linkedin.com/in/johndoe",
    "github_url": "https://github.com/johndoe",
    "year_start": 2020,
    "year_end": 2025,
    "contact_message": "N'hésitez pas à me contacter !"
}
```

**Champs (tous optionnels pour la mise à jour) :**

| Champ | Type | Max | Description |
|-------|------|-----|-------------|
| first_name | string | 255 | Prénom |
| last_name | string | 255 | Nom |
| bio | string | 5000 | Biographie |
| photo_url | string | 500 | URL de la photo |
| email | email | 255 | Email |
| phone | string | 50 | Téléphone |
| location | string | 255 | Localisation |
| linkedin_url | url | 500 | URL LinkedIn |
| github_url | url | 500 | URL GitHub |
| year_start | integer | - | Année de début |
| year_end | integer | - | Année de fin |
| contact_message | string | 2000 | Message de contact |

---

## 🏢 Companies (Entreprises)

### GET `/dashboard/companies`

Liste toutes les entreprises avec compteurs.

**Exemple de retour :**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Entreprise ABC",
            "sector": "Tech",
            "description": "Description de l'entreprise",
            "location": "Paris",
            "photo_url": "/storage/companies/abc.jpg",
            "stages_count": 2,
            "projects_count": 5,
            "realisations_count": 3,
            "created_at": "2025-03-04T10:00:00.000000Z",
            "updated_at": "2025-03-04T10:00:00.000000Z"
        }
    ]
}
```

### GET `/dashboard/companies/{id}`

Récupère une entreprise avec ses relations.

### POST `/dashboard/companies`

Crée une entreprise.

**Body (JSON) :**

```json
{
    "name": "Nouvelle Entreprise",
    "sector": "Digital",
    "description": "Description de l'entreprise",
    "location": "Lyon, France",
    "photo_url": "/storage/companies/logo.jpg"
}
```

**Champs :**

| Champ | Type | Requis | Max | Description |
|-------|------|--------|-----|-------------|
| name | string | ✅ | 255 | Nom de l'entreprise |
| sector | string | ❌ | 255 | Secteur d'activité |
| description | string | ❌ | 5000 | Description |
| location | string | ❌ | 255 | Localisation |
| photo_url | string | ❌ | 500 | URL du logo |

### PUT `/dashboard/companies/{id}`

Met à jour une entreprise (mêmes champs, tous optionnels).

### DELETE `/dashboard/companies/{id}`

Supprime une entreprise.

⚠️ **Note :** La suppression échoue si l'entreprise a des stages, projets ou réalisations associés.

---

## 📅 Stages

### GET `/dashboard/stages`

Liste tous les stages avec entreprise et compétences.

**Exemple de retour :**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "company_id": 1,
            "start_date": "2024-01-15",
            "end_date": "2024-06-15",
            "duration": "6 mois",
            "role": "Développeur Full Stack",
            "description": "Description du stage",
            "company": {
                "id": 1,
                "name": "Entreprise ABC"
            },
            "competences": [
                {
                    "id": 1,
                    "code": "C1",
                    "label": "Développement Web"
                }
            ],
            "created_at": "2025-03-04T10:00:00.000000Z",
            "updated_at": "2025-03-04T10:00:00.000000Z"
        }
    ]
}
```

### GET `/dashboard/stages/{id}`

Récupère un stage par ID.

### POST `/dashboard/stages`

Crée un stage.

**Body (JSON) :**

```json
{
    "company_id": 1,
    "start_date": "2024-01-15",
    "end_date": "2024-06-15",
    "duration": "6 mois",
    "role": "Développeur Full Stack",
    "description": "Description détaillée du stage",
    "competence_ids": [1, 2, 3]
}
```

**Champs :**

| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| company_id | integer | ✅ | ID de l'entreprise |
| start_date | date | ✅ | Date de début (YYYY-MM-DD) |
| end_date | date | ✅ | Date de fin (YYYY-MM-DD) |
| duration | string | ❌ | Durée textuelle |
| role | string | ✅ | Rôle/poste |
| description | string | ❌ | Description |
| competence_ids | array | ❌ | IDs des compétences |

### PUT `/dashboard/stages/{id}`

Met à jour un stage (mêmes champs, tous optionnels).

### DELETE `/dashboard/stages/{id}`

Supprime un stage.

---

## � Formations (Parcours/Diplômes)

### GET `/dashboard/formations`

Liste toutes les formations (parcours scolaire, diplômes).

**Exemple de retour :**

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
            "description": "Formation en développement d'applications...",
            "logo_url": "/storage/formations/lycee.jpg",
            "diploma_url": null,
            "order": 0,
            "period": "2023 — 2025",
            "created_at": "2025-03-11T10:00:00.000000Z",
            "updated_at": "2025-03-11T10:00:00.000000Z"
        },
        {
            "id": 2,
            "title": "Licence Pro Développement Web",
            "school": "Université XYZ",
            "location": "Lyon, France",
            "degree_type": "Licence",
            "start_date": "2025-09-01",
            "end_date": null,
            "is_current": true,
            "description": "Formation en cours...",
            "logo_url": null,
            "diploma_url": null,
            "order": 1,
            "period": "2025 — En cours",
            "created_at": "2025-03-11T10:00:00.000000Z",
            "updated_at": "2025-03-11T10:00:00.000000Z"
        }
    ]
}
```

### GET `/dashboard/formations/{id}`

Récupère une formation par ID.

### POST `/dashboard/formations`

Crée une formation.

**Body (JSON) :**

```json
{
    "title": "BTS SIO option SLAM",
    "school": "Lycée Exemple",
    "location": "Paris, France",
    "degree_type": "BTS",
    "start_date": "2023-09-01",
    "end_date": "2025-06-30",
    "is_current": false,
    "description": "Formation en développement d'applications",
    "logo_url": "/storage/formations/logo.jpg",
    "diploma_url": null,
    "order": 0
}
```

**Champs :**

| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| title | string | ✅ | Titre du diplôme (ex: "BTS SIO SLAM") |
| school | string | ✅ | Nom de l'établissement |
| location | string | ❌ | Localisation |
| degree_type | string | ❌ | Type: BTS, Licence, Master, BAC, etc. |
| start_date | date | ✅ | Date de début (YYYY-MM-DD) |
| end_date | date | ❌ | Date de fin (null si en cours) |
| is_current | boolean | ❌ | Formation en cours ? (défaut: false) |
| description | string | ❌ | Description |
| logo_url | string | ❌ | URL du logo de l'école |
| diploma_url | string | ❌ | URL du diplôme (PDF) |
| order | integer | ❌ | Ordre d'affichage |

### PUT `/dashboard/formations/{id}`

Met à jour une formation (mêmes champs, tous optionnels sauf title/school).

### DELETE `/dashboard/formations/{id}`

Supprime une formation.

---

## �🎯 Competences

### GET `/dashboard/competences`

Liste toutes les compétences avec compteurs.

**Exemple de retour :**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "code": "C1",
            "label": "Développer une application sécurisée",
            "bloc": "Bloc 1",
            "description": "Description de la compétence",
            "projects_count": 5,
            "stages_count": 2,
            "created_at": "2025-03-04T10:00:00.000000Z",
            "updated_at": "2025-03-04T10:00:00.000000Z"
        }
    ]
}
```

### GET `/dashboard/competences/{id}`

Récupère une compétence avec ses projets et stages.

### POST `/dashboard/competences`

Crée une compétence.

**Body (JSON) :**

```json
{
    "code": "C1.1",
    "label": "Nouvelle compétence",
    "bloc": "Bloc 1",
    "description": "Description de la compétence"
}
```

**Champs :**

| Champ | Type | Requis | Max | Description |
|-------|------|--------|-----|-------------|
| code | string | ✅ | 50 | Code unique |
| label | string | ✅ | 255 | Libellé |
| bloc | string | ❌ | 100 | Bloc de compétences |
| description | string | ❌ | 5000 | Description |

### PUT `/dashboard/competences/{id}`

Met à jour une compétence.

### DELETE `/dashboard/competences/{id}`

Supprime une compétence.

---

## 💼 Projects

### GET `/dashboard/projects`

Liste tous les projets avec relations.

**Exemple de retour :**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "company_id": 1,
            "title": "Application Web E-commerce",
            "type": "Web",
            "description": "Courte description",
            "long_description": "Description détaillée en markdown...",
            "year": 2024,
            "company": {
                "id": 1,
                "name": "Entreprise ABC"
            },
            "tags": [
                {"id": 1, "project_id": 1, "tag": "Laravel"},
                {"id": 2, "project_id": 1, "tag": "Vue.js"}
            ],
            "competences": [
                {"id": 1, "code": "C1", "label": "Développement Web"}
            ],
            "images": [
                {
                    "id": 1,
                    "project_id": 1,
                    "image_url": "/storage/projects/image1.jpg",
                    "caption": "Page d'accueil",
                    "description": "Description de l'image",
                    "order": 1
                }
            ],
            "created_at": "2025-03-04T10:00:00.000000Z",
            "updated_at": "2025-03-04T10:00:00.000000Z"
        }
    ]
}
```

### GET `/dashboard/projects/{id}`

Récupère un projet par ID.

### POST `/dashboard/projects`

Crée un projet.

**Body (JSON) :**

```json
{
    "company_id": 1,
    "title": "Nouveau Projet",
    "type": "Web",
    "description": "Courte description du projet",
    "long_description": "# Description détaillée\n\nContenu en markdown...",
    "year": 2024,
    "tags": ["Laravel", "Vue.js", "TailwindCSS"],
    "competence_ids": [1, 2, 3]
}
```

**Champs :**

| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| company_id | integer | ❌ | ID de l'entreprise |
| title | string | ✅ | Titre du projet |
| type | string | ❌ | Type de projet |
| description | string | ❌ | Description courte |
| long_description | string | ❌ | Description longue (markdown) |
| year | integer | ❌ | Année |
| tags | array | ❌ | Tableau de tags (strings) |
| competence_ids | array | ❌ | IDs des compétences |

### PUT `/dashboard/projects/{id}`

Met à jour un projet.

**Exemple :**

```json
{
    "title": "Titre modifié",
    "description": "Nouvelle description",
    "tags": ["PHP", "MySQL"],
    "competence_ids": [1, 4]
}
```

### DELETE `/dashboard/projects/{id}`

Supprime un projet (et ses tags, images, relations).

---

## 🎨 Realisations

### GET `/dashboard/realisations`

Liste toutes les réalisations.

**Exemple de retour :**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "type": "Design",
            "company_id": 1,
            "title": "Refonte du site web",
            "description": "Courte description",
            "long_description": "Description détaillée...",
            "company": {
                "id": 1,
                "name": "Entreprise ABC"
            },
            "tags": [
                {"id": 1, "realisation_id": 1, "tag": "Figma"}
            ],
            "images": [...],
            "created_at": "2025-03-04T10:00:00.000000Z",
            "updated_at": "2025-03-04T10:00:00.000000Z"
        }
    ]
}
```

### GET `/dashboard/realisations/{id}`

Récupère une réalisation par ID.

### POST `/dashboard/realisations`

Crée une réalisation.

**Body (JSON) :**

```json
{
    "type": "Design",
    "company_id": 1,
    "title": "Nouvelle réalisation",
    "description": "Courte description",
    "long_description": "Description détaillée en markdown",
    "tags": ["Figma", "Adobe XD", "UI/UX"]
}
```

**Champs :**

| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| type | string | ❌ | Type de réalisation |
| company_id | integer | ❌ | ID de l'entreprise |
| title | string | ✅ | Titre |
| description | string | ❌ | Description courte |
| long_description | string | ❌ | Description longue (markdown) |
| tags | array | ❌ | Tableau de tags (strings) |

### PUT `/dashboard/realisations/{id}`

Met à jour une réalisation.

### DELETE `/dashboard/realisations/{id}`

Supprime une réalisation.

---

## � Important Messages

Messages importants à afficher sur le portfolio (ex: "Je recherche un stage", "Disponible pour alternance").

### GET `/dashboard/messages`

Liste tous les messages (actifs et inactifs) pour le dashboard.

**Exemple de retour :**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Recherche de stage",
            "message": "Je suis actuellement à la recherche d'un stage de 6 mois en développement web à partir de septembre 2026.",
            "type": "urgent",
            "icon": "briefcase",
            "link_url": "mailto:contact@example.com",
            "link_text": "Me contacter",
            "is_active": true,
            "order": 0,
            "start_date": null,
            "end_date": "2026-09-01T00:00:00.000000Z",
            "created_at": "2025-03-11T10:00:00.000000Z",
            "updated_at": "2025-03-11T10:00:00.000000Z"
        }
    ]
}
```

### GET `/messages` (Route publique)

Liste uniquement les messages actifs et dans leur période de validité.

### GET `/dashboard/messages/{id}`

Récupère un message par ID.

### POST `/dashboard/messages`

Crée un message important.

**Body (JSON) :**

```json
{
    "title": "Recherche de stage",
    "message": "Je suis à la recherche d'un stage de 6 mois en développement web.",
    "type": "urgent",
    "icon": "briefcase",
    "link_url": "mailto:contact@example.com",
    "link_text": "Me contacter",
    "is_active": true,
    "order": 0,
    "start_date": null,
    "end_date": "2026-09-01"
}
```

**Champs :**

| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| title | string | ❌ | Titre du message (max 255) |
| message | string | ✅ | Contenu du message (max 5000) |
| type | string | ❌ | Type: `info`, `success`, `warning`, `urgent` |
| icon | string | ❌ | Nom de l'icône (ex: briefcase, search) |
| link_url | url | ❌ | URL du lien optionnel |
| link_text | string | ❌ | Texte du lien |
| is_active | boolean | ❌ | Actif ou non (défaut: true) |
| order | integer | ❌ | Ordre d'affichage (défaut: 0) |
| start_date | date | ❌ | Date de début d'affichage |
| end_date | date | ❌ | Date de fin d'affichage |

### PUT `/dashboard/messages/{id}`

Met à jour un message (mêmes champs, tous optionnels sauf message).

### PATCH `/dashboard/messages/{id}/toggle`

Active ou désactive un message.

**Exemple de retour :**

```json
{
    "success": true,
    "data": { ... },
    "message": "Message activé"
}
```

### DELETE `/dashboard/messages/{id}`

Supprime un message.

---

## 🖼️ Images & Uploads

Toutes les routes d'upload sont préfixées par `/images`. Les fichiers sont stockés dans `/storage/{dossier}/` et l'URL retournée est directement utilisable pour l'affichage.

### Structure des dossiers

| Dossier | Contenu |
|---------|---------|
| `/storage/portfolio/` | Photo de profil |
| `/storage/companies/` | Logos des entreprises |
| `/storage/formations/` | Logos des écoles |
| `/storage/diplomas/` | Fichiers diplômes (PDF/images) |
| `/storage/projects/` | Images des projets |
| `/storage/realisations/` | Images des réalisations |

---

### 👤 Portfolio - Photo de profil

#### POST `/images/portfolio`

Upload la photo de profil du portfolio.

**Body (multipart/form-data) :**

| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| image | file | ✅ | Image (jpeg, png, jpg, gif, webp) max 5MB |

**Exemple de retour :**

```json
{
    "success": true,
    "url": "/storage/portfolio/photo_20250311_143052_abc12345.jpg",
    "portfolio": {
        "id": 1,
        "first_name": "John",
        "photo_url": "/storage/portfolio/photo_20250311_143052_abc12345.jpg",
        ...
    }
}
```

#### DELETE `/images/portfolio`

Supprime la photo de profil.

---

### 🏢 Companies - Logo

#### POST `/images/companies/{companyId}`

Upload le logo d'une entreprise.

**Body (multipart/form-data) :**

| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| image | file | ✅ | Image (jpeg, png, jpg, gif, webp, svg) max 5MB |

**Exemple de retour :**

```json
{
    "success": true,
    "url": "/storage/companies/company_1_20250311_143052_abc12345.png",
    "company": {
        "id": 1,
        "name": "Entreprise ABC",
        "photo_url": "/storage/companies/company_1_20250311_143052_abc12345.png",
        ...
    }
}
```

#### DELETE `/images/companies/{companyId}`

Supprime le logo de l'entreprise.

---

### 🎓 Formations - Logo & Diplôme

#### POST `/images/formations/{formationId}/logo`

Upload le logo de l'école/établissement.

**Body (multipart/form-data) :**

| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| image | file | ✅ | Image (jpeg, png, jpg, gif, webp, svg) max 5MB |

**Exemple de retour :**

```json
{
    "success": true,
    "url": "/storage/formations/formation_1_logo_20250311_143052_abc12345.png",
    "formation": {
        "id": 1,
        "title": "BTS SIO SLAM",
        "logo_url": "/storage/formations/formation_1_logo_20250311_143052_abc12345.png",
        ...
    }
}
```

#### DELETE `/images/formations/{formationId}/logo`

Supprime le logo de la formation.

#### POST `/images/formations/{formationId}/diploma`

Upload le diplôme (PDF ou image).

**Body (multipart/form-data) :**

| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| file | file | ✅ | PDF ou image (pdf, jpeg, png, jpg) max 10MB |

**Exemple de retour :**

```json
{
    "success": true,
    "url": "/storage/diplomas/diploma_1_20250311_143052_abc12345.pdf",
    "formation": {
        "id": 1,
        "title": "BTS SIO SLAM",
        "diploma_url": "/storage/diplomas/diploma_1_20250311_143052_abc12345.pdf",
        ...
    }
}
```

#### DELETE `/images/formations/{formationId}/diploma`

Supprime le diplôme.

---

### 💼 Projects - Galerie d'images

| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/images/projects/{projectId}` | Liste les images du projet |
| POST | `/images/projects/{projectId}` | Upload une image |
| DELETE | `/images/projects/image/{imageId}` | Supprime une image |
| PUT | `/images/projects/{projectId}/order` | Met à jour l'ordre |

#### POST `/images/projects/{projectId}`

**Body (multipart/form-data) :**

| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| image | file | ✅ | Image (jpeg, png, jpg, gif, webp) max 5MB |
| caption | string | ❌ | Légende de l'image |
| description | string | ❌ | Description détaillée |

**Exemple de retour :**

```json
{
    "success": true,
    "url": "/storage/projects/project_1_20250311_143052_abc12345.jpg",
    "image": {
        "id": 1,
        "project_id": 1,
        "image_url": "/storage/projects/project_1_20250311_143052_abc12345.jpg",
        "caption": "Page d'accueil",
        "description": "Description de l'image",
        "order": 1,
        "created_at": "2025-03-11T14:30:52.000000Z",
        "updated_at": "2025-03-11T14:30:52.000000Z"
    }
}
```

#### PUT `/images/projects/{projectId}/order`

Met à jour l'ordre des images.

**Body (JSON) :**

```json
{
    "images": [
        {"id": 1, "order": 2},
        {"id": 2, "order": 1},
        {"id": 3, "order": 3}
    ]
}
```

---

### 🎨 Realisations - Galerie d'images

Mêmes routes que pour les projets, avec `/realisations` au lieu de `/projects`.

| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/images/realisations/{realisationId}` | Liste les images |
| POST | `/images/realisations/{realisationId}` | Upload une image |
| DELETE | `/images/realisations/image/{imageId}` | Supprime une image |
| PUT | `/images/realisations/{realisationId}/order` | Met à jour l'ordre |

---

## 📤 Exemples d'upload avec JavaScript

### Upload simple (photo de profil)

```javascript
const fileInput = document.getElementById('photoInput');
const formData = new FormData();
formData.append('image', fileInput.files[0]);

const response = await fetch('http://localhost:8000/api/images/portfolio', {
    method: 'POST',
    body: formData
});

const data = await response.json();

if (data.success) {
    console.log('Photo uploadée:', data.url);
    // Utiliser data.url pour afficher l'image
    document.getElementById('preview').src = data.url;
}
```

### Upload logo entreprise

```javascript
const companyId = 1;
const formData = new FormData();
formData.append('image', fileInput.files[0]);

const response = await fetch(`http://localhost:8000/api/images/companies/${companyId}`, {
    method: 'POST',
    body: formData
});

const data = await response.json();
if (data.success) {
    console.log('Logo uploadé:', data.url);
}
```

### Upload image projet avec légende

```javascript
const projectId = 1;
const formData = new FormData();
formData.append('image', fileInput.files[0]);
formData.append('caption', 'Page d\'accueil du site');
formData.append('description', 'Capture d\'écran de la page d\'accueil avec le nouveau design');

const response = await fetch(`http://localhost:8000/api/images/projects/${projectId}`, {
    method: 'POST',
    body: formData
});

const data = await response.json();
if (data.success) {
    console.log('Image ajoutée:', data.image);
}
```

### Upload diplôme PDF

```javascript
const formationId = 1;
const formData = new FormData();
formData.append('file', pdfInput.files[0]); // Note: 'file' pas 'image'

const response = await fetch(`http://localhost:8000/api/images/formations/${formationId}/diploma`, {
    method: 'POST',
    body: formData
});

const data = await response.json();
if (data.success) {
    console.log('Diplôme uploadé:', data.url);
}
```

### Supprimer une image

```javascript
// Supprimer la photo de profil
await fetch('http://localhost:8000/api/images/portfolio', { method: 'DELETE' });

// Supprimer le logo d'une entreprise
await fetch('http://localhost:8000/api/images/companies/1', { method: 'DELETE' });

// Supprimer une image de projet
await fetch('http://localhost:8000/api/images/projects/image/5', { method: 'DELETE' });
```

---

## Résumé des routes Images

| Entité | Upload | Delete | Notes |
|--------|--------|--------|-------|
| Portfolio | POST `/images/portfolio` | DELETE `/images/portfolio` | Photo de profil unique |
| Company | POST `/images/companies/{id}` | DELETE `/images/companies/{id}` | Logo unique |
| Formation Logo | POST `/images/formations/{id}/logo` | DELETE `/images/formations/{id}/logo` | Logo unique |
| Formation Diplôme | POST `/images/formations/{id}/diploma` | DELETE `/images/formations/{id}/diploma` | PDF ou image |
| Project | POST `/images/projects/{id}` | DELETE `/images/projects/image/{imageId}` | Galerie multi-images |
| Realisation | POST `/images/realisations/{id}` | DELETE `/images/realisations/image/{imageId}` | Galerie multi-images |

---

## 🔗 Exemples d'appels avec Fetch (JavaScript)

### GET - Récupérer des données

```javascript
// Liste des projets
const response = await fetch('http://localhost:8000/api/dashboard/projects');
const data = await response.json();

if (data.success) {
    console.log(data.data); // Tableau des projets
}
```

### POST - Créer une ressource

```javascript
const response = await fetch('http://localhost:8000/api/dashboard/projects', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    body: JSON.stringify({
        title: 'Mon nouveau projet',
        description: 'Description du projet',
        year: 2024,
        tags: ['Laravel', 'Vue.js'],
        competence_ids: [1, 2]
    })
});

const data = await response.json();

if (data.success) {
    console.log('Projet créé:', data.data);
} else {
    console.error('Erreurs:', data.errors);
}
```

### PUT - Mettre à jour une ressource

```javascript
const response = await fetch('http://localhost:8000/api/dashboard/projects/1', {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    body: JSON.stringify({
        title: 'Titre modifié',
        tags: ['PHP', 'MySQL']
    })
});

const data = await response.json();
```

### DELETE - Supprimer une ressource

```javascript
const response = await fetch('http://localhost:8000/api/dashboard/projects/1', {
    method: 'DELETE',
    headers: {
        'Accept': 'application/json',
    }
});

const data = await response.json();

if (data.success) {
    console.log(data.message);
}
```

### Upload d'image (multipart/form-data)

```javascript
const formData = new FormData();
formData.append('image', fileInput.files[0]);
formData.append('caption', 'Ma légende');
formData.append('description', 'Description de l\'image');

const response = await fetch('http://localhost:8000/api/images/projects/1', {
    method: 'POST',
    body: formData
});

const data = await response.json();
```

---

## 🔗 Exemples avec Axios

### Configuration de base

```javascript
import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});
```

### CRUD Examples

```javascript
// GET - Liste
const { data } = await api.get('/dashboard/projects');

// GET - Détail
const { data } = await api.get('/dashboard/projects/1');

// POST - Créer
const { data } = await api.post('/dashboard/projects', {
    title: 'Nouveau projet',
    tags: ['Laravel']
});

// PUT - Mettre à jour
const { data } = await api.put('/dashboard/projects/1', {
    title: 'Titre modifié'
});

// DELETE - Supprimer
const { data } = await api.delete('/dashboard/projects/1');
```

---

## Codes HTTP de retour

| Code | Description |
|------|-------------|
| 200 | Succès |
| 201 | Ressource créée |
| 404 | Ressource non trouvée |
| 409 | Conflit (ex: suppression impossible) |
| 422 | Erreur de validation |
| 500 | Erreur serveur |

---

## Résumé des routes Dashboard

| Ressource | List | Get | Create | Update | Delete |
|-----------|------|-----|--------|--------|--------|
| Portfolio | GET /dashboard/portfolio | - | - | PUT /dashboard/portfolio | - |
| Companies | GET /dashboard/companies | GET /dashboard/companies/{id} | POST /dashboard/companies | PUT /dashboard/companies/{id} | DELETE /dashboard/companies/{id} |
| Stages | GET /dashboard/stages | GET /dashboard/stages/{id} | POST /dashboard/stages | PUT /dashboard/stages/{id} | DELETE /dashboard/stages/{id} |
| Formations | GET /dashboard/formations | GET /dashboard/formations/{id} | POST /dashboard/formations | PUT /dashboard/formations/{id} | DELETE /dashboard/formations/{id} |
| Competences | GET /dashboard/competences | GET /dashboard/competences/{id} | POST /dashboard/competences | PUT /dashboard/competences/{id} | DELETE /dashboard/competences/{id} |
| Projects | GET /dashboard/projects | GET /dashboard/projects/{id} | POST /dashboard/projects | PUT /dashboard/projects/{id} | DELETE /dashboard/projects/{id} |
| Realisations | GET /dashboard/realisations | GET /dashboard/realisations/{id} | POST /dashboard/realisations | PUT /dashboard/realisations/{id} | DELETE /dashboard/realisations/{id} |
| Messages | GET /dashboard/messages | GET /dashboard/messages/{id} | POST /dashboard/messages | PUT /dashboard/messages/{id} | DELETE /dashboard/messages/{id} |
