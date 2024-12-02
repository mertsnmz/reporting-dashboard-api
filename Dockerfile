FROM composer:2.6 as composer

WORKDIR /app
COPY composer.json composer.lock ./
COPY database/ database/
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

FROM node:20-alpine as node

WORKDIR /app

COPY package*.json ./
COPY vite.config.js ./
COPY postcss.config.js ./
COPY tailwind.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm ci
RUN npm run build

RUN echo "Build output contents:"
RUN ls -la public/build/
RUN cat public/build/manifest.json || echo "manifest.json not found"

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
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

COPY --from=composer /app/vendor/ vendor/
COPY --from=node /app/public/build/ public/build/

COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data . && \
    chmod -R 755 storage && \
    chmod -R 755 bootstrap/cache && \
    chmod -R 755 public/build

RUN echo "Final build files check:"
RUN ls -la public/build/
