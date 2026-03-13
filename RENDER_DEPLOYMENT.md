# 🚀 Déploiement sur Render.com

Guide complet pour déployer votre API Portfolio sur Render.com avec stockage Cloudflare R2.

## ⚡ Prérequis

- Compte Render.com
- Repository GitHub avec ce projet
- Credentials Cloudflare R2

## 📋 Étapes de déploiement

### 1️⃣ Connecter GitHub à Render

1. Aller sur [render.com](https://render.com)
2. Se connecter / créer un compte
3. Cliquer sur **"New +"** → **"Web Service"**
4. Sélectionner **"Deploy an existing repository from GitHub"**
5. Autoriser Render à accéder à GitHub
6. Sélectionner ce repository

### 2️⃣ Configurer le service

**Basic Settings:**
- **Name:** `portfolio-api` (ou autre)
- **Environment:** `Docker`
- **Region:** `Frankfurt EU (fra)` ou `IAD (US)` selon vos préférences
- **Branch:** `main` (ou votre branche)
- **Auto-deploy:** Activé (optionnel)

**Build Command:**  
Laisser vide (Render utilisera le Dockerfile)

**Start Command:**  
Laisser vide (utilisera ENTRYPOINT du Dockerfile)

### 3️⃣ Configurer les variables d'environnement

Cliquer sur **"Advanced"** ou allez dans **Settings → Environment Variables**

Ajouter ces variables:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=                          # (auto-généré)
DB_CONNECTION=sqlite
FILESYSTEM_DISK=r2

# Cloudflare R2 (IMPORTANT!)
CLOUDFLARE_R2_ACCESS_KEY_ID=d912100a938a4ab3571d165ba99b2dd6
CLOUDFLARE_R2_SECRET_ACCESS_KEY=01f5f394a3f922c89258cc69452946b034d86958c64a5445f4a901bc640cdd9a
CLOUDFLARE_R2_BUCKET=a55c6e5cc8c8a996e287abce953ac92c
CLOUDFLARE_R2_ENDPOINT=https://a55c6e5cc8c8a996e287abce953ac92c.r2.cloudflarestorage.com
CLOUDFLARE_R2_PUBLIC_URL=https://a55c6e5cc8c8a996e287abce953ac92c.r2.cloudflarestorage.com

# Session & Cache (requis sur Render)
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 4️⃣ Configurer le Disque (Volume)

**Pour SQLite persistent:**

1. Cliquer sur **"Disks"** dans le menu
2. Cliquer **"Create Disk"**
   - **Name:** `sqlite-storage`
   - **Mount Path:** `/app/database`
   - **Size:** 1 GB minimum
3. Cliquer **"Create"**

La variable d'environnement sera auto-configurée.

### 5️⃣ Health Check (optionnel mais recommandé)

Dans **Settings → Health Check:**
- **Path:** `/api/ping`
- **Protocol:** `HTTP`

### 6️⃣ Lancer le déploiement

1. Cliquer **"Create Web Service"**
2. Render va:
   - Clone le repository
   - Build l'image Docker
   - Lancer le serveur Laravel

3. Attendre que le statut passe à **"Live"** (5-10 minutes)

---

## ✅ Vérifier le déploiement

Une fois déployé, vous pouvez tester:

```bash
# Récupérer l'URL depuis le dashboard Render (ex: https://portfolio-api.onrender.com)

# Test simple
curl https://portfolio-api.onrender.com/api/ping
# Résultat: {"status":"ok","message":"pong",...}

# Test upload image
curl -X POST https://portfolio-api.onrender.com/api/images/portfolio \
  -F "image=@photo.jpg"
```

---

## 📊 Logs et débogage

### Voir les logs en temps réel

Dans le dashboard Render → **Logs**

Ou depuis le terminal:
```bash
# Voir les derniers logs
curl https://api.render.com/v1/services/{service-id}/logs
```

### Erreurs courantes

**Erreur: "No HTTP ports detected"**
- Port n'est pas exposé correctement
- **Solution:** Vérifier que le Dockerfile expose le port avec `EXPOSE 8080`
- Vérifier que le serveur démarre correctement dans les logs

**Erreur: "APP_KEY missing"**
- La clé n'est pas générée
- **Solution:** Render génère automatiquement la clé au premier démarrage

**Erreur: "Database locked" (SQLite)**
- Concurrent access sur la base de données
- **Solution:** Utiliser PostgreSQL pour production (voir ci-dessous)

**Erreur: "R2 credentials invalid"**
- Credentials incorrects ou exécutés
- **Solution:** Vérifier les variables d'environnement dans le dashboard Render

---

## 🔄 Updates et redéploiement

### Auto-déploiement (si activé)

Chaque push sur `main` redéploiera automatiquement.

### Redéploiement manuel

Dans le dashboard Render:
1. Cliquer sur le service
2. Menu **"Deployments"**
3. Cliquer **"Trigger deploy"** sur le dernier déploiement

---

## 📈 Pour la production

### Utiliser PostgreSQL (meilleur que SQLite)

1. Créer une base PostgreSQL sur Render
2. Obtenir la `EXTERNAL DATABASE_URL`
3. Ajouter aux variables d'environnement:

```env
DATABASE_URL=postgresql://user:password@host:port/dbname
DB_CONNECTION=pgsql
```

### Optimisations simples

```env
APP_ENV=production
APP_DEBUG=false
LOG_CHANNEL=stderr
LOG_LEVEL=warning
```

### HTTPS/SSL

Render fournit automatiquement HTTPS via Let's Encrypt. Pas de configuration nécessaire.

---

## 🆘 Support Render

Si vous rencontrez des problèmes:

1. **Docs Render:** https://render.com/docs
2. **Status Page:** https://status.render.com
3. **Render Support:** https://render.com/support

---

## 💡 Points importants

✅ **À faire:**
- Garder les credentials R2 secures (variables d'environnement)
- Configurer le disque SQLite pour persistence
- Monitorer les logs au démarrage

❌ **À éviter:**
- Hard-coder les credentials dans le Dockerfile
- Déployer directement avec SQLite sans disque persistant
- Oublier d'ajouter les variables d'environnement clés

---

## 📞 Problèmes courants

| Problème | Cause | Solution |
|----------|-------|----------|
| Port non détecté | EXPOSE 8080 manquant | Vérifier le Dockerfile |
| App crash au démarrage | Migrations échouent | Vérifier les logs Render |
| Images non uploadées | Credentials R2 manquants | Ajouter env vars |
| Base de données reset | Pas de volume persistent | Créer un disque Render |
| Déploiement lent | Build Docker long | Normal pour première fois |

---

## 🎯 Résumé

1. ✅ Connecter GitHub
2. ✅ Configurer variables d'environnement (surtout R2!)
3. ✅ Créer un disque pour la base de données
4. ✅ Lancer le déploiement
5. ✅ Tester `/api/ping`

**Estimated time:** 10-20 minutes

---

**Version:** 1.0  
**Dernière mise à jour:** Mars 2026  
**Platform:** Render.com  
**Status:** ✅ Prêt à déployer
