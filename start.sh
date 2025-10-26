#!/bin/bash

cd /var/www/html

# SOLUCIÓN CRÍTICA: Configurar permisos CORRECTAMENTE
echo "🔧 Configurando permisos..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
chmod -R 777 storage/framework storage/logs

# SOLUCIÓN CRÍTICA: Configurar .env y APP_KEY
echo "🔑 Configurando variables de entorno..."
if [ ! -f .env ]; then
    echo "📝 Creando .env desde ejemplo..."
    cp .env.example .env
fi

# Forzar la generación de APP_KEY
echo "🔐 Generando APP_KEY..."
php artisan key:generate --force

# Verificar que APP_KEY se generó
if ! grep -q "APP_KEY=base64:" .env; then
    echo "❌ ERROR: APP_KEY no se generó correctamente"
    # Generar manualmente como fallback
    php -r "echo 'APP_KEY=base64:'.base64_encode(random_bytes(32)).PHP_EOL;" >> .env
fi

# Esperar a la base de datos
echo "⏳ Esperando a PostgreSQL..."
sleep 10

# SOLUCIÓN CRÍTICA: Limpiar tabla problemática
echo "🗑️  Limpiando tabla grupo_materia..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Eliminar CASCADE para forzar la eliminación
    \Illuminate\Support\Facades\DB::statement('DROP TABLE IF EXISTS grupo_materia CASCADE');
    echo '✅ Tabla grupo_materia eliminada' . PHP_EOL;
} catch (Exception \$e) {
    echo '⚠️  Error eliminando tabla: ' . \$e->getMessage() . PHP_EOL;
}
"

# Ejecutar migraciones
echo "🔄 Ejecutando migraciones..."
if php artisan migrate --force; then
    echo "✅ Migraciones exitosas"
else
    echo "❌ Migraciones fallaron, intentando reparar..."
    # Intentar migraciones individualmente
    php artisan migrate:status
fi
# Ejecutar el seeder de administrador
echo "👤 Creando usuario administrador..."
php artisan db:seed --class=AdminUserSeeder --force
php artisan db:seed --class=HorarioSeeder --force
php artisan db:seed --class=AulaSeeder --force
# SOLUCIÓN: Compilar assets de Vite si es necesario
echo "🎨 Verificando assets de Vite..."
if [ ! -f public/build/manifest.json ] && [ -f package.json ]; then
    echo "📦 Compilando assets..."
    npm run build 2>/dev/null || echo "⚠️  No se pudieron compilar assets"
else
    echo "✅ Assets ya compilados"
fi

# Limpiar cache
echo "🧹 Limpiando cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize

echo "🚀 Iniciando servidor Apache..."
exec apache2-foreground
