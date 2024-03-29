FROM php:5.6-fpm
RUN pecl install redis-4.3.0 \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get update && apt-get install -y \
               libfreetype6-dev \
               libjpeg62-turbo-dev \
               libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd