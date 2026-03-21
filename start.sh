#!/bin/sh
set -e

echo "=== Portfolio API Startup ==="

# Générer APP_KEY si absent
if [ -z "$APP_KEY" ]; then
    echo "[startup] Génération de APP_KEY..."
    php artisan key:generate --force
fi

# Générer JWT_SECRET si absent
if [ -z "$JWT_SECRET" ]; then
    echo "[startup] Génération de JWT_SECRET..."
    php artisan jwt:secret --force
    php artisan config:clear
fi

echo "[startup] Démarrage du serveur sur le port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
