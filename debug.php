f<?php
// debug.php - Archivo de diagnóstico

echo "<h2>🔍 Diagnóstico del Sistema</h2>";
echo "<style>body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
      .ok { color: green; } .error { color: red; } .warning { color: orange; }
      .box { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }</style>";

// 1. Verificar versión de PHP
echo "<div class='box'>";
echo "<h3>1. Versión de PHP</h3>";
echo "<p class='ok'>✓ PHP " . phpversion() . "</p>";
echo "</div>";

// 2. Verificar carpetas
echo "<div class='box'>";
echo "<h3>2. Estructura de Carpetas</h3>";
$carpetas = ['modelos', 'controladores'];
foreach ($carpetas as $carpeta) {
    if (is_dir($carpeta)) {
        echo "<p class='ok'>✓ Carpeta '$carpeta' existe</p>";
    } else {
        echo "<p class='error'>✗ Carpeta '$carpeta' NO existe</p>";
    }
}
echo "</div>";

// 3. Verificar archivos
echo "<div class='box'>";
echo "<h3>3. Archivos del Proyecto</h3>";
$archivos = [
    'modelos/Libro.php',
    'controladores/LibroController.php',
    'index.php'
];
foreach ($archivos as $archivo) {
    if (file_exists($archivo)) {
        echo "<p class='ok'>✓ '$archivo' existe</p>";
    } else {
        echo "<p class='error'>✗ '$archivo' NO existe</p>";
    }
}
echo "</div>";

// 4. Verificar archivo de datos
echo "<div class='box'>";
echo "<h3>4. Archivo de Datos (libros.txt)</h3>";
$archivo = 'libros.txt';
if (file_exists($archivo)) {
    echo "<p class='ok'>✓ El archivo 'libros.txt' existe</p>";
    $contenido = file_get_contents($archivo);
    echo "<p>Tamaño: " . strlen($contenido) . " bytes</p>";
    if (strlen($contenido) > 0) {
        echo "<p class='ok'>✓ El archivo tiene contenido</p>";
        echo "<pre style='background: white; padding: 10px; border: 1px solid #ddd;'>";
        echo htmlspecialchars($contenido);
        echo "</pre>";
    } else {
        echo "<p class='warning'>⚠ El archivo está vacío</p>";
    }
} else {
    echo "<p class='warning'>⚠ El archivo 'libros.txt' NO existe todavía</p>";
}
echo "</div>";

// 5. Probar permisos de escritura
echo "<div class='box'>";
echo "<h3>5. Permisos de Escritura</h3>";
$directorioActual = getcwd();
echo "<p>Directorio actual: <code>$directorioActual</code></p>";

if (is_writable('.')) {
    echo "<p class='ok'>✓ El directorio tiene permisos de escritura</p>";
    
    // Intentar crear archivo de prueba
    $test = @file_put_contents('test_permisos.txt', 'test');
    if ($test !== false) {
        echo "<p class='ok'>✓ Se puede crear archivos correctamente</p>";
        @unlink('test_permisos.txt');
    } else {
        echo "<p class='error'>✗ NO se pueden crear archivos</p>";
    }
} else {
    echo "<p class='error'>✗ El directorio NO tiene permisos de escritura</p>";
    echo "<p>Solución: Dar permisos 755 o 777 a la carpeta del proyecto</p>";
}
echo "</div>";

// 6. Probar la clase Libro
echo "<div class='box'>";
echo "<h3>6. Probar Clase Libro</h3>";
if (file_exists('modelos/Libro.php')) {
    require_once 'modelos/Libro.php';
    $libro = new Libro("Libro de Prueba", "Autor Test", "Categoría Test", 2024);
    
    if ($libro->validar()) {
        echo "<p class='ok'>✓ La clase Libro funciona correctamente</p>";
        
        // Intentar guardar
        if ($libro->guardarEnArchivo('libros.txt')) {
            echo "<p class='ok'>✓ Se pudo guardar el libro de prueba</p>";
            echo "<p>Información guardada:</p>";
            echo "<div style='background: white; padding: 10px; border: 1px solid #ddd;'>";
            echo $libro->mostrar();
            echo "</div>";
        } else {
            echo "<p class='error'>✗ NO se pudo guardar el libro</p>";
        }
    } else {
        echo "<p class='error'>✗ Error en la validación del libro</p>";
    }
} else {
    echo "<p class='error'>✗ No se encuentra la clase Libro</p>";
}
echo "</div>";

// 7. Verificar si libros.txt ahora existe
echo "<div class='box'>";
echo "<h3>7. Verificación Final</h3>";
if (file_exists('libros.txt')) {
    $lineas = file('libros.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    echo "<p class='ok'>✓ Archivo 'libros.txt' existe</p>";
    echo "<p>Total de libros: <strong>" . count($lineas) . "</strong></p>";
} else {
    echo "<p class='error'>✗ No se pudo crear el archivo 'libros.txt'</p>";
}
echo "</div>";

echo "<hr>";
echo "<p><a href='index.php'>← Volver al sistema</a></p>";
?>