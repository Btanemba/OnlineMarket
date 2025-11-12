#!/bin/bash
set -e

echo "🚀 Starting application..."

# Create .env file from environment variables
echo "📝 Creating .env file from environment variables..."
cat > /var/www/html/.env << EOF
APP_NAME=${APP_NAME:-Laravel}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL}

LOG_CHANNEL=${LOG_CHANNEL:-stack}
LOG_LEVEL=${LOG_LEVEL:-error}

DB_CONNECTION=${DB_CONNECTION:-sqlite}
DB_DATABASE=${DB_DATABASE:-/var/www/html/database/database.sqlite}

SESSION_DRIVER=${SESSION_DRIVER:-file}
SESSION_LIFETIME=120

CACHE_DRIVER=${CACHE_DRIVER:-file}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}

FILESYSTEM_DISK=${FILESYSTEM_DISK:-public}
EOF

# Ensure database directory exists
echo "📁 Setting up database directory..."
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chmod -R 775 /var/www/html/database

# Ensure storage directories exist
echo "📂 Setting up storage directories..."
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/public/storage

# Run database migrations
echo "🔄 Running database migrations..."
php artisan migrate --force --no-interaction || true

# Create storage symlink
echo "🔗 Creating storage symlink..."
rm -rf /var/www/html/public/storage
php artisan storage:link || true
ls -la /var/www/html/public/storage

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Cache configuration
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

echo "✅ Starting Apache..."
exec apache2-foreground
