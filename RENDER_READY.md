# 🚀 Portfolio API - Render Ready

## ✅ Prêt à déployer sur Render.com

Cette application Laravel est totalement optimisée pour Render.com avec:

### 📋 Architecture
- **Docker ready** - Dockerfile optimisé pour Render
- **Entrypoint script** - Gère migrations et setup automatiques
- **Cloudflare R2** - Stockage d'images dans le cloud
- **SQLite par défaut** - Base de données persistante sur disque Render
- **Zero configuration** - La plupart des variables sont auto-configurées

### 🔧 À configurer sur Render

Lors de créer le service, ajouter ces variables d'environnement:

```env
# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<votre-service>.onrender.com  # Auto-généré par Render
DB_CONNECTION=sqlite
FILESYSTEM_DISK=r2

# Cloudflare R2 (Pour uploads images)
CLOUDFLARE_R2_ACCESS_KEY_ID=d912100a938a4ab3571d165ba99b2dd6
CLOUDFLARE_R2_SECRET_ACCESS_KEY=01f5f394a3f922c89258cc69452946b034d86958c64a5445f4a901bc640cdd9a
CLOUDFLARE_R2_BUCKET=a55c6e5cc8c8a996e287abce953ac92c
CLOUDFLARE_R2_ENDPOINT=https://a55c6e5cc8c8a996e287abce953ac92c.r2.cloudflarestorage.com
CLOUDFLARE_R2_PUBLIC_URL=https://a55c6e5cc8c8a996e287abce953ac92c.r2.cloudflarestorage.com

# Session & Cache (recommandé)
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 📁 Volumen requis

Créer un disque **"sqlite-storage"** avec:
- **Mount Path:** `/app/database`
- **Size:** 1 GB minimum

### ☑️ Checklist de déploiement

- [ ] Variables d'environnement configurées dans Render
- [ ] Disque SQLite créé et monté
- [ ] Health check configuré: `/api/ping`
- [ ] Région sélectionnée
- [ ] Repository GitHub connecté
- [ ] Trigger deploy

### 📊 Endpoints de test

Une fois déployé:

```bash
# Test serveur
curl https://<votre-service>.onrender.com/api/ping

# Test upload (depuis votre frontend)
POST https://<votre-service>.onrender.com/api/images/portfolio
  Header: Content-Type: multipart/form-data
  Body: file=@image.jpg

# Tous les endpoints
GET  https://<votre-service>.onrender.com/api/projects
GET  https://<votre-service>.onrender.com/api/portfolio
etc...
```

### 🔍 Voir les logs

Dans le dashboard Render:
- Cliquer sur le service → **"Logs"**
- Voir les traces de démarrage et erreurs

### ⚡ Performance

- ✅ Startup rapide (30-60 secondes)
- ✅ Images servies depuis Cloudflare R2 (CDN automatique)
- ✅ Base de données locale (pas de latence réseau)
- ✅ Auto-restart en cas de crash

### 💾 Persistence

- ✅ Base SQLite persistente sur disque Render
- ✅ Images stockées sur Cloudflare R2 (cloud)
- ✅ Logs complets disponibles

### 🚨 Résolution de problèmes

**"Port not detected":**
- Vérifier que `docker-entrypoint.sh` est présent et exécutable
- Voir les logs Render pour erreurs de démarrage

**"App keeps crashing":**
- Voir les logs Render
- Vérifier que les variables d'environnement clés sont configurées
- Vérifier que le disque SQLite est créé

**"Images don't upload":**
- Vérifier les credentials Cloudflare R2
- Vérifier la variable `CLOUDFLARE_R2_PUBLIC_URL`
- Consulter [RENDER_DEPLOYMENT.md](RENDER_DEPLOYMENT.md) pour plus de détails

### 📚 Documentation

- **[RENDER_DEPLOYMENT.md](RENDER_DEPLOYMENT.md)** - Guide complet Render
- **[Context.MD](Context.MD)** - API documentation complète
- **[CLOUDFLARE_R2_SETUP.md](CLOUDFLARE_R2_SETUP.md)** - Config Cloudflare R2

### 🎯 Prochaines étapes

1. ✅ Connecter repository GitHub
2. ✅ Configurer variables d'environnement
3. ✅ Créer disque SQLite
4. ✅ Déployer
5. ✅ Tester `/api/ping`
6. ✅ Tester uploads images

**Durée estimée:** 10-15 minutes

---

**Render Service:** Ready ✅  
**Cloudflare R2:** Configured ✅  
**Database:** SQLite (persistent) ✅  
**Status:** Production Ready 🚀
