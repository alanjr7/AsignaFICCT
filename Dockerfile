# Usar una imagen base con PHP y Apache
FROM php:8.2-apache

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && a2enmod rewrite

# Instalar Node.js y npm para Vite
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Usar variable de entorno para el puerto
ENV PORT=10000
RUN sed -i "s/80/${PORT}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Configurar ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copiar proyecto
COPY . /var/www/html/
WORKDIR /var/www/html

# Configurar document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf

# Permisos básicos durante build
RUN chmod -R 755 storage bootstrap/cache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Compilar assets durante build
RUN npm install && npm run build

EXPOSE 10000

# Script de inicio corregido
COPY start.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/start.sh
CMD ["/usr/local/bin/start.sh"]