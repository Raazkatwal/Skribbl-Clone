# Dockerfile
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    sqlite3 \
    libsqlite3-dev \
    git \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Set working directory
WORKDIR /var/www

# Copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install

# Set permissions
RUN chown -R www-data:www-data /var/www && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
