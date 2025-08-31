# Build dependencies
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.* ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Production image
FROM php:8.2-fpm-alpine
RUN apk add --no-cache nginx supervisor \
    && docker-php-ext-install pdo pdo_mysql opcache \
    && mkdir -p /run/nginx

WORKDIR /var/www/html
COPY . .
COPY --from=vendor /app/vendor ./vendor

# Configuration
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# Permissions
RUN chown -R www-data:www-data var/ public/ \
    && chmod -R 775 var/

# Cache warmup and environment setup
USER www-data
ENV APP_ENV=prod
ENV APP_DEBUG=0
RUN php bin/console cache:warmup --env=prod --no-debug

USER root
EXPOSE 8080
CMD ["supervisord", "-c", "/etc/supervisord.conf"]