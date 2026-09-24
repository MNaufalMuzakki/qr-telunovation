#!/bin/sh
set -e

# Configure port for Nginx (Render assigns PORT env var, defaults to 80)
TARGET_PORT="${PORT:-80}"
sed -i "s/LISTEN_PORT/${TARGET_PORT}/g" /etc/nginx/http.d/default.conf

# Setup SQLite database if using sqlite
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    mkdir -p /var/www/html/database
    if [ ! -f /var/www/html/database/database.sqlite ]; then
        touch /var/www/html/database/database.sqlite
    fi
fi

# Set proper directory permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Run Laravel startup tasks
php artisan storage:link || true
php artisan migrate --force || true
php artisan db:seed --force || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Starting services on port ${TARGET_PORT}..."
exec /usr/bin/supervisord -n -c /etc/supervisord.conf
