<?php
require_once 'config/conexion.php';

class ImportarController {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function index() {
        // Mostrar formulario
        require_once 'views/importar/index.php';
    }
    
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?ruta=importar');
            exit();
        }
        
        // Verificar que se subió un archivo
        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "Por favor seleccione un archivo Excel válido";
            header('Location: index.php?ruta=importar');
            exit();
        }
        
        $archivo = $_FILES['archivo'];
        
        // Verificar extensión
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ['xlsx', 'xls'])) {
            $_SESSION['error'] = "Solo se permiten archivos Excel (.xlsx o .xls)";
            header('Location: index.php?ruta=importar');
            exit();
        }
        
        // Mover archivo a carpeta uploads
        $nombreArchivo = 'import_' . time() . '.' . $extension;
        $rutaArchivo = 'uploads/' . $nombreArchivo;
        
        if (!move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
            $_SESSION['error'] = "Error al subir el archivo";
            header('Location: index.php?ruta=importar');
            exit();
        }
        
        // Procesar el archivo
        try {
            $resultado = $this->procesarExcel($rutaArchivo);
            
            // Eliminar archivo temporal
            unlink($rutaArchivo);
            
            // Mostrar resultados
            $_SESSION['resultado_importacion'] = $resultado;
            header('Location: index.php?ruta=importar');
            exit();
            
        } catch (Exception $e) {
            // Eliminar archivo temporal
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
            
            $_SESSION['error'] = "Error al procesar el archivo: " . $e->getMessage();
            header('Location: index.php?ruta=importar');
            exit();
        }
    }
    
    private function procesarExcel($rutaArchivo) {
        require_once 'vendor/autoload.php';
        
        // Cargar el archivo Excel
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaArchivo);
        $sheet = $spreadsheet->getActiveSheet();
        
        $resultado = [
            'total' => 0,
            'exitosos' => 0,
            'errores' => 0,
            'actualizados' => 0,
            'detalles' => []
        ];
        
        // Los datos empiezan en la fila 5 (fila 4 son encabezados)
        $filaInicio = 5;
        $filaFin = $sheet->getHighestRow();
        
        // Iniciar transacción
        $this->pdo->beginTransaction();
        
        try {
            for ($fila = $filaInicio; $fila <= $filaFin; $fila++) {
                // Leer datos de la fila
                $isbn = trim($sheet->getCell("C{$fila}")->getValue() ?? '');
                $titulo = trim($sheet->getCell("D{$fila}")->getValue() ?? '');
                $autor = trim($sheet->getCell("E{$fila}")->getValue() ?? '');
                $genero = trim($sheet->getCell("F{$fila}")->getValue() ?? '');
                $anio = $sheet->getCell("G{$fila}")->getValue();
                $estado = trim($sheet->getCell("H{$fila}")->getValue() ?? '');
                $ubicacion = trim($sheet->getCell("I{$fila}")->getValue() ?? '');
                $observaciones = trim($sheet->getCell("J{$fila}")->getValue() ?? '');
                $ejemplares = $sheet->getCell("K{$fila}")->getValue() ?? 1;
                
                $resultado['total']++;
                
                // Validar campos obligatorios
                if (empty($titulo) || empty($autor)) {
                    $resultado['errores']++;
                    $resultado['detalles'][] = [
                        'fila' => $fila,
                        'estado' => 'error',
                        'mensaje' => 'Título y autor son obligatorios',
                        'titulo' => $titulo ?: 'SIN TÍTULO'
                    ];
                    continue;
                }
                
                // Buscar o crear categoría
                $categoria_id = $this->obtenerOCrearCategoria($genero);
                
                // Verificar si el libro ya existe (por ISBN o título+autor)
                $libroExistente = $this->buscarLibro($isbn, $titulo, $autor);
                
                if ($libroExistente) {
                    // Actualizar libro existente
                    $this->actualizarLibro(
                        $libroExistente['id'],
                        $titulo,
                        $autor,
                        $isbn,
                        $categoria_id,
                        $anio,
                        $ubicacion,
                        $observaciones,
                        $ejemplares
                    );
                    
                    $resultado['actualizados']++;
                    $resultado['detalles'][] = [
                        'fila' => $fila,
                        'estado' => 'actualizado',
                        'mensaje' => 'Libro actualizado',
                        'titulo' => $titulo
                    ];
                } else {
                    // Insertar nuevo libro
                    $this->insertarLibro(
                        $titulo,
                        $autor,
                        $isbn,
                        $categoria_id,
                        $anio,
                        $ubicacion,
                        $observaciones,
                        $ejemplares
                    );
                    
                    $resultado['exitosos']++;
                    $resultado['detalles'][] = [
                        'fila' => $fila,
                        'estado' => 'exitoso',
                        'mensaje' => 'Libro importado',
                        'titulo' => $titulo
                    ];
                }
            }
            
            // Confirmar transacción
            $this->pdo->commit();
            
        } catch (Exception $e) {
            // Revertir cambios en caso de error
            $this->pdo->rollBack();
            throw $e;
        }
        
        return $resultado;
    }
    
    private function obtenerOCrearCategoria($nombre) {
        if (empty($nombre)) {
            $nombre = 'Sin categoría';
        }
        
        // Buscar categoría existente
        $query = "SELECT id FROM categorias WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':nombre' => $nombre]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($categoria) {
            return $categoria['id'];
        }
        
        // Crear nueva categoría
        $query = "INSERT INTO categorias (nombre) VALUES (:nombre)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':nombre' => $nombre]);
        
        return $this->pdo->lastInsertId();
    }
    
    private function buscarLibro($isbn, $titulo, $autor) {
        // Buscar por ISBN si existe
        if (!empty($isbn)) {
            $query = "SELECT id FROM libros WHERE isbn = :isbn LIMIT 1";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':isbn' => $isbn]);
            $libro = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($libro) {
                return $libro;
            }
        }
        
        // Buscar por título y autor
        $query = "SELECT id FROM libros WHERE titulo = :titulo AND autor = :autor LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':titulo' => $titulo, ':autor' => $autor]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    private function insertarLibro($titulo, $autor, $isbn, $categoria_id, $anio, $ubicacion, $descripcion, $cantidad) {
        $query = "INSERT INTO libros 
                  (titulo, autor, isbn, categoria_id, anio_publicacion, ubicacion, 
                   descripcion, cantidad_total, cantidad_disponible) 
                  VALUES 
                  (:titulo, :autor, :isbn, :categoria_id, :anio, :ubicacion, 
                   :descripcion, :cantidad, :cantidad)";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':titulo' => $titulo,
            ':autor' => $autor,
            ':isbn' => $isbn,
            ':categoria_id' => $categoria_id,
            ':anio' => $anio,
            ':ubicacion' => $ubicacion,
            ':descripcion' => $descripcion,
            ':cantidad' => $cantidad
        ]);
    }
    
    private function actualizarLibro($id, $titulo, $autor, $isbn, $categoria_id, $anio, $ubicacion, $descripcion, $cantidad) {
        $query = "UPDATE libros 
                  SET titulo = :titulo,
                      autor = :autor,
                      isbn = :isbn,
                      categoria_id = :categoria_id,
                      anio_publicacion = :anio,
                      ubicacion = :ubicacion,
                      descripcion = :descripcion,
                      cantidad_total = :cantidad,
                      cantidad_disponible = cantidad_disponible + (:cantidad - cantidad_total)
                  WHERE id = :id";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':id' => $id,
            ':titulo' => $titulo,
            ':autor' => $autor,
            ':isbn' => $isbn,
            ':categoria_id' => $categoria_id,
            ':anio' => $anio,
            ':ubicacion' => $ubicacion,
            ':descripcion' => $descripcion,
            ':cantidad' => $cantidad
        ]);
    }
}