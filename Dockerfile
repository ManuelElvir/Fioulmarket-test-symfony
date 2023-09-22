FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-install -j$(nproc) simplexml \
    && docker-php-ext-install -j$(nproc) zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo "date.timezone = UTC" >> /usr/local/etc/php/php.ini
RUN echo "memory_limit = 256M" >> /usr/local/etc/php/php.ini

RUN useradd -ms /bin/bash appuser

USER appuser

WORKDIR /var/www/html

EXPOSE 9000
