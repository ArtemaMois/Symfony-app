FROM php:8.3-fpm

WORKDIR /var/www/project

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libpq-dev \
    git \
    zlib1g-dev \
    libxml2-dev \
    libpng-dev \
    libzip-dev 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_pgsql pgsql

RUN apt-get install tzdata

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

USER www

EXPOSE 9000
CMD ["php-fpm"]
