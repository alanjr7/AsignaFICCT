#!/bin/bash

cd /var/www/html

# Configurar permisos
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Crear .env si no existe y configurar variables básicas
if [ ! -f .env ]; then
    cp .env.example .env
    # Configuración básica para Laravel
    echo "APP_ENV=production" >> .env
    echo "APP_DEBUG=false" >> .env
    echo "LOG_CHANNEL=stderr" >> .env
fi

# Generar APP_KEY solo si no existe
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Esperar a que la base de datos esté lista
echo "⏳ Esperando a que la base de datos esté lista..."
sleep 10

# Verificar conexión a la base de datos de forma simple
echo "🔍 Verificando conexión a la base de datos..."
if php -r "
try {
    \$pdo = new PDO('pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    echo '✅ Conexión a BD exitosa' . PHP_EOL;
    exit(0);
} catch (Exception \$e) {
    echo '❌ Error conectando a BD: ' . \$e->getMessage() . PHP_EOL;
    exit(1);
}
"; then
    echo "✅ Conexión verificada"
else
    echo "⚠️  No se pudo verificar la conexión, continuando de todos modos..."
fi

# SOLUCIÓN TEMPORAL: Crear tabla aulas si no existe
echo "📊 Verificando tabla aulas..."
php -r "
try {
    require_once 'vendor/autoload.php';
    \$app = require_once 'bootstrap/app.php';
    \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    if (!\Illuminate\Support\Facades\Schema::hasTable('aulas')) {
        \Illuminate\Support\Facades\DB::statement('CREATE TABLE IF NOT EXISTS aulas (
            id BIGSERIAL PRIMARY KEY,
            nombre VARCHAR(255),
            capacidad INTEGER,
            ubicacion VARCHAR(255) NULL,
            created_at TIMESTAMP(0) DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP(0) DEFAULT CURRENT_TIMESTAMP
        )');
        echo '✅ Tabla aulas verificada/creada' . PHP_EOL;
    } else {
        echo '✅ Tabla aulas ya existe' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo '⚠️  Error con tabla aulas: ' . \$e->getMessage() . PHP_EOL;
}
"

# Ejecutar migraciones con reintentos
echo "🔄 Ejecutando migraciones..."
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
    echo "⚠️  No se pudieron ejecutar las migraciones, continuando..."
fi

# Limpiar y optimizar
echo "🧹 Limpiando cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize

echo "🚀 Iniciando servidor Apache..."
exec apache2-foreground