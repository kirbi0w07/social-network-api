
#!/bin/sh
set -e

echo "=== Verificando configuración de Laravel ==="

php artisan config:clear

echo "=== Variables de entorno ==="
echo "DB_DATABASE recibido: ${DB_DATABASE:-NO CONFIGURADO}"

echo "=== Configuración efectiva de Laravel ==="
php artisan tinker --execute="dump([
    'connection' => config('database.default'),
    'database' => config('database.connections.pgsql.database'),
    'url_configured' => !empty(config('database.connections.pgsql.url')),
]);"

echo "=== Ejecutando migraciones ==="
php artisan migrate --force

echo "=== Iniciando Laravel ==="
exec php artisan serve \
    --host=0.0.0.0 \
    --port="${PORT:-10000}"
