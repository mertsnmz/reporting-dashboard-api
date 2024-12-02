FROM composer:2.6 as composer

WORKDIR /app
COPY composer.json composer.lock ./
COPY database/ database/
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

FROM node:20-alpine as node

WORKDIR /app

COPY . .

RUN npm ci

RUN ls -la resources/css/
RUN cat resources/css/app.css
RUN ls -la

RUN npm run build

RUN ls -la public/build/
RUN find public/build -type f

FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

RUN a2enmod rewrite headers

WORKDIR /var/www/html

COPY . .
COPY --from=composer /app/vendor/ vendor/
COPY --from=node /app/public/build/ public/build/

COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN mkdir -p storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data . \
    && chmod -R 755 storage bootstrap/cache public/build

RUN ls -la public/build

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
