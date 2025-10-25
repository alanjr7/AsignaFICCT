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

# Solo generar key si no existe
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Esperar a que la base de datos esté disponible (opcional pero recomendado)
echo "Esperando a que la base de datos esté disponible..."
while ! nc -z $DB_HOST $DB_PORT; do
  sleep 1
done
echo "Base de datos disponible!"

# Ejecutar migraciones solo si APP_ENV no es local (para producción)
if [ "$APP_ENV" != "local" ]; then
    php artisan migrate --force
fi

# Limpiar cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize

# Iniciar Apache en primer plano
exec apache2-foreground