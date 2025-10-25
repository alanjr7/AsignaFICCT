#!/bin/bash

cd AsignaFICCT

# Instalar dependencias de PHP
composer install --no-dev --optimize-autoloader

# Generar key de la aplicación
php artisan key:generate

# Ejecutar migraciones
php artisan migrate --force

# Optimizar Laravel
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar el servidor
php artisan serve --host=0.0.0.0 --port=$PORT