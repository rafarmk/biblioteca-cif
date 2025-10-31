<?php
require_once 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportarController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        require_once 'views/importar/index.php';
    }

    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
            $archivo = $_FILES['archivo'];

            if ($archivo['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error'] = "Error al subir el archivo";
                header("Location: index.php?ruta=importar");
                exit();
            }

            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), ['xlsx', 'xls'])) {
                $_SESSION['error'] = "Solo se permiten archivos Excel (.xlsx, .xls)";
                header("Location: index.php?ruta=importar");
                exit();
            }

            try {
                $spreadsheet = IOFactory::load($archivo['tmp_name']);
                $sheet = $spreadsheet->getActiveSheet();
                $highestRow = $sheet->getHighestRow();

                $librosImportados = 0;
                $errores = [];

                for ($row = 5; $row <= $highestRow; $row++) {
                    try {
                        $idLibro = $sheet->getCell('B' . $row)->getValue();
                        $titulo = $sheet->getCell('C' . $row)->getValue();
                        $autor = $sheet->getCell('D' . $row)->getValue();
                        $genero = $sheet->getCell('E' . $row)->getValue();
                        $anio = $sheet->getCell('F' . $row)->getValue();
                        $estado = $sheet->getCell('G' . $row)->getValue();
                        $ubicacion = $sheet->getCell('H' . $row)->getValue();
                        $observaciones = $sheet->getCell('I' . $row)->getValue();
                        $ejemplares = $sheet->getCell('J' . $row)->getValue();

                        if (empty($idLibro) && empty($titulo) && empty($autor)) {
                            continue;
                        }

                        $titulo = trim($titulo ?? '');
                        $autor = trim($autor ?? '');

                        if (empty($titulo) || empty($autor)) {
                            $errores[] = "Fila $row: Título o Autor vacío";
                            continue;
                        }

                        // Truncar textos largos para que quepan en la BD
                        $titulo = mb_substr($titulo, 0, 200);
                        $autor = mb_substr($autor, 0, 500);
                        $idLibro = mb_substr($idLibro ?? '', 0, 20);

                        // Verificar si el ISBN ya existe (solo si no está vacío)
                        if (!empty($idLibro)) {
                            $checkQuery = "SELECT COUNT(*) FROM libros WHERE isbn = :isbn";
                            $checkStmt = $this->db->prepare($checkQuery);
                            $checkStmt->execute([':isbn' => $idLibro]);
                            if ($checkStmt->fetchColumn() > 0) {
                                $errores[] = "Fila $row: ISBN '$idLibro' ya existe (duplicado - saltado)";
                                continue;
                            }
                        }

                        $query = "INSERT INTO libros 
                                  (titulo, autor, isbn, categoria, anio_publicacion, ubicacion, 
                                   cantidad_total, cantidad_disponible, descripcion, estado)
                                  VALUES 
                                  (:titulo, :autor, :isbn, :categoria, :anio, :ubicacion, 
                                   :cantidad_total, :cantidad_disponible, :descripcion, :estado)";

                        $stmt = $this->db->prepare($query);
                        $stmt->execute([
                            ':titulo' => $titulo,
                            ':autor' => $autor,
                            ':isbn' => $idLibro ?? '',
                            ':categoria' => $genero ?? '',
                            ':anio' => !empty($anio) ? intval($anio) : null,
                            ':ubicacion' => $ubicacion ?? 'ESTANTE',
                            ':cantidad_total' => !empty($ejemplares) ? intval($ejemplares) : 1,
                            ':cantidad_disponible' => !empty($ejemplares) ? intval($ejemplares) : 1,
                            ':descripcion' => $observaciones ?? '',
                            ':estado' => 'disponible'
                        ]);

                        $librosImportados++;
                    } catch (Exception $e) {
                        $errores[] = "Fila $row: " . $e->getMessage();
                    }
                }

                $mensaje = "Se importaron $librosImportados libros exitosamente";
                if (count($errores) > 0) {
                    $mensaje .= " con " . count($errores) . " errores";
                }

                $_SESSION['mensaje'] = $mensaje;
                $_SESSION['errores_importacion'] = $errores;
                header("Location: index.php?ruta=importar&resultado=1");
                exit();

            } catch (Exception $e) {
                $_SESSION['error'] = "Error al procesar el archivo: " . $e->getMessage();
                header("Location: index.php?ruta=importar");
                exit();
            }
        } else {
            header("Location: index.php?ruta=importar");
            exit();
        }
    }
}
?>