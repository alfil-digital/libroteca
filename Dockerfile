# Usamos la imagen oficial de PHP 8.3 con Apache
FROM php:8.3-apache

# ... (Instalación de dependencias del sistema y extensiones)

# 1. Instalar Composer de forma global y asegurarnos de que sea ejecutable
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

# 2. Configurar el directorio de trabajo
WORKDIR /var/www/html

# 3. Copiar archivos de dependencias
COPY composer.json composer.lock ./

# 4. Ejecutar la instalación usando la ruta completa al binario de composer
RUN php /usr/local/bin/composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --no-interaction

# 5. Copiar el resto del proyecto
COPY . .

# 6. Generar el autoloader final
RUN php /usr/local/bin/composer dump-autoload --optimize --no-dev --no-interaction

# 5. Ajustar el DocumentRoot de Apache para que apunte a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 7. Dar permisos de escritura a las carpetas de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Exponer el puerto 80
EXPOSE 80