# 📚 Portfolio API - Documentation

**Base URL:** `http://localhost:8000/api` (dev) ou votre URL de production

## 🔐 Authentification JWT

Tous les endpoints protégés nécessitent un header:

```
Authorization: Bearer <token>
```

---

## 1️⃣ AUTH - Endpoints

### ✨ Register (Créer un compte lecteur)

**POST** `/auth/register`

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "SecurePass123!",
  "password_confirmation": "SecurePass123!"
}
```

**Réponse 201:**

```json
{
  "success": true,
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "reader"
  }
}
```

---

### 🔑 Login - Authentification JWT

**POST** `/auth/login`

**Purpose**: Obtenir un token JWT pour accéder aux routes protégées

**Request:**

```json
{
  "email": "admin@portfolio.local",
  "password": "AdminPassword123!"
}
```

**Response 200:**

```json
{
  "success": true,
  "message": "Login successful",
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAiLCJpYXQiOjE3MzM5MzAxMjAsImV4cCI6MTczMzk3MzMyMCwidWlkIjoxLCJlbWFpbCI6ImFkbWluQHBvcnRmb2xpby5sb2NhbCIsInJvbGUiOiJhZG1pbiJ9.KGh5BlZCd...",
  "token_type": "Bearer",
  "expires_in": 3600,
  "user": {
    "id": 1,
    "name": "Administrator",
    "email": "admin@portfolio.local",
    "role": "admin",
    "created_at": "2026-03-20T09:00:00.000000Z"
  }
}
```

**Utilisation du token:**

Après login, utiliser le `access_token` dans le header `Authorization` pour les requêtes protégées:

```javascript
// JavaScript/Fetch
const token = "eyJ0eXAiOiJKV1QiLCJhbGc...";

fetch('http://localhost:8000/api/dashboard/stats', {
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
  }
});
```

```bash
# cURL
curl -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc..." \
  http://localhost:8000/api/dashboard/stats
```

**Erreurs possibles:**

- **401 Unauthorized**: Email ou mot de passe incorrect
- **422 Validation failed**: Données manquantes ou invalides
- **500 Server error**: Problème serveur

**Caractéristiques du token:**

- ✅ Valide 60 minutes (configurable dans `JWT_TTL`)
- ✅ Peut être rafraîchi avant expiration via `/auth/refresh`
- ✅ Contient les claims: `uid`, `email`, `role`

---

### 🔄 Refresh Token

**POST** `/auth/refresh` (protégé)

**Purpose**: Obtenir un nouveau token avant expiration (utile pour les sessions longues)

**Request:**

```bash
curl -X POST http://localhost:8000/api/auth/refresh \
  -H "Authorization: Bearer <current_token>" \
  -H "Content-Type: application/json"
```

**Response 200:**

```json
{
  "success": true,
  "message": "Token refreshed successfully",
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "Bearer",
  "expires_in": 3600
}
```

**Quand utiliser?**

- L'utilisateur a une longue session (vidéo, édition longue)
- Token expire dans quelques minutes
- Avant d'effectuer une action importante

---

### 👤 Get Current User

**GET** `/auth/me` (protégé)

**Purpose**: Récupérer les infos de l'utilisateur connecté

**Request:**

```bash
curl -H "Authorization: Bearer <token>" \
  http://localhost:8000/api/auth/me
```

**Response 200:**

```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Administrator",
    "email": "admin@portfolio.local",
    "role": "admin",
    "created_at": "2026-03-20T09:00:00.000000Z"
  }
}
```

---

## 2️⃣ PUBLIC API - Endpoints (Lecture seule)

### Portefeuille

- **GET** `/portfolio` - Infos portfolio

### Étapes

- **GET** `/stages` - Liste tous les stages

### Formations

- **GET** `/formations` - Liste toutes les formations

### Projets

- **GET** `/projects` - Liste tous les projets
- **GET** `/projects/{id}` - Détail d'un projet

### Réalisations

- **GET** `/realisations` - Liste toutes les réalisations
- **GET** `/realisations/{id}` - Détail d'une réalisation

### Entreprises & Compétences

- **GET** `/companies` - Liste les entreprises
- **GET** `/competences` - Liste les compétences

### Tout en une requête

- **GET** `/all` - Portfolio complet (toutes les données)

---

## 3️⃣ PROTECTED API - Endpoints (Admin Only)

**Prefix:** `/dashboard` + Middleware JWT

### Portfolio

- **GET** `/dashboard/portfolio` - Récupérer
- **PUT** `/dashboard/portfolio` - Mettre à jour

### Entreprises (CRUD)

- **GET** `/dashboard/companies` - Liste
- **GET** `/dashboard/companies/{id}` - Détail
- **POST** `/dashboard/companies` - Créer
- **PUT** `/dashboard/companies/{id}` - Modifier
- **DELETE** `/dashboard/companies/{id}` - Supprimer

### Stages (CRUD)

- **GET** `/dashboard/stages`
- **GET** `/dashboard/stages/{id}`
- **POST** `/dashboard/stages`
- **PUT** `/dashboard/stages/{id}`
- **DELETE** `/dashboard/stages/{id}`

### Projets (CRUD)

- **GET** `/dashboard/projects`
- **GET** `/dashboard/projects/{id}`
- **POST** `/dashboard/projects`
- **PUT** `/dashboard/projects/{id}`
- **DELETE** `/dashboard/projects/{id}`

### Réalisations (CRUD)

- **GET** `/dashboard/realisations`
- **GET** `/dashboard/realisations/{id}`
- **POST** `/dashboard/realisations`
- **PUT** `/dashboard/realisations/{id}`
- **DELETE** `/dashboard/realisations/{id}`

### Compétences (CRUD)

- **GET** `/dashboard/competences`
- **GET** `/dashboard/competences/{id}`
- **POST** `/dashboard/competences`
- **PUT** `/dashboard/competences/{id}`
- **DELETE** `/dashboard/competences/{id}`

### Stats

- **GET** `/dashboard/stats` - Statistiques du portfolio

---

## 🛠️ Exemples d'utilisation

### cURL - Login

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@portfolio.local",
    "password": "AdminPassword123!"
  }'
```

