FROM php:7.4-fpm

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY ./config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/auth_test_task