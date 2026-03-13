# Usamos la imagen oficial de PHP 8.3 con Apache
FROM php:8.3-apache

# 1. Instalar dependencias del sistema y extensiones de PHP para MySQL/Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 2. Habilitar el módulo rewrite de Apache (vital para las rutas de Laravel)
RUN a2enmod rewrite



# 3. Instalar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Configurar el directorio de trabajo
WORKDIR /var/www/html
COPY . .

# 5. Ajustar el DocumentRoot de Apache para que apunte a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 6. Instalar dependencias de PHP
# Instalamos dependencias con límite de memoria liberado
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader --no-interaction

# 7. Dar permisos de escritura a las carpetas de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Exponer el puerto 80
EXPOSE 80