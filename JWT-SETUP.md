# 🔐 Configuration JWT - Portfolio API

## ✅ Fait

1. **Authentification JWT** ✓
   - Routes: `/auth/register`, `/auth/login`, `/auth/refresh`, `/auth/logout`, `/auth/me`
   - Config: `config/jwt.php` (TLL: 60min, Refresh: 20160min)

2. **Rôles d'utilisateurs** ✓
   - `admin`: Accès complet CRUD (`/dashboard/*`, `/images/*`)
   - `reader`: Accès public API + Refresh token

3. **Utilisateurs par défaut** ✓
   - Seeder: `database/seeders/AdminUserSeeder.php`
   - Admin: `admin@portfolio.local` / `AdminPassword123!`
   - Reader: `reader@portfolio.local` / `ReaderPassword123!`

4. **Middleware JWT** ✓
   - Protection automatique: `App\Http\Middleware\JwtMiddleware`
   - Vérification des rôles incluse
   - Routes admin nécessitent rôle `admin`

5. **Variables d'environnement** ✓
   - `.env.example` mis à jour avec JWT config
   - `render.yaml` tient compte des migrations/seeds au déploiement

6. **Documentation** ✓
   - API-DOCS.md: Examples d'utilisation + HTML register
   - openapi.yaml: Spec Swagger complète

---

## 🚀 Setup Local

```bash
# 1. Générer clés Laravel
php artisan key:generate

# 2. Générer JWT_SECRET (+ ajouter à .env)
php artisan jwt:secret

# 3. Créer DB + tables
php artisan migrate

# 4. Créer utilisateurs par défaut
php artisan db:seed

# 5. Lancer serveur
php artisan serve
```

**Identifiants de test:**

- Admin: `admin@portfolio.local` / `AdminPassword123!`
- Reader: `reader@portfolio.local` / `ReaderPassword123!`

---

## 🔧 Pour la Production (Render)

1. **Variables d'environnement** à définir dans Render dashboard:
   - `JWT_SECRET`: Générer avec `php artisan jwt:secret`
   - Les autres variables sont dans `render.yaml`

2. **Migrations/Seeds** roulent automatiquement via `buildCommand` dans `render.yaml`

3. **Test de l'API:**

   ```bash
   curl https://your-domain/api/ping
   ```

---

## 📚 Documentation

- **API-DOCS.md**: Tous les endpoints + exemples cURL/JavaScript + page register HTML
- **openapi.yaml**: Spec Swagger à importer sur <https://editor.swagger.io>
- Pour test Swagger local: `composer require darkaonline/l5-swagger`

---

## 🔒 Sécurité

- ✅ Tokens JWT signés HS256
- ✅ Passwords hashées (bcrypt)
- ✅ Rôles vérifiés sur chaque requête protégée
- ✅ Refresh token séparé (20160min par défaut)
- ⚠️ **À FAIRE:** HTTPS en production (Render l'applique automatiquement)
- ⚠️ **À FAIRE:** CORS si frontend & backend sur domaines différents

---

## 🐛 Problèmes Courants

**Token expiré:**

```javascript
// Utiliser /auth/refresh pour renouveler le token
fetch('http://api/auth/refresh', {
  method: 'POST',
  headers: { 'Authorization': 'Bearer ' + token }
});
```

**Rôle insuffisant (403):**

- `/dashboard/*` nécessite `role: admin`
- `/auth/register` crée des users `reader` par défaut
- Changer le rôle manuellement dans la DB si besoin

**JWT_SECRET manquant:**

```bash
# Générer dans .env (local)
php artisan jwt:secret

# Ou utiliser: Render dashboard → JWT_SECRET variable
```

---

## 📋 Checklist résumée

- [x] JWT configuré et fonctionnel
- [x] Rôles admin/reader en place
- [x] AdminUserSeeder intégré au DatabaseSeeder
- [x] Middleware JWT + contrôle d'accès
- [x] render.yaml avec migrations/seeds
- [x] API documentation complète
- [x] Swagger OpenAPI spec prête
- [ ] CORS configuré (si needed)
- [ ] Héberger la page register HTML qq part
- [ ] Tester en production

---

Besoin d'aide? Voir **API-DOCS.md** pour tous les endpoints et exemples.
