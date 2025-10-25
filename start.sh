#!/bin/bash

cd /var/www/html

# Configurar permisos
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Generar key si no existe
if [ ! -f .env ]; then
    cp .env.example .env
fi

if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Esperar a que la base de datos de Railway esté lista
echo "Esperando a que la base de datos esté lista..."
sleep 10

# Verificar conexión a la base de datos
echo "Verificando conexión a la base de datos..."
if php artisan tinker --execute "
    try {
        \DB::connection()->getPdo();
        echo 'Conexión a la base de datos exitosa' . PHP_EOL;
        exit(0);
    } catch (\Exception \$e) {
        echo 'Error conectando a la base de datos: ' . \$e->getMessage() . PHP_EOL;
        exit(1);
    }
"; then
    echo "Conexión exitosa, ejecutando migraciones..."
else
    echo "No se pudo conectar a la base de datos"
    exit 1
fi

# SOLUCIÓN TEMPORAL: Crear tabla aulas si no existe (para el error anterior)
echo "Verificando tabla aulas..."
php artisan tinker --execute "
    try {
        if (!\\Schema::hasTable('aulas')) {
            \\DB::statement('CREATE TABLE aulas (
                id BIGSERIAL PRIMARY KEY,
                nombre VARCHAR(255),
                capacidad INTEGER,
                ubicacion VARCHAR(255) NULL,
                created_at TIMESTAMP(0) DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP(0) DEFAULT CURRENT_TIMESTAMP
            )');
            echo 'Tabla aulas creada exitosamente' . PHP_EOL;
        } else {
            echo 'Tabla aulas ya existe' . PHP_EOL;
        }
    } catch (\\Exception \$e) {
        echo 'Error con tabla aulas: ' . \$e->getMessage() . PHP_EOL;
    }
"

# Ejecutar migraciones con reintentos
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

if [ $attempt -gt $max_attempts ]; then
    echo "ADVERTENCIA: No se pudieron ejecutar las migraciones, pero el servidor continuará iniciando"
fi

# Limpiar cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize

echo "Iniciando servidor Apache..."
exec apache2-foreground