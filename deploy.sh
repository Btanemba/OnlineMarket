#!/bin/bash
set -e

echo "🚀 Starting deployment..."

# Ensure database directory exists
echo "📁 Setting up database directory..."
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chmod -R 775 /var/www/html/database

# Run database migrations
echo "🔄 Running database migrations..."
php artisan migrate --force --no-interaction

# Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link || true

# Clear all caches first
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Clear and cache configuration
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

echo "✅ Deployment completed successfully!"