### JavaScript/Fetch - Register

```javascript
const register = async (name, email, password) => {
  const response = await fetch('http://localhost:8000/api/auth/register', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      name,
      email,
      password,
      password_confirmation: password
    })
  });
  return response.json();
};

register('John Doe', 'john@example.com', 'SecurePass123!');
```

### JavaScript/Fetch - API call (protégé)

```javascript
const token = 'eyJ0eXAiOiJKV1QiLCJhbGc...'; // Du login

const getProjects = async () => {
  const response = await fetch('http://localhost:8000/api/dashboard/projects', {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
  return response.json();
};

getProjects();
```

### React Hook - Login

```javascript
const [token, setToken] = useState(null);

const login = async (email, password) => {
  try {
    const response = await fetch('http://localhost:8000/api/auth/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });
    const data = await response.json();
    if (data.success) {
      setToken(data.access_token);
      localStorage.setItem('token', data.access_token);
    }
  } catch (error) {
    console.error('Login failed:', error);
  }
};
```

---

## 👥 Utilisateurs par défaut (après seeding)

| Email | Mot de passe | Rôle | Accès |
|-------|-------------|------|-------|
| `admin@portfolio.local` | `AdminPassword123!` | admin | Tout (CRUD complet) |
| `reader@portfolio.local` | `ReaderPassword123!` | reader | Public API + Refresh Token |

---

## ⚠️ Codes d'erreur

| Code | Signification |
|------|---------------|
| 200 | ✅ Succès |
| 201 | ✅ Créé |
| 401 | ❌ Non authentifié / Token invalide |
| 403 | ❌ Non autorisé (rôle insuffisant) |
| 404 | ❌ Ressource non trouvée |
| 422 | ❌ Validation échouée |
| 500 | ❌ Erreur serveur |

---

## 📝 Setup Environnement

**En local (Laragon):**

```bash
# Copier .env.example
cp .env.example .env

# Générer clés
php artisan key:generate
php artisan jwt:secret

# DB & seeding
php artisan migrate
php artisan db:seed

# Lancer app
php artisan serve
```

**JWT_SECRET** - À générer automatiquement ou définir manuellement pour la production

---

## 🔗 Swagger/OpenAPI

Swagger est disponible à: `/api/docs` (si configNé avec swagger)

Pour configurer Swagger:

1. Installer: `composer require darkaonline/l5-swagger`
2. Publier: `php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"`
3. Générer: `php artisan l5-swagger:generate`
4. Accéder à: `http://localhost:8000/api/docs`
**Ou utiliser Swagger UI en ligne:**
Allez sur <https://editor.swagger.io> et importez le fichier `openapi.yaml` du projet.

---

## 📄 Créer une page d'inscription HTML/JavaScript

Exemple simple de page HTML pour l'inscription:

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.5);
        }
        button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #764ba2;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .login-link a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Créer un compte</h1>
        <form id="registerForm">
            <div class="form-group">
                <label for="name">Nom:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required minlength="8">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmer mot de passe:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
            </div>
            <button type="submit">S'inscrire</button>
        </form>
        <div id="message"></div>
        <div class="login-link">
            Vous avez un compte? <a href="login.html">Se connecter</a>
        </div>
    </div>

    <script>
        const API_URL = 'http://localhost:8000/api'; // Modifier selon votre URL

        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;

            // Validation
            if (password !== password_confirmation) {
                showMessage('Les mots de passe ne correspondent pas', 'error');
                return;
            }

            try {
                const response = await fetch(`${API_URL}/auth/register`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        name,
                        email,
                        password,
                        password_confirmation
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showMessage('✅ Inscription réussie! Vous pouvez maintenant vous connecter.', 'success');
                    document.getElementById('registerForm').reset();
                    setTimeout(() => {
                        window.location.href = 'login.html';
                    }, 2000);
                } else {
                    showMessage(`❌ ${data.message || 'Erreur lors de l\'inscription'}`, 'error');
                }
            } catch (error) {
                showMessage(`❌ Erreur: ${error.message}`, 'error');
            }
        });

        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = message;
            messageDiv.className = `message ${type}`;
        }
    </script>
</body>
</html>
```

**Page login.html similaire:**

```html
<!-- Même structure, mais avec /auth/login -->
<!-- Et sauvegarder le token dans localStorage -->
<!-- localStorage.setItem('token', data.access_token); -->
```

---

## ✅ Checklist Setup Production (Render/Docker)

- [ ] Définir `JWT_SECRET` dans le dashboard Render
- [ ] Configurer les variables Cloudflare R2
- [ ] Les migrations et seeders roulent automatiquement
- [ ] Admin user créé: `admin@portfolio.local` / `AdminPassword123!`
- [ ] Tester `/api/ping` pour vérifier que l'API fonctionne
