#!/bin/sh

chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

php artisan migrate --force

php artisan config:clear
php artisan config:cache

exec /usr/bin/supervisord -c /etc/supervisord.conf