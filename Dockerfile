# Usar una imagen base con PHP y Apache
FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && a2enmod rewrite

# Copiar el proyecto completo
COPY . /var/www/html/

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Configurar el document root de Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configurar el puerto para Render
RUN echo "Listen 10000" >> /etc/apache2/ports.conf
RUN sed -i 's/80/10000/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Establecer permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Generar key de Laravel
RUN if [ ! -f .env ]; then \
    cp .env.example .env; \
    fi
RUN php artisan key:generate

# Limpiar cache
RUN php artisan config:clear

# Exponer el puerto
EXPOSE 10000

# Comando por defecto de Apache
CMD ["apache2-foreground"]