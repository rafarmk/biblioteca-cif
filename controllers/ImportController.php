<?php
require_once 'config/Database.php';
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
            $this->importar();
        } else {
            require_once 'views/layouts/navbar.php';
            require_once 'views/importar/index.php';
        }
    }

    private function importar() {
        try {
            $archivo = $_FILES['archivo'];
            
            if ($archivo['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Error al subir el archivo');
            }

            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
                throw new Exception('Formato no válido. Use Excel (.xlsx, .xls) o CSV');
            }

            $spreadsheet = IOFactory::load($archivo['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Saltar encabezados
            array_shift($rows);

            $importados = 0;
            $errores = 0;

            foreach ($rows as $row) {
                if (empty($row[0])) continue; // Saltar filas vacías

                try {
                    $query = "INSERT INTO libros (titulo, autor, isbn, categoria, editorial, anio_publicacion, 
                              ubicacion, cantidad_total, cantidad_disponible, estado, descripcion)
                              VALUES (:titulo, :autor, :isbn, :categoria, :editorial, :anio, :ubicacion, 
                              :cantidad, :cantidad, 'disponible', :descripcion)";
                    
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([
                        ':titulo' => $row[0] ?? '',
                        ':autor' => $row[1] ?? '',
                        ':isbn' => $row[2] ?? '',
                        ':categoria' => $row[3] ?? '',
                        ':editorial' => $row[4] ?? '',
                        ':anio' => $row[5] ?? null,
                        ':ubicacion' => $row[6] ?? '',
                        ':cantidad' => $row[7] ?? 1,
                        ':descripcion' => $row[8] ?? ''
                    ]);
                    $importados++;
                } catch (PDOException $e) {
                    $errores++;
                }
            }

            $_SESSION['mensaje'] = "Importación completada: $importados libros importados, $errores errores";
            header('Location: index.php?ruta=importar');
            exit();

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al importar: ' . $e->getMessage();
            header('Location: index.php?ruta=importar');
            exit();
        }
    }
}
?>
