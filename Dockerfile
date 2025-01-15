FROM php:8.3-fpm

WORKDIR /var/www/project

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libpq-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_pgsql pgsql

RUN apt-get update && \
    apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
    docker-php-ext-install gd
RUN apt-get install tzdata

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

USER www

EXPOSE 9000
CMD ["php-fpm"]
