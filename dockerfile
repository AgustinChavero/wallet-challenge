FROM php:8.1-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libxml2-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar SOAP para funcionamiento correcto de la extension
RUN docker-php-ext-install soap

# Extensiones necesarias para Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Instalar Redis extension
RUN pecl install redis && docker-php-ext-enable redis

WORKDIR /var/www