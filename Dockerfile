# ── Stage 1 : Build assets (Node) ────────────────────────────────────────────
FROM node:20-alpine AS assets

WORKDIR /app
COPY package*.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm run build

# ── Stage 2 : App PHP-FPM ────────────────────────────────────────────────────
FROM php:8.3-fpm-alpine AS app

RUN apk add --no-cache \
        postgresql-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        libzip-dev \
        zip \
        unzip \
        curl \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Dépendances PHP (sans scripts post-install, sans dev)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Code source + assets buildés
COPY . .
COPY --from=assets /app/public/build ./public/build

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x docker/entrypoint.sh

EXPOSE 9000
CMD ["php-fpm"]

# ── Stage 3 : Nginx (assets statiques intégrés) ───────────────────────────────
FROM nginx:1.27-alpine AS webserver

# Vider la conf par défaut
RUN rm /etc/nginx/conf.d/default.conf

# Assets statiques baked-in (public/ source + build/ depuis Node)
COPY public/ /var/www/html/public/
COPY --from=assets /app/public/build /var/www/html/public/build

# Config nginx
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80