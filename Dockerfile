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

RUN npm install
RUN npm run build

RUN php artisan key:generate
RUN php artisan config:cache
RUN php artisan route:cache

EXPOSE 8000

EXPOSE 5173

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=8000 & npm run dev"]
