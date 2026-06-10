#!/usr/bin/env bash
set -euo pipefail

echo "=== NiaOS Safe Deploy ==="

echo "--- Composer install (no dev, optimize) ---"
composer install --no-dev --optimize-autoloader

echo "--- Run migrations ---"
php artisan migrate --force

echo "--- Publish Filament assets ---"
php artisan filament:assets

echo "--- Clear cache ---"
php artisan optimize:clear

echo "--- Ensure platform admin ---"
php artisan niaos:ensure-platform-admin

echo "--- Restart queue worker ---"
sudo systemctl restart niaos-dev

echo "=== Deploy complete ==="
