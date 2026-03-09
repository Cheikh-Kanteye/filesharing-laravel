#!/bin/bash
# FileShare Setup Script
# Run this once to install the PHP PostgreSQL extension and run migrations

set -e
echo "=== FileShare Setup ==="

# Install PHP PostgreSQL extension
echo "[1/3] Installing PHP PostgreSQL extension..."
sudo apt-get install -y php8.3-pgsql

# Run migrations
echo "[2/3] Running database migrations..."
php artisan migrate

# Build assets
echo "[3/3] Building frontend assets..."
npm run build

echo ""
echo "=== Setup complete! ==="
echo "Start the dev server with: php artisan serve"
echo "And assets watcher with:   npm run dev"
