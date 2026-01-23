FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpq-dev

RUN curl -sS https://getcomposer.org/installer | php && \
  mv composer.phar  /usr/local/bin/composer

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

RUN docker-php-ext-install zip \
    pdo \
    pdo_pgsql \
    pgsql

RUN echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

WORKDIR /var/www/app
