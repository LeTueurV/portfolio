# 🔧 PROMPT: Configurer CORS dans Laravel API

## Objectif
Activer CORS (Cross-Origin Resource Sharing) dans l'API Laravel pour permettre au frontend (admin-portfolio) d'accéder aux endpoints d'upload d'images.

**Problème actuel:** 
- Frontend: `http://localhost:8080` ou `https://portfolio-mlb3.onrender.com`
- API: `https://portfolio-mlb3.onrender.com/api`
- Erreur: `Access-Control-Allow-Origin header is missing`

---

## ✅ Actions à effectuer

### 1️⃣ Modifier le fichier `config/cors.php`

**Chemin:** `Laravel/config/cors.php`

**Remplacer le contenu par:**

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],  // Accept GET, POST, PUT, DELETE, PATCH, etc.

    'allowed_origins' => ['*'],  // PERMISSIF: accepte toutes les origines
    
    // OU si vous voulez être restrictif (RECOMMANDÉ pour la production):
    // 'allowed_origins' => [
    //     'http://localhost:8080',
    //     'http://localhost:3000',
    //     'http://localhost:8000',
    //     'https://portfolio-mlb3.onrender.com',
    //     'file://',  // Pour les tests locaux
    // ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],  // Accept tous les headers (Content-Type, Authorization, etc.)

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
```

---

### 2️⃣ Verifier le middleware CORS est activé

**Fichier:** `app/Http/Kernel.php`

Vérifier que dans le groupe API (section `$middlewareGroups`), il y a:

```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    \Fruitcake\Cors\HandleCors::class,  // ← DOIT ÊTRE LÀ
],
```

Si `HandleCors::class` n'est pas présent, ajouter:

```php
use Fruitcake\Cors\HandleCors;
```

---

### 3️⃣ Vérifier l'installation du package CORS

Exécuter dans le terminal Laravel:

```bash
composer require fruitcake/laravel-cors
```

Si déjà installé, sauter cette étape.

---

### 4️⃣ Publier la configuration (si première installation)

```bash
php artisan vendor:publish --vendor=fruitcake/laravel-cors
```

---

### 5️⃣ Déployer sur Render.com

**Pour Render.com**, une fois le code modifié:

```bash
git add config/cors.php app/Http/Kernel.php
git commit -m "✅ Enable CORS for frontend image uploads"
git push origin main
```

**Attendre 2-3 minutes** que Render redéploie automatiquement.

---

## 🧪 Vérification

Une fois déployé, tester:

1. **Frontend:** Ouvrir `http://localhost:8080` (ou votre URL de production)
2. **Console:** F12 → Onglet Console
3. **Action:** Cliquer sur "Upload logo" dans une entreprise
4. **Attendu:** Voir dans la console:
   ```
   🔄 Upload logo vers: https://portfolio-mlb3.onrender.com/api/images/companies/1
   📨 Response status: 200
   📦 Response data: {success: true, data: {...}, message: "..."}
   ✅ Upload réussi!
   ```

---

## 🔍 Si erreur persiste:

### Erreur: "500 Internal Server Error"
→ Problème Laravel (vérifier logs: `storage/logs/laravel.log`)

### Erreur: "CORS still blocked"
→ Cache Laravel pas clearé:
```bash
php artisan config:cache
php artisan config:clear
php artisan cache:clear
```

### En développement local (Laravel serve):
```bash
php artisan serve
# Puis accéder depuis http://localhost:8080
# vers http://localhost:8000/api (doit fonctionner)
```

---

## 📋 Checklist

- [ ] `config/cors.php` modifié avec `allowed_origins=['*']`
- [ ] `app/Http/Kernel.php` contient `HandleCors::class`
- [ ] Package `fruitcake/laravel-cors` installé
- [ ] Cache Laravel clearé (`config:clear`, `cache:clear`)
- [ ] Code poussé sur Git et Render redéployé
- [ ] Test upload logo = succès ✅

---

## Endpoints affectés

Ces endpoints doivent maintenant fonctionner depuis le frontend:

```
POST   /api/images/portfolio/          (upload photo profil)
DELETE /api/images/portfolio/          (supprimer photo)
POST   /api/images/companies/{id}      (upload logo entreprise)
DELETE /api/images/companies/{id}      (supprimer logo)
POST   /api/images/formations/{id}/logo/     (upload logo formation)
DELETE /api/images/formations/{id}/logo/     (supprimer logo)
POST   /api/images/formations/{id}/diploma/  (upload diplôme)
DELETE /api/images/formations/{id}/diploma/  (supprimer diplôme)
POST   /api/images/projects/{id}       (upload images projet)
DELETE /api/images/projects/image/{id} (supprimer image projet)
```

---

**Fait le 13 Mars 2026**
