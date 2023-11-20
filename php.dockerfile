FROM serversideup/php:8.2-fpm-nginx AS build-php

## install required packages
RUN apt-get -y update
RUN apt-get -y upgrade
RUN apt-get -yq install \
        libfreetype6-dev \
        libjpeg-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev

## Place Files
COPY --chown=webuser:webgroup .eslintrc.js /var/www/html/.eslintrc.js
COPY --chown=webuser:webgroup .styleci.yml /var/www/html/.styleci.yml
COPY --chown=webuser:webgroup app /var/www/html/app
COPY --chown=webuser:webgroup artisan /var/www/html/artisan
COPY --chown=webuser:webgroup bootstrap /var/www/html/bootstrap
COPY --chown=webuser:webgroup composer.json /var/www/html/composer.json
COPY --chown=webuser:webgroup composer.lock /var/www/html/composer.lock
COPY --chown=webuser:webgroup config /var/www/html/config
COPY --chown=webuser:webgroup database /var/www/html/database
COPY --chown=webuser:webgroup lang /var/www/html/lang
COPY --chown=webuser:webgroup package.json /var/www/html/package.json
COPY --chown=webuser:webgroup phpstan.neon /var/www/html/phpstan.neon
COPY --chown=webuser:webgroup phpunit.xml /var/www/html/phpunit.xml
COPY --chown=webuser:webgroup pint.json /var/www/html/pint.json
COPY --chown=webuser:webgroup postcss.config.js /var/www/html/postcss.config.js
COPY --chown=webuser:webgroup public /var/www/html/public
COPY --chown=webuser:webgroup resources /var/www/html/resources
COPY --chown=webuser:webgroup routes /var/www/html/routes
COPY --chown=webuser:webgroup storage /var/www/html/storage
COPY --chown=webuser:webgroup stubs /var/www/html/stubs
COPY --chown=webuser:webgroup tailwind.config.js /var/www/html/tailwind.config.js
COPY --chown=webuser:webgroup tests /var/www/html/tests
COPY --chown=webuser:webgroup vite.config.js /var/www/html/vite.config.js
COPY --chown=webuser:webgroup yarn.lock /var/www/html/yarn.lock

COPY --chmod=0755 ./docker/composer.sh /etc/composer.sh
USER webuser
RUN /etc/composer.sh

USER root

## Setup file permission
RUN chown webuser:webgroup -R /var/www
RUN chmod -R 777 /var/www/html/storage
RUN chown webuser:webgroup -R /var/www/html/storage

## Build Assets
FROM node:18.13.0 AS build-node

COPY --from=build-php /var/www/html /var/www/html
WORKDIR /var/www/html
COPY --chmod=0755 ./docker/yarn.sh /etc/yarn.sh
RUN /etc/yarn.sh

FROM build-php AS server

ENV AUTORUN_LARAVEL_MIGRATION=true
ENV SSL_MODE=off

COPY --from=build-node /var/www/html/public/build /var/www/html/public/build

COPY --chown=webuser:webgroup .env /var/www/html/.env

COPY --chmod=0755 ./docker/entrypoint.sh /etc/entrypoint.sh
ENTRYPOINT ["/etc/entrypoint.sh"]
