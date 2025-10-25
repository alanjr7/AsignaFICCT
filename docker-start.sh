#!/bin/bash

# Navegar al directorio de la aplicación
cd /var/www/html

# Configurar permisos de almacenamiento
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Generar key de Laravel si no existe
if [ ! -f .env ]; then
    cp .env.example .env
fi

php artisan key:generate

# Ejecutar migraciones (opcional - solo en producción)
# php artisan migrate --force

# Limpiar cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Iniciar Apache en segundo plano
apache2-foreground