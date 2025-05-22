FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# Instalar dependências do sistema
RUN apk add --no-cache \
    zip unzip curl git bash nginx supervisor \
    libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev oniguruma-dev \
    nodejs npm

# Instalar extensões PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql gd mbstring

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar código-fonte da aplicação (volta 1 nível para fora da pasta infra)
COPY ../ /var/www/html/

# Instalar dependências Laravel
RUN composer install --optimize-autoloader --no-dev

# Instalar dependências e build frontend (Vite)
RUN npm install && npm run build

# Ajustar permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Configurar Nginx e Supervisor
COPY infra/docker/nginx.conf.base /etc/nginx/nginx.conf
COPY infra/docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY infra/docker/supervisord.conf /etc/supervisord.conf
COPY infra/docker/entrypoint.sh /entrypoint.sh
COPY infra/.env.defaults /var/www/html/.env

EXPOSE 80

RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
