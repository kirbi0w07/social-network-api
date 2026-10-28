
#!/bin/sh
set -e

echo "=== Verificando configuración de Laravel ==="

php artisan config:clear

echo "=== Variables de entorno ==="
echo "DB_CONNECTION=${DB_CONNECTION:-NO CONFIGURADO}"
echo "DB_HOST=${DB_HOST:-NO CONFIGURADO}"
echo "DB_PORT=${DB_PORT:-NO CONFIGURADO}"
echo "DB_DATABASE=${DB_DATABASE:-NO CONFIGURADO}"
echo "DB_USERNAME=${DB_USERNAME:-NO CONFIGURADO}"

echo "=== Configuración efectiva de Laravel ==="

php artisan tinker --execute="dump([
    'connection' => config('database.default'),
    'host' => config('database.connections.pgsql.host'),
    'database' => config('database.connections.pgsql.database'),
    'username' => config('database.connections.pgsql.username'),
    'url_configured' => !empty(config('database.connections.pgsql.url')),
]);"

echo "=== Ejecutando migraciones ==="

php artisan migrate --force

echo "=== Iniciando Laravel ==="

exec php artisan serve \
    --host=0.0.0.0 \
    --port="${PORT:-10000}"
