
#!/bin/sh
set -e

echo "Ejecutando migraciones pendientes..."
php artisan migrate --force

echo "Iniciando Laravel..."
exec php artisan serve \
  --host=0.0.0.0 \
  --port="${PORT:-10000}"
