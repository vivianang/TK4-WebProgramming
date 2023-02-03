#! /bin/sh
set -e

cd /app

composer install
php artisan migrate
if [ ! -f "/tmp/.seed" ]; then
    php artisan db:seed
fi

php artisan serve --host=0.0.0.0