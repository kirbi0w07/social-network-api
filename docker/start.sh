#!/bin/sh

set -e

echo "Starting Laravel..."

php artisan storage:link || true

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force

php-fpm -D

exec nginx -g "daemon off;"
