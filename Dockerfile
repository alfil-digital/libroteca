# Usamos la imagen oficial de PHP 8.3 con Apache
FROM php:8.3-apache

# ... (Instalación de dependencias del sistema y extensiones)

# ... (Pasos iniciales de instalación de extensiones igual)

# 1. Asegurar Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

WORKDIR /var/www/html

# 2. Copiar solo los archivos de configuración
COPY composer.json composer.lock ./

# 3. Instalación de dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 4. Instalación ULTRA LIGERA
# --no-dev: quita cosas de testing que no necesitamos
# --preferred-dist: baja zips, no usa git (ahorra mucha RAM)
# --no-autoloader: pospone la parte pesada de indexar clases
RUN php -d memory_limit=-1 /usr/local/bin/composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --no-interaction \
    --preferred-dist

# 5. Copiar el resto del código
COPY . .

# 6. Generar autoloader al final, con un límite de memoria forzado
RUN php -d memory_limit=-1 /usr/local/bin/composer dump-autoload --no-dev --optimize

# 7. Ajustar el DocumentRoot de Apache para que apunte a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 8. Dar permisos de escritura a las carpetas de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Exponer el puerto 80
EXPOSE 80