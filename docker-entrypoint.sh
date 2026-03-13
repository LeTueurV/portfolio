#!/bin/bash
# Script de démarrage pour Render.com

set -e

echo "🚀 Démarrage de l'application Portfolio..."

# Générer la clé si nécessaire
echo "🔑 Vérification des clés..."
php artisan key:generate --no-interaction 2>/dev/null || true

# Créer répertoire de base de données s'il n'existe pas
echo "💾 Vérification base de données..."
mkdir -p database

# Exécuter les migrations

# Optimiser le cache
echo "⚡ Optimisation du cache..."
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# Démarrer Laravel Artisan Server
echo "✅ Serveur en cours de démarrage sur 0.0.0.0:${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
