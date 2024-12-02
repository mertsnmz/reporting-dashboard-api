FROM php:8.2-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring bcmath xml \
    && curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

COPY . .

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

RUN composer install --no-dev --optimize-autoloader

RUN npm install
RUN npm run build

EXPOSE 8000
EXPOSE 5173

CMD ["sh", "-c", "php artisan key:generate && php artisan config:cache && php artisan route:cache && php artisan serve --host=0.0.0.0 --port=8000 & npm run dev"]
