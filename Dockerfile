FROM php:8.2-fpm

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/entrypoint.sh /usr/local/bin/app-entrypoint.sh
RUN chmod +x /usr/local/bin/app-entrypoint.sh

WORKDIR /var/www/html

ENTRYPOINT ["app-entrypoint.sh"]
