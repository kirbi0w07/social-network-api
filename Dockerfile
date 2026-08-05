FROM php:8.3-fpm-bookworm

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libxml2-dev \
    && docker-php-ext-install \
        pdo_pgsql \
        mbstring \
        bcmath \
        xml \
        zip \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Primero copiamos dependencias para aprovechar Docker cache
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# Copiar aplicación
COPY . .

# Ejecutar scripts de Composer ahora que Laravel ya está disponible
RUN composer dump-autoload --optimize

# Permisos de Laravel
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data \
        storage \
        bootstrap/cache \
    && chmod -R 775 \
        storage \
        bootstrap/cache

# Configuración de Nginx
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# PHP-FPM debe aceptar conexiones desde Nginx
RUN sed -i 's|^listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf

EXPOSE 10000

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
