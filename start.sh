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

# Esperar a que la base de datos esté lista
echo "Esperando a que la base de datos esté lista..."
sleep 10

# Verificar conexión a la base de datos de forma más simple
echo "Verificando conexión a la base de datos..."
timeout 30 bash -c 'until php -r "try { 
    new PDO(\"pgsql:host=\" . getenv(\"DB_HOST\") . \";port=\" . getenv(\"DB_PORT\") . \";dbname=\" . getenv(\"DB_DATABASE\"), 
    getenv(\"DB_USERNAME\"), getenv(\"DB_PASSWORD\")); 
    echo \"Conexión exitosa\"; 
    exit(0);
} catch (Exception \$e) { 
    echo \"Error: \" . \$e->getMessage(); 
    exit(1);
}" 2>/dev/null; do 
    echo "Reintentando conexión en 5 segundos...";
    sleep 5; 
done'

if [ $? -eq 0 ]; then
    echo "✅ Conexión a BD exitosa"
else
    echo "❌ No se pudo conectar a la base de datos después de 30 segundos"
    # Continuar de todos modos para resiliencia
fi

# SOLUCIÓN TEMPORAL: Crear tabla aulas si no existe
echo "Verificando tabla aulas..."
php -r "
try {
    require_once 'vendor/autoload.php';
    \$app = require_once 'bootstrap/app.php';
    \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    if (!\Illuminate\Support\Facades\Schema::hasTable('aulas')) {
        \Illuminate\Support\Facades\DB::statement('CREATE TABLE aulas (
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
} catch (Exception \$e) {
    echo 'Error con tabla aulas: ' . \$e->getMessage() . PHP_EOL;
}
"

# Ejecutar migraciones con reintentos
echo "Ejecutando migraciones..."
max_attempts=3
attempt=1

while [ $attempt -le $max_attempts ]; do
    if php artisan migrate --force; then
        echo "✅ Migraciones ejecutadas exitosamente"
        break
    else
        echo "❌ Intento $attempt de $max_attempts falló, reintentando en 5 segundos..."
        sleep 5
        ((attempt++))
    fi
done

if [ $attempt -gt $max_attempts ]; then
    echo "⚠️ ADVERTENCIA: No se pudieron ejecutar las migraciones, pero el servidor continuará iniciando"
fi

# Limpiar y optimizar
echo "Limpiando cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize

echo "🚀 Iniciando servidor Apache..."
exec apache2-foreground