FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP and JS Dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
RUN npm install
RUN npm run build

# Change document root for Apache to point to Laravel's public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable Apache mod_rewrite and set permissions
RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN php artisan storage:link

# Expose port 80
EXPOSE 80

# Run migrations and start Apache
CMD sh -c "php artisan config:cache && php artisan route:cache && php artisan view:cache && (php artisan migrate --force || true) && apache2-foreground"
