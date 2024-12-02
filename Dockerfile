FROM php:8.2-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan key:generate
RUN php artisan config:cache
RUN php artisan route:cache

RUN npm install

EXPOSE 80
EXPOSE 5173

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=80 & npm run dev -- --host=0.0.0.0 --port=5173"]
