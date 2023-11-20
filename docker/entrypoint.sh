#!/bin/sh
set -e

php artisan optimize

echo "Calling original entrypoint command..."
exec /init "$@"
