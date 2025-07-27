#!/bin/bash

cd /var/www

echo "Fixing permissions..."
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

if [ ! -f "database/database.sqlite" ]; then
    echo "Creating SQLite database file..."
    touch database/database.sqlite
fi

chmod 666 database/database.sqlite || true
chown www-data:www-data database/database.sqlite || true

if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ ! -d "node_modules" ]; then
    echo "Installing and building frontend assets..."
    npm install && npm run build
fi

if [ ! -f /var/www/.env ]; then
    cp /var/www/.env.example /var/www/.env
    php /var/www/artisan key:generate
fi

echo "Running migrations..."
php artisan migrate --force

echo "Linking storage..."
php artisan storage:link || true

echo "Starting Reverb..."
php artisan reverb:start &

echo "Starting PHP-FPM..."
exec php-fpm
