# Usar una imagen base con PHP y Apache
FROM php:8.2-apache

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev zip unzip git \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && a2enmod rewrite

# Usar variable de entorno para el puerto
ENV PORT=10000
RUN sed -i "s/80/${PORT}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Configurar ServerName para evitar warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copiar proyecto
COPY . /var/www/html/
WORKDIR /var/www/html

# Configurar document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf

# Permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage bootstrap/cache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# CONFIGURACIÓN CRÍTICA: Forzar PostgreSQL y preparar Laravel
RUN [ -f .env ] || cp .env.example .env

# Asegurar que use PostgreSQL en producción
RUN echo "DB_CONNECTION=pgsql" >> .env && \
    echo "SESSION_DRIVER=database" >> .env && \
    echo "CACHE_STORE=database" >> .env && \
    echo "QUEUE_CONNECTION=database" >> .env

# Generar key
RUN php artisan key:generate --force

# Crear tablas necesarias para sesiones
RUN php artisan session:table

# Ejecutar migraciones (IMPORTANTE)
RUN php artisan migrate --force

# Optimizar Laravel para producción
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE 10000
CMD ["apache2-foreground"]