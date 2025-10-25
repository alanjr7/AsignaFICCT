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

# Pequeña pausa para permitir que la BD esté lista (opcional)
sleep 5

# Ejecutar migraciones con reintentos automáticos
echo "Ejecutando migraciones..."
max_attempts=3
attempt=1

while [ $attempt -le $max_attempts ]; do
    if php artisan migrate --force; then
        echo "Migraciones ejecutadas exitosamente"
        break
    else
        echo "Intento $attempt de $max_attempts falló, reintentando en 5 segundos..."
        sleep 5
        ((attempt++))
    fi
done

# Si fallan todas las migraciones, continuar de todos modos (para resiliencia)
if [ $attempt -gt $max_attempts ]; then
    echo "ADVERTENCIA: No se pudieron ejecutar las migraciones, pero el servidor continuará iniciando"
fi

# Limpiar cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize

# Iniciar Apache en primer plano
echo "Iniciando servidor Apache..."
exec apache2-foreground