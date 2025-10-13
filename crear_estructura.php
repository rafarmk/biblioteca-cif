<?php
/**
 * Script para crear estructura de carpetas del proyecto
 * Ejecutar una sola vez desde el navegador: http://localhost/biblioteca-cif/crear_estructura.php
 */

echo "<h2>🚀 Creando estructura de carpetas...</h2>";

// Carpetas principales
$carpetas = [
    'config',
    'core',
    'models',
    'controllers',
    'views',
    'views/layouts',
    'views/auth',
    'views/dashboard',
    'views/libros',
    'views/prestamos',
    'public',
    'public/css',
    'public/js',
    'public/img',
    'public/uploads',
    'public/uploads/libros',
    'public/uploads/usuarios',
    'logs'
];

$creadas = 0;
$existentes = 0;

foreach ($carpetas as $carpeta) {
    if (!file_exists($carpeta)) {
        if (mkdir($carpeta, 0777, true)) {
            echo "✅ Creada: <strong>$carpeta</strong><br>";
            $creadas++;
        } else {
            echo "❌ Error al crear: <strong>$carpeta</strong><br>";
        }
    } else {
        echo "⚠️ Ya existe: <strong>$carpeta</strong><br>";
        $existentes++;
    }
}

echo "<hr>";
echo "<h3>📊 Resumen:</h3>";
echo "✅ Carpetas creadas: <strong>$creadas</strong><br>";
echo "⚠️ Carpetas existentes: <strong>$existentes</strong><br>";
echo "<hr>";
echo "<p>✨ <strong>¡Estructura creada exitosamente!</strong></p>";
echo "<p>Ahora puedes eliminar este archivo (crear_estructura.php) y continuar con el desarrollo.</p>";
?>
