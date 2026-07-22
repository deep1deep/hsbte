FROM php:8.3-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx gettext-base git unzip zip libzip-dev libpq-dev \
    && docker-php-ext-install zip pdo pdo_mysql pdo_pgsql opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# composer files first — this layer stays cached unless dependencies actually
# change, so ordinary code edits rebuild in seconds
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

COPY . .
RUN composer dump-autoload --optimize --no-dev

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/nginx.conf.template /etc/nginx/nginx.conf.template
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 10000

CMD ["/usr/local/bin/entrypoint.sh"]
