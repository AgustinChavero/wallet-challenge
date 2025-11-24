FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    libxml2-dev

# Habilitar SOAP para funcionamiento correcto de la extension
RUN docker-php-ext-install soap

# Extensiones necesarias para Laravel
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www
