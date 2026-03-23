#!/usr/bin/env bash
# Exit on error
set -o errexit

echo "Installing PHP dependencies..."
composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction

echo "Installing JS dependencies and building frontend..."
npm install
npm run build

echo "Clearing and caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running database migrations..."
php artisan migrate --force
