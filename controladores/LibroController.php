<?php
// controladores/LibroController.php

require_once 'modelos/Libro.php';

class LibroController {

    // Mostrar formulario de registro
    public function form() {
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Registro de Libros</title>
            <style>
                body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
                h2 { color: #333; }
                .form-group { margin-bottom: 15px; }
                label { display: block; margin-bottom: 5px; font-weight: bold; }
                input, select { width: 100%; padding: 8px; box-sizing: border-box; }
                button { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; }
                button:hover { background-color: #45a049; }
                .error { color: red; }
                .success { color: green; }
            </style>
        </head>
        <body>
            <h2>📚 Registro de Libros</h2>
            <form action="index.php" method="POST">
                <input type="hidden" name="c" value="libro">
                <input type="hidden" name="a" value="guardar">
                
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>

                <div class="form-group">
                    <label for="autor">Autor:</label>
                    <input type="text" id="autor" name="autor" required>
                </div>

                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select id="categoria" name="categoria" required>
                        <option value="">Seleccione una categoría</option>
                        <option value="Investigación">Investigación</option>
                        <option value="Ficción">Ficción</option>
                        <option value="Ciencia">Ciencia</option>
                        <option value="Historia">Historia</option>
                        <option value="Tecnología">Tecnología</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="anio">Año:</label>
                    <input type="number" id="anio" name="anio" min="1900" max="<?php echo date('Y'); ?>" required>
                </div>

                <button type="submit">Registrar Libro</button>
            </form>

            <p><a href="index.php?c=libro&a=listar">Ver todos los libros</a></p>
        </body>
        </html>
        <?php
    }

    // Guardar libro
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener y sanitizar datos
            $titulo = htmlspecialchars($_POST['titulo'] ?? '');
            $autor = htmlspecialchars($_POST['autor'] ?? '');
            $categoria = htmlspecialchars($_POST['categoria'] ?? '');
            $anio = filter_var($_POST['anio'] ?? '', FILTER_VALIDATE_INT);

            // Debug: mostrar datos recibidos
            echo "<!-- DEBUG: titulo=$titulo, autor=$autor, categoria=$categoria, anio=$anio -->";

            // Crear objeto libro
            $libro = new Libro($titulo, $autor, $categoria, $anio);

            // Validar
            if ($libro->validar()) {
                // Guardar en archivo usando ruta absoluta
                $rutaArchivo = __DIR__ . '/../libros.txt';
                
                // Construir datos manualmente
                $datos = "{$titulo}|{$autor}|{$categoria}|{$anio}\n";
                $resultado = file_put_contents($rutaArchivo, $datos, FILE_APPEND | LOCK_EX);
                
                if ($resultado !== false) {
                    ?>
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <title>Libro Guardado</title>
                        <style>
                            body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
                            .success { color: green; background-color: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; }
                            .libro-info { margin: 20px 0; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #4CAF50; }
                            .debug { background: #fff3cd; padding: 10px; margin: 10px 0; border-radius: 5px; font-size: 0.9em; }
                        </style>
                    </head>
                    <body>
                        <div class="success">
                            <h3>✓ Libro registrado correctamente</h3>
                        </div>
                        <div class="debug">
                            📁 Guardado en: <?php echo $rutaArchivo; ?><br>
                            📊 Bytes escritos: <?php echo $resultado; ?>
                        </div>
                        <div class="libro-info">
                            <?php echo $libro->mostrar(); ?>
                        </div>
                        <p><a href="index.php?c=libro&a=form">Registrar otro libro</a></p>
                        <p><a href="index.php?c=libro&a=listar">Ver todos los libros</a></p>
                    </body>
                    </html>
                    <?php
                } else {
                    echo "<p style='color: red; padding: 15px; background: #f8d7da;'>✗ Error al guardar el libro en: $rutaArchivo</p>";
                    echo "<p>Error de PHP: " . error_get_last()['message'] . "</p>";
                }
            } else {
                echo "<p style='color: red; padding: 15px; background: #f8d7da;'>✗ Error: Faltan datos obligatorios.</p>";
                echo "<p>Datos recibidos - Título: '$titulo', Autor: '$autor', Categoría: '$categoria', Año: '$anio'</p>";
            }
        } else {
            header('Location: index.php?c=libro&a=form');
        }
    }

    // Listar todos los libros
    public function listar() {
        // Obtener ruta absoluta del archivo
        $archivo = __DIR__ . '/../libros.txt';
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Lista de Libros</title>
            <style>
                body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
                th { background-color: #4CAF50; color: white; }
                tr:hover { background-color: #f5f5f5; }
                .debug { background: #fff3cd; padding: 10px; margin: 10px 0; border-radius: 5px; font-size: 0.9em; }
                .error { background: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 5px; }
                .success { background: #d4edda; color: #155724; padding: 10px; margin: 10px 0; border-radius: 5px; }
                .actions { margin: 20px 0; }
                .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; }
                .btn:hover { background: #0056b3; }
                .btn-danger { background: #dc3545; }
                .btn-danger:hover { background: #c82333; }
            </style>
        </head>
        <body>
            <h2>📚 Lista de Libros Registrados</h2>
            
            <?php
            // Mostrar información de depuración
            echo "<div class='debug'>";
            echo "🔍 <strong>Ruta del archivo:</strong> " . $archivo . "<br>";
            echo "📁 <strong>Archivo existe:</strong> " . (file_exists($archivo) ? "✓ SÍ" : "✗ NO") . "<br>";
            if (file_exists($archivo)) {
                clearstatcache(); // Limpiar caché de estado de archivos
                echo "📊 <strong>Tamaño:</strong> " . filesize($archivo) . " bytes<br>";
                echo "🔐 <strong>Permisos:</strong> " . substr(sprintf('%o', fileperms($archivo)), -4) . "<br>";
                echo "⏰ <strong>Última modificación:</strong> " . date("Y-m-d H:i:s", filemtime($archivo));
            }
            echo "</div>";
            
            if (file_exists($archivo)) {
                clearstatcache();
                $contenido = file_get_contents($archivo);
                
                // Mostrar contenido raw para debug
                echo "<div class='debug'>";
                echo "📄 <strong>Contenido raw del archivo:</strong><br>";
                echo "<pre style='background:white;padding:10px;border:1px solid #ccc;max-height:150px;overflow:auto;'>";
                echo htmlspecialchars($contenido);
                echo "</pre>";
                echo "</div>";
                
                if (strlen($contenido) > 0) {
                    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    
                    if (count($lineas) > 0) {
                        echo "<div class='success'>✓ Se encontraron " . count($lineas) . " libro(s)</div>";
                        echo "<table>";
                        echo "<tr><th>#</th><th>Título</th><th>Autor</th><th>Categoría</th><th>Año</th></tr>";
                        
                        $contador = 1;
                        foreach ($lineas as $linea) {
                            $linea = trim($linea);
                            if (empty($linea)) continue;
                            
                            $datos = explode('|', $linea);
                            if (count($datos) === 4) {
                                echo "<tr>";
                                echo "<td>" . $contador++ . "</td>";
                                echo "<td>" . htmlspecialchars(trim($datos[0])) . "</td>";
                                echo "<td>" . htmlspecialchars(trim($datos[1])) . "</td>";
                                echo "<td>" . htmlspecialchars(trim($datos[2])) . "</td>";
                                echo "<td>" . htmlspecialchars(trim($datos[3])) . "</td>";
                                echo "</tr>";
                            } else {
                                echo "<tr style='background:#ffeeee;'><td colspan='5'>⚠️ Línea con formato inválido: " . htmlspecialchars($linea) . " (campos: " . count($datos) . ")</td></tr>";
                            }
                        }
                        echo "</table>";
                    } else {
                        echo "<div class='error'>⚠️ El archivo existe pero no tiene líneas válidas</div>";
                    }
                } else {
                    echo "<div class='error'>⚠️ El archivo existe pero está vacío (0 bytes)</div>";
                }
            } else {
                echo "<div class='error'>⚠️ El archivo 'libros.txt' aún no existe. Registra el primer libro para crearlo.</div>";
            }
            ?>
            
            <div class="actions">
                <a href="index.php?c=libro&a=form" class="btn">➕ Registrar nuevo libro</a>
                <?php if (file_exists($archivo)): ?>
                    <a href="?c=libro&a=limpiar" class="btn btn-danger" onclick="return confirm('¿Seguro que quieres eliminar todos los libros?')">🗑️ Limpiar todos</a>
                <?php endif; ?>
            </div>
        </body>
        </html>
        <?php
    }

    // Método para limpiar el archivo
    public function limpiar() {
        $archivo = __DIR__ . '/../libros.txt';
        if (file_exists($archivo)) {
            unlink($archivo);
        }
        header('Location: index.php?c=libro&a=listar');
        exit;
    }
}
?>