<?php
// diagnostico.php
echo "<h2>🔍 Diagnóstico de Permisos</h2>";
echo "<style>body{font-family:Arial;max-width:800px;margin:20px auto;padding:20px;}
      .ok{color:green;background:#d4edda;padding:10px;margin:10px 0;border-radius:5px;}
      .error{color:red;background:#f8d7da;padding:10px;margin:10px 0;border-radius:5px;}
      .info{color:blue;background:#d1ecf1;padding:10px;margin:10px 0;border-radius:5px;}</style>";

// 1. Directorio actual
echo "<div class='info'>";
echo "<strong>📁 Directorio actual:</strong><br>";
echo getcwd() . "<br>";
echo "<strong>Directorio del script:</strong><br>";
echo __DIR__;
echo "</div>";

// 2. Verificar permisos de escritura en el directorio actual
echo "<h3>1. Permisos del directorio</h3>";
if (is_writable('.')) {
    echo "<div class='ok'>✓ El directorio actual TIENE permisos de escritura</div>";
} else {
    echo "<div class='error'>✗ El directorio actual NO tiene permisos de escritura</div>";
    echo "<div class='error'>Solución: Asigna permisos 755 o 777 a la carpeta</div>";
}

// 3. Intentar crear archivo de prueba
echo "<h3>2. Prueba de creación de archivo</h3>";
$testFile = 'test_escritura.txt';
$resultado = @file_put_contents($testFile, "Prueba de escritura\n");

if ($resultado !== false) {
    echo "<div class='ok'>✓ Se pudo crear el archivo '$testFile'</div>";
    echo "<div class='info'>Bytes escritos: $resultado</div>";
    
    // Verificar que existe
    if (file_exists($testFile)) {
        echo "<div class='ok'>✓ El archivo existe y es accesible</div>";
        $contenido = file_get_contents($testFile);
        echo "<div class='info'>Contenido: " . htmlspecialchars($contenido) . "</div>";
        
        // Limpiar
        @unlink($testFile);
        echo "<div class='ok'>✓ Archivo de prueba eliminado</div>";
    }
} else {
    echo "<div class='error'>✗ NO se pudo crear el archivo</div>";
    echo "<div class='error'>Error de PHP: " . error_get_last()['message'] . "</div>";
}

// 4. Verificar si existe libros.txt
echo "<h3>3. Verificar libros.txt</h3>";
if (file_exists('libros.txt')) {
    echo "<div class='ok'>✓ El archivo 'libros.txt' existe</div>";
    echo "<div class='info'>Ruta completa: " . realpath('libros.txt') . "</div>";
    echo "<div class='info'>Tamaño: " . filesize('libros.txt') . " bytes</div>";
    
    $contenido = file_get_contents('libros.txt');
    if (strlen($contenido) > 0) {
        echo "<div class='ok'>✓ El archivo tiene contenido</div>";
        echo "<pre style='background:white;padding:10px;border:1px solid #ccc;'>";
        echo htmlspecialchars($contenido);
        echo "</pre>";
    } else {
        echo "<div class='error'>✗ El archivo está vacío</div>";
    }
} else {
    echo "<div class='error'>✗ El archivo 'libros.txt' NO existe</div>";
}

// 5. Intentar crear libros.txt manualmente
echo "<h3>4. Intentar crear libros.txt</h3>";
$resultado = @file_put_contents('libros.txt', "Libro de Prueba|Autor de Prueba|Categoría de Prueba|2024\n", FILE_APPEND);

if ($resultado !== false) {
    echo "<div class='ok'>✓ Se pudo escribir en 'libros.txt'</div>";
    echo "<div class='info'>Bytes escritos: $resultado</div>";
    
    if (file_exists('libros.txt')) {
        echo "<div class='ok'>✓ Ahora el archivo existe</div>";
        echo "<div class='info'>Ruta: " . realpath('libros.txt') . "</div>";
        
        $contenido = file_get_contents('libros.txt');
        echo "<div class='info'><strong>Contenido actual:</strong></div>";
        echo "<pre style='background:white;padding:10px;border:1px solid #ccc;'>";
        echo htmlspecialchars($contenido);
        echo "</pre>";
    }
} else {
    echo "<div class='error'>✗ NO se pudo escribir en 'libros.txt'</div>";
    $error = error_get_last();
    if ($error) {
        echo "<div class='error'>Error: " . $error['message'] . "</div>";
    }
}

// 6. Verificar clase Libro
echo "<h3>5. Probar clase Libro</h3>";
if (file_exists('modelos/Libro.php')) {
    echo "<div class='ok'>✓ El archivo 'modelos/Libro.php' existe</div>";
    require_once 'modelos/Libro.php';
    
    $libro = new Libro("Test Manual", "Autor Manual", "Categoría Manual", 2024);
    echo "<div class='ok'>✓ Se pudo crear el objeto Libro</div>";
    
    if ($libro->validar()) {
        echo "<div class='ok'>✓ Los datos son válidos</div>";
        
        echo "<div class='info'>Intentando guardar...</div>";
        $guardado = $libro->guardarEnArchivo('libros.txt');
        
        if ($guardado) {
            echo "<div class='ok'>✓ El método guardarEnArchivo() retornó TRUE</div>";
        } else {
            echo "<div class='error'>✗ El método guardarEnArchivo() retornó FALSE</div>";
        }
        
        // Verificar nuevamente
        if (file_exists('libros.txt')) {
            $contenido = file_get_contents('libros.txt');
            echo "<div class='info'><strong>Contenido final de libros.txt:</strong></div>";
            echo "<pre style='background:white;padding:10px;border:1px solid #ccc;'>";
            echo htmlspecialchars($contenido);
            echo "</pre>";
            
            $lineas = file('libros.txt', FILE_IGNORE_NEW_LINES);
            echo "<div class='ok'>Total de líneas: " . count($lineas) . "</div>";
        }
    }
} else {
    echo "<div class='error'>✗ No se encuentra 'modelos/Libro.php'</div>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Volver al inicio</a> | <a href='index.php?c=libro&a=listar'>Ver lista de libros</a></p>";
?>