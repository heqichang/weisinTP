FROM php:5.6-fpm
RUN pecl install redis-4.3.0 \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo pdo_mysql