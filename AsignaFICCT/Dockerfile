# Etapa 1: instalar dependencias PHP y Composer
FROM composer:2 AS build

WORKDIR /app
COPY . /app
RUN composer install --no-dev --optimize-autoloader

# Etapa 2: servidor PHP con Apache
FROM php:8.2-apache

# Instalar extensiones requeridas
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar el proyecto desde la etapa anterior
COPY --from=build /app /var/www/html

# Establecer permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer el puerto para Render
EXPOSE 10000

# Comando para ejecutar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
