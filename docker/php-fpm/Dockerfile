FROM php:7.4-fpm

RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY ./config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql

WORKDIR /var/www/auth_test_task