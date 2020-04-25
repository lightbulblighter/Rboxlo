FROM php:fpm-alpine

RUN apk add oniguruma-dev
RUN apk add nginx
RUN apk add bash

RUN docker-php-ext-install mbstring
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli

RUN rm -rf /var/www/*
RUN mkdir /run/nginx

COPY website/html /var/www/html
COPY website/backend /var/www/backend
COPY packaging/version /var/www/packaging/version
COPY website/renders /var/www/bak/renders
COPY website/static /var/www/bak/static

COPY .git/refs/heads/master /var/www/packaging/hash

COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY locations.conf /etc/nginx/snippets/locations.conf

COPY docker-entrypoint.sh /usr/local/bin/

RUN ln -s /var/www/renders /var/www/html/renders

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

CMD /usr/local/bin/docker-entrypoint.sh