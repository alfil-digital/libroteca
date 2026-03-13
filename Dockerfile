# Usamos la imagen oficial de PHP 8.3 con Apache
FROM php:8.3-apache

# ... (Instalación de dependencias del sistema y extensiones)

# 1. Copiar solo los archivos de composer primero (esto ayuda a la caché de Docker)
COPY composer.json composer.lock ./

# 2. Instalar paquetes sin ejecutar scripts ni generar el autoloader pesado
RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --no-interaction

# 3. Copiar el resto del código del proyecto
COPY . .

# 4. Generar el autoloader optimizado como un paso final y separado
RUN COMPOSER_MEMORY_LIMIT=-1 composer dump-autoload --optimize --no-dev --no-interaction

# ... (Configuración de Apache y permisos)

# 5. Ajustar el DocumentRoot de Apache para que apunte a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 6. Instalar dependencias de PHP
# Instalamos dependencias con límite de memoria liberado
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader --no-interaction

# Ahora generamos el autoloader de forma aislada
RUN COMPOSER_MEMORY_LIMIT=-1 composer dump-autoload --optimize --no-dev --no-interaction

# 7. Dar permisos de escritura a las carpetas de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Exponer el puerto 80
EXPOSE 80