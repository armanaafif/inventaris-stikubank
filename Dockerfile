FROM php:8.3-apache

# Install ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy semua file project
COPY . .

# Install dependensi PHP
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permission untuk storage dan cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port
EXPOSE 80