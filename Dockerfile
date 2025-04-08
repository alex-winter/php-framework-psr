FROM php:8.4-rc-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions (phar is built-in, don't re-install)
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev \
    && docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy app
COPY . /var/www/html

# (Optional) Custom Apache config for routing support
COPY apache.conf /etc/apache2/sites-available/000-default.conf