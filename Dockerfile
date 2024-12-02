FROM mertsnmz/php-nginx-openresty:ubuntu22.04

WORKDIR /app

COPY . .

EXPOSE 80

COPY nginx.conf /usr/local/openresty/nginx/conf/nginx.conf
COPY nginx.conf /etc/nginx/sites-available/default
COPY nginx.conf /etc/nginx/nginx.conf
COPY php.ini /usr/local/etc/php/

RUN composer install --optimize-autoloader --no-dev --no-scripts
RUN php artisan key:generate
RUN php artisan migrate --force
RUN php artisan optimize

RUN chmod -R 755 /app


CMD ["sh", "-c", "php artisan octane:start --server=swoole --host=0.0.0.0 --port=8089 --workers=12 --task-workers=12 & /usr/local/openresty/bin/openresty -g 'daemon off;'"]
