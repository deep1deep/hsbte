#!/bin/sh
set -e

# Render assigns the port at runtime; nginx.conf can't read env vars itself,
# so bake it in here. Whitelisting ${PORT} keeps nginx's own $uri/$host intact.
: "${PORT:=10000}"
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

cd /var/www

php artisan migrate --force
php artisan db:seed --force
php artisan storage:link || true

# Must run at runtime, not build time — these bake in env vars (DB, APP_URL)
# that only exist once the container is actually running.
php artisan config:cache
php artisan route:cache
php artisan view:cache

# the artisan commands above ran as root, so anything they created is root-owned.
# php-fpm workers run as www-data and still need to write logs and sessions.
chown -R www-data:www-data storage bootstrap/cache

# php-fpm in the background, nginx in the foreground as PID 1
php-fpm -D
exec nginx -g 'daemon off;'
