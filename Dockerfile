FROM php:8.3-cli
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpq-dev \
    && docker-php-ext-install zip pdo pdo_mysql pdo_pgsql
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
COPY . .
RUN composer install --no-dev --optimize-autoloader
RUN printf "upload_max_filesize=64M\npost_max_size=64M\n" > /usr/local/etc/php/conf.d/uploads.ini
EXPOSE 10000
CMD php artisan migrate --force && php artisan db:seed --force && (php artisan storage:link || true) && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
