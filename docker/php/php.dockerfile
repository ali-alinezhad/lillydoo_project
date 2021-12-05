FROM php:7.4.20-fpm-alpine3.13

RUN addgroup -g 1000 lillydoo && adduser -u 1000 -G lillydoo -g lillydoo -s /bin/sh -D lillydoo

RUN mkdir -p /var/www/html

RUN chown lillydoo:lillydoo /var/www/html

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql
