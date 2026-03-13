FROM php:8.3-apache

# 1. Instalamos solo lo mínimo indispensable y usamos el comando docker-php-ext-install
# de forma que no sature la memoria del servidor gratuito de Render.
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    && docker-php-ext-install pdo_mysql bcmath gd

# 2. Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# 3. Copiar Composer desde la imagen oficial (más rápido que bajarlo)
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# 4. Copiar archivos de configuración PRIMERO
COPY composer.json composer.lock ./

# 5. Instalar dependencias SIN optimizar todavía (para que no colapse la RAM)
# Usamos --no-autoloader para que no escanee miles de archivos ahora.
RUN php -d memory_limit=-1 /usr/local/bin/composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --no-interaction \
    --prefer-dist

# 6. Ahora sí, copiar el resto del proyecto
COPY . .

# 7. Generar el autoloader al final (paso liviano)
RUN php -d memory_limit=-1 /usr/local/bin/composer dump-autoload --optimize --no-dev --no-interaction

# 8. Ajustar DocumentRoot de Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 9. Permisos finales
RUN chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache