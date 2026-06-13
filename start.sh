#!/bin/sh
set -e

# Créer les répertoires nécessaires
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

# Permissions
chmod -R 775 storage bootstrap/cache

# Symlink storage
php artisan storage:link --force

# Migrations auto
php artisan migrate --force

# Caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Démarrer nginx + php-fpm (commande nixpacks Laravel standard)
exec /usr/bin/start-nginx-fpm
