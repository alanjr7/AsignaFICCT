# Etapa 1: instalar dependencias PHP y Composer
FROM composer:2.7 AS build

WORKDIR /app

# Copiar solo los archivos necesarios para instalar dependencias
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader

# Copiar el resto del código y generar autoloader
COPY . .
RUN composer dump-autoload --optimize --no-dev

# Etapa 2: servidor PHP con Apache
FROM php:8.2-apache

# Instalar extensiones requeridas y herramientas necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && a2enmod rewrite

# Configurar Apache para Laravel
COPY --from=build /app /var/www/html

# Establecer el directorio de trabajo de Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Establecer permisos correctos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Crear archivo .env si no existe y generar key
RUN if [ ! -f /var/www/html/.env ]; then \
    cp /var/www/html/.env.example /var/www/html/.env; \
    fi

# Exponer el puerto para Render
EXPOSE 10000

# Configurar Apache para usar el puerto 10000
RUN echo "Listen 10000" >> /etc/apache2/ports.conf
RUN sed -i 's/80/10000/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Script de inicio para configurar Laravel
COPY docker-start.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-start.sh

CMD ["/usr/local/bin/docker-start.sh"]