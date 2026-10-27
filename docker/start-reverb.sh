
#!/bin/sh
set -e

echo "Iniciando Laravel Reverb..."

exec php artisan reverb:start \
  --host=0.0.0.0 \
  --port="${PORT:-10000}"
