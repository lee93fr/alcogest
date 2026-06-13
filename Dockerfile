FROM php:8.2-fpm

# Dépendances système
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    nodejs \
    npm \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Dépendances PHP
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-autoloader

# Dépendances JS + build frontend
COPY package.json package-lock.json vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
RUN npm ci && npm run build

# Application complète
COPY . .
RUN composer dump-autoload --no-dev --optimize

# Permissions
RUN mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache public \
    && chmod -R 775 storage bootstrap/cache

# Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Script de démarrage
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
