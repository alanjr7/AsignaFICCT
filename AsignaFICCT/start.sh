#!/bin/bash

# Entrar a la carpeta del proyecto
cd AsignaFICCT

# Instalar dependencias
composer install --no-dev --optimize-autoloader

# Configurar aplicación
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar servidor
php artisan serve --host=0.0.0.0 --port=$PORT