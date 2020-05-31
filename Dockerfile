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
COPY website/data /var/www/data

COPY packaging/version /var/www/packaging/version
COPY .git/refs/heads/master /var/www/packaging/hash

COPY webiste/php.ini /usr/local/etc/php

COPY website/nginx/nginx.conf /etc/nginx/conf.d/default.conf
COPY website/nginx/locations.conf /etc/nginx/snippets/locations.conf
COPY website/nginx/domains.conf /etc/nginx/snippets/domains.conf
COPY website/nginx/blog.conf /etc/nginx/snippets/blog.conf
COPY website/nginx/git.conf /etc/nginx/snippets/git.conf

COPY docker-entrypoint.sh /usr/local/bin/

RUN ln -s /var/www/data/thumbnails /var/www/html/html/img/thumbnails

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

CMD /usr/local/bin/docker-entrypoint.sh