FROM php:8.3-fpm AS venton_php

# Install common dependencies
RUN apt-get update; apt-get install --no-install-recommends -y \
    acl netcat-traditional wget ssh git nano

# Enable common PHP extensions
RUN docker-php-ext-install bcmath pdo_mysql

# Enable PHP GD extensions
RUN apt-get update; apt-get install --no-install-recommends -y \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev; \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd

# Enable PHP intl extension
RUN apt-get update; apt-get install --no-install-recommends -y \
    libicu-dev; \
    docker-php-ext-install intl

# Enable PHP exif extension
RUN docker-php-ext-install exif

# Enable PHP redis extension
RUN pecl install -o -f redis; \
    docker-php-ext-enable redis

# Enable PHP xdebug extension
RUN pecl install "xdebug-3.3.1"; \
    docker-php-ext-enable xdebug

# Enable PHP xsl extension
RUN apt-get update; apt-get install --no-install-recommends -y \
    libxslt-dev; \
    docker-php-ext-install xsl

# Enable PHP zip extension
RUN apt-get update; apt-get install --no-install-recommends -y \
    libzip-dev unzip; \
    docker-php-ext-install zip

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD ["php-fpm"]

# Finish build
EXPOSE 9000
USER root
WORKDIR /srv/www

FROM nginx:alpine AS venton_nginx

WORKDIR /srv/www

ENV FPM_HOST=app
ENV FPM_PORT=9000

COPY docker/templates/default.conf.template /etc/nginx/templates/

FROM venton_php AS {PROJECT_NAME}_app

# Prepare application
COPY artisan ./
COPY app app/
COPY bootstrap bootstrap/
COPY config config/
COPY database database/
COPY lang lang/
COPY public public/
COPY resources resources/
COPY routes routes/
COPY storage storage/

RUN set -eux; \
    chmod +x artisan

FROM venton_nginx AS {PROJECT_NAME}_web

# Copy application directory contents
COPY --from={PROJECT_NAME}_app /srv/www/public public/
