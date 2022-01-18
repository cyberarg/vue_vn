FROM php:7.4-fpm-alpine

RUN apk update && \
    apk add --no-cache libpng-dev jpeg-dev libjpeg-turbo-dev libxml2-dev php-soap php-gd git $PHPIZE_DEPS

RUN docker-php-ext-install pdo pdo_mysql soap gd

RUN pecl install xdebug-2.9.0 redis && \
    docker-php-ext-enable xdebug redis

COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer
