FROM php:8.4-rc-apache

RUN a2enmod rewrite

RUN apt-get update && \ 
    apt-get install -y zip unzip git curl libzip-dev

RUN docker-php-ext-install pdo pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . /var/www/html

COPY apache.conf /etc/apache2/sites-available/000-default.conf