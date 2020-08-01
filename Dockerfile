FROM node:12

RUN mkdir /rboxlo
RUN mkdir /rboxlo/matchmaker
ADD matchmaker /rboxlo/matchmaker

RUN cd /rboxlo/matchmaker && npm install

FROM php:fpm-alpine

RUN apk add oniguruma-dev
RUN apk add nginx
RUN apk add bash

RUN docker-php-ext-install mbstring
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli

RUN rm -rf /var/www/*
RUN mkdir /run/nginx

RUN mkdir /var/www/application
RUN mkdir /var/www/data

ADD website/public /var/www/html
ADD website/application /var/www/application

RUN chmod +x /var/www/data

COPY packaging/version /var/www/packaging/version

COPY website/php.ini /usr/local/etc/php
COPY website/structure.sql /rboxlo

COPY website/nginx/nginx.conf /etc/nginx/conf.d/default.conf
COPY website/nginx/locations.conf /etc/nginx/snippets/locations.conf
COPY website/nginx/domains.conf /etc/nginx/snippets/domains.conf
COPY website/nginx/listeners.conf /etc/nginx/snippets/listeners.conf
COPY website/nginx/custom.conf /etc/nginx/snippets/custom.conf

RUN ln -s /var/www/data/thumbnails /var/www/html/html/img/thumbnails
RUN ln -s /var/www/data/setup /var/www/html/api/setup/files

EXPOSE 8080

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

RUN chmod +x /rboxlo
RUN chmod +x /rboxlo/matchmaker
RUN chmod +x /rboxlo/*
RUN chmod +x /rboxlo/matchmaker/*

CMD /usr/local/bin/docker-entrypoint.sh