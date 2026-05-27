#!/bin/sh
set -e

PORT="${PORT:-80}"
sed -i "s/__PORT__/${PORT}/g" /etc/apache2/sites-available/000-default.conf
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
a2dismod mpm_event mpm_worker >/dev/null 2>&1 || true
a2enmod mpm_prefork rewrite >/dev/null 2>&1 || true

if [ -n "$APP_KEY" ]; then
    :
else
    export APP_KEY=SomeRandomStringForStaffTrackDemo
fi

if [ -n "$DB_HOST" ]; then
    echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT:-3306}..."
    for attempt in $(seq 1 30); do
        if mysqladmin ping \
            --host="$DB_HOST" \
            --port="${DB_PORT:-3306}" \
            --user="$DB_USERNAME" \
            --password="$DB_PASSWORD" \
            --silent; then
            break
        fi

        if [ "$attempt" -eq 30 ]; then
            echo "MySQL is not reachable."
            exit 1
        fi

        sleep 2
    done
fi

php artisan migrate --force

if [ "$RUN_SEEDERS" = "true" ]; then
    php artisan db:seed --force
fi

apache2-foreground
