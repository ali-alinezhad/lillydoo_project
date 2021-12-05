FROM composer:2.1.1

RUN addgroup -g 1000 lillydoo && adduser -u 1000 -G lillydoo -g lillydoo -s /bin/sh -D lillydoo

WORKDIR /var/www/html
