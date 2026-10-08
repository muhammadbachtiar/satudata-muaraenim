# syntax=docker/dockerfile:1.7
#
# One image, three roles (see docker-compose.yml):
#   app       -> nginx + php-fpm (supervisord)   [default CMD]
#   worker    -> php artisan queue:work
#   scheduler -> php artisan schedule:work
#
# Debian (not Alpine) on purpose: www-data is uid/gid 33 here, the same as on
# Debian/Ubuntu hosts, so existing uploaded files in ./storage stay writable.

ARG PHP_VERSION=8.3

# ---------------------------------------------------------------- base ------
FROM php:${PHP_VERSION}-fpm-bookworm AS base

COPY --from=ghcr.io/mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/

# pdo_pgsql = PostgreSQL driver (production DB). mbstring, curl, etc. ship with the official image.
RUN apt-get update \
    && apt-get install -y --no-install-recommends nginx supervisor gosu curl unzip \
    && install-php-extensions pdo_pgsql pcntl opcache redis \
    && rm -rf /var/lib/apt/lists/* /etc/nginx/sites-enabled/* /etc/nginx/conf.d/*

# -------------------------------------------------------------- vendor ------
FROM base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
# Only the manifests, so this layer is cached until dependencies change.
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

# ------------------------------------------------------------- runtime ------
FROM base AS runtime

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=vendor /app/vendor ./vendor
COPY . .

# bootstrap/cache and storage/* are not guaranteed to exist in the build context
# (storage is excluded by .dockerignore and bind-mounted at runtime).
RUN mkdir -p bootstrap/cache storage/app/public storage/framework/cache/data \
        storage/framework/sessions storage/framework/views storage/logs \
    && composer dump-autoload --no-dev --optimize --no-scripts \
    && php artisan package:discover --ansi \
    && rm /usr/bin/composer \
    # `php artisan storage:link` equivalent: public/storage -> storage/app/public
    && ln -sfn ../storage/app/public public/storage \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-satudata.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/zz-satudata.conf
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/satudata.conf
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

# php-fpm pool sizing, tunable per environment (see docker/php/php-fpm.conf).
ENV APP_ENV=production \
    FPM_MAX_CHILDREN=20 \
    FPM_START_SERVERS=4 \
    FPM_MIN_SPARE=2 \
    FPM_MAX_SPARE=6

EXPOSE 80

ENTRYPOINT ["docker-entrypoint"]
CMD ["supervisord", "-c", "/etc/supervisor/satudata.conf"]
