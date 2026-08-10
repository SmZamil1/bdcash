FROM php:8.3-apache

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev libxml2-dev libonig-dev libcurl4-openssl-dev \
    curl unzip git && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mbstring xml curl zip gd bcmath \
    tokenizer ctype fileinfo intl opcache

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy all app files
COPY . .

# Install Laravel dependencies
RUN cd core && composer install --no-dev --optimize-autoloader --no-interaction

# Set up storage
RUN mkdir -p core/storage/logs core/storage/framework/sessions \
    core/storage/framework/views core/storage/framework/cache/data \
    core/bootstrap/cache && \
    chmod -R 777 core/storage core/bootstrap/cache && \
    touch core/storage/logs/laravel.log

# Cache Laravel config
RUN cd core && php artisan config:cache || true
RUN cd core && php artisan route:cache || true

# Apache config - serve from /var/www/html (repo root has index.php)
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]
