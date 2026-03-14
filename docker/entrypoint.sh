#!/bin/sh
set -e

echo "▶ Migrations (endpoint direct Neon, sans pooler)..."
# DB_HOST_DIRECT = endpoint non-pooler (requis pour les DDL migrations)
MIGRATE_HOST="${DB_HOST_DIRECT:-$DB_HOST}"
DB_HOST="$MIGRATE_HOST" php artisan migrate --force

echo "▶ Storage link..."
php artisan storage:link --force

echo "▶ Cache config / routes / vues..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✓ Démarrage PHP-FPM..."
exec php-fpm
