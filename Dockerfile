FROM php:7.4-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev default-mysql-client \
    && docker-php-ext-install pdo_mysql zip \
    && a2dismod mpm_event mpm_worker \
    && a2enmod mpm_prefork rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/start.sh /usr/local/bin/start-stafftrack

RUN composer install --no-dev --no-interaction --prefer-dist --no-plugins --no-scripts \
    && composer dump-autoload --no-plugins --no-scripts \
    && chmod +x /usr/local/bin/start-stafftrack \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["start-stafftrack"]
