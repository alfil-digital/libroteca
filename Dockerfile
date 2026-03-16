FROM php:8.3-apache

# 1. Instalar dependencias del sistema y Node.js (necesario para Vite)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql bcmath gd

RUN a2enmod rewrite

# 2. Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# 3. Instalar dependencias de PHP (paso liviano que ya teníamos)
COPY composer.json composer.lock ./
RUN php -d memory_limit=-1 /usr/local/bin/composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --no-interaction \
    --prefer-dist

# 4. Instalar dependencias de Node y compilar assets de Vite
COPY package.json package-lock.json* ./
RUN npm install
COPY . .
RUN npm run build  # <--- Esto genera el manifest.json que falta

# 5. Finalizar PHP
RUN php -d memory_limit=-1 /usr/local/bin/composer dump-autoload --optimize --no-dev --no-interaction

# 6. Configuración de Apache y permisos
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

RUN chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache