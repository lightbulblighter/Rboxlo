FROM php:fpm-alpine

RUN apk add oniguruma-dev
RUN apk add nginx
RUN apk add bash

RUN docker-php-ext-install mbstring
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli

RUN rm -rf /var/www/*
RUN mkdir /run/nginx

COPY website/public /var/www/html
COPY website/application /var/www/application
COPY website/data /var/www/data

COPY packaging/version /var/www/packaging/version
COPY .git/refs/heads/master /var/www/packaging/hash

COPY website/php.ini /usr/local/etc/php

COPY website/nginx/nginx.conf /etc/nginx/conf.d/default.conf
COPY website/nginx/locations.conf /etc/nginx/snippets/locations.conf
COPY website/nginx/domains.conf /etc/nginx/snippets/domains.conf
COPY website/nginx/listeners.conf /etc/nginx/snippets/listeners.conf
COPY website/nginx/custom.conf /etc/nginx/snippets/custom.conf

COPY docker-entrypoint.sh /usr/local/bin/

RUN ln -s /var/www/data/thumbnails /var/www/html/html/img/thumbnails
RUN ln -s /var/www/data/setup /var/www/html/api/setup/files

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

CMD /usr/local/bin/docker-entrypoint.sh