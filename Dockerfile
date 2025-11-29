FROM php:8.4-fpm

# Installer les dépendances pour pdo_pgsql
RUN apt-get update && apt-get install -y libpq-dev gcc

# Installer les extensions PHP
RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /app
