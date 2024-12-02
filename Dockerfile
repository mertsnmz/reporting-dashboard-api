FROM composer:2.6 as composer

COPY composer.json composer.lock /app/
COPY database/ /app/database/

RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

FROM node:20-alpine as node

WORKDIR /app
COPY package.json package-lock.json ./
COPY . .

RUN npm install
RUN npm run build

FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . /var/www/html/
COPY --from=composer /app/vendor/ /var/www/html/vendor/
COPY --from=node /app/public/build/ /var/www/html/public/build/

COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage
