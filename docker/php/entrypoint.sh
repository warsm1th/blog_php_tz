#!/bin/sh
set -e

mkdir -p /var/www/html/var/smarty/compile
chmod -R a+rwX /var/www/html/var

exec docker-php-entrypoint php-fpm
