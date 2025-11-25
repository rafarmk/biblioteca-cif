<?php
require_once __DIR__ . '/../config/Database.php';

class LibroController {
    private $db;
    private $resultados = ['importados' => 0, 'errores' => []];

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        try {
            $stmt = $this->db->query("
                SELECT l.*, c.nombre as categoria_nombre
                FROM libros l
                LEFT JOIN categorias c ON l.categoria_id = c.id
                ORDER BY l.titulo
            ");
            $libros = $stmt->fetchAll();

            require_once __DIR__ . '/../views/libros/index.php';
        } catch (PDOException $e) {
            die("Error al cargar libros: " . $e->getMessage());
        }
    }

    public function importar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarImportacion();
        } else {
            require_once __DIR__ . '/../views/libros/importar.php';
        }
    }

    private function procesarImportacion() {
        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            $this->resultados['errores'][] = "Error al subir el archivo";
            require_once __DIR__ . '/../views/libros/importar.php';
            return;
        }

        $archivo = $_FILES['archivo']['tmp_name'];
        $nombreArchivo = $_FILES['archivo']['name'];
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

        if ($_FILES['archivo']['size'] > 10 * 1024 * 1024) {
            $this->resultados['errores'][] = "Archivo demasiado grande (maximo 10MB)";
            require_once __DIR__ . '/../views/libros/importar.php';
            return;
        }

        if ($extension === 'csv') {
            $this->procesarCSV($archivo);
        } elseif ($extension === 'xlsx' || $extension === 'xls') {
            $this->procesarExcel($archivo);
        } else {
            $this->resultados['errores'][] = "Solo archivos CSV o XLSX permitidos";
            require_once __DIR__ . '/../views/libros/importar.php';
            return;
        }

        require_once __DIR__ . '/../views/libros/importar.php';
    }

    private function procesarExcel($archivo) {
        if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
            $this->resultados['errores'][] = "PhpSpreadsheet no esta instalado. Ejecuta: composer require phpoffice/phpspreadsheet";
            return;
        }

        require_once __DIR__ . '/../vendor/autoload.php';

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivo);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            $headers = [];
            $filaEncabezado = 1;

            for ($row = 1; $row <= 10; $row++) {
                $tempHeaders = [];
                $columnIndex = 'A';
                while ($columnIndex <= $highestColumn) {
                    $value = $sheet->getCell($columnIndex . $row)->getValue();
                    $tempHeaders[] = $value;
                    $columnIndex++;
                }

                $contieneTexto = false;
                foreach ($tempHeaders as $h) {
                    if ($h !== null && trim((string)$h) !== '') {
                        $contieneTexto = true;
                        break;
                    }
                }

                if ($contieneTexto) {
                    $textoCompleto = strtoupper(implode(' ', $tempHeaders));
                    if (strpos($textoCompleto, 'TITULO') !== false ||
                        strpos($textoCompleto, 'ID') !== false ||
                        strpos($textoCompleto, 'ISBN') !== false ||
                        strpos($textoCompleto, 'LIBRO') !== false) {
                        $headers = $tempHeaders;
                        $filaEncabezado = $row;
                        break;
                    }
                }
            }

            $columnas = $this->encontrarColumnasExcel($headers);

            if (!isset($columnas['isbn']) || !isset($columnas['titulo'])) {
                $this->resultados['errores'][] = "El Excel debe tener columnas: ISBN (o ID DE LIBRO) y TITULO. Fila de encabezados: $filaEncabezado";
                return;
            }

            for ($row = $filaEncabezado + 1; $row <= $highestRow; $row++) {
                $data = [];
                $columnIndex = 'A';
                while ($columnIndex <= $highestColumn) {
                    $value = $sheet->getCell($columnIndex . $row)->getValue();
                    $data[] = $value;
                    $columnIndex++;
                }

                $this->insertarLibroExcel($data, $columnas);
            }

        } catch (Exception $e) {
            $this->resultados['errores'][] = "Error al leer Excel: " . $e->getMessage();
        }
    }

    private function encontrarColumnasExcel($headers) {
        $columnas = [];

        foreach ($headers as $index => $header) {
            if ($header === null || trim((string)$header) === '') {
                continue;
            }

            $h = strtoupper(trim((string)$header));
            $h = str_replace(['°', 'Ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'á', 'é', 'í', 'ó', 'ú'],
                            ['', 'N', 'A', 'E', 'I', 'O', 'U', 'N', 'A', 'E', 'I', 'O', 'U'], $h);

            if (strpos($h, 'ISBN') !== false ||
                strpos($h, 'ID') !== false ||
                strpos($h, 'CODIGO') !== false ||
                strpos($h, 'LIBRO') !== false ||
                $h == 'N' ||
                $h == 'NO') {
                if (!isset($columnas['isbn'])) {
                    $columnas['isbn'] = $index;
                }
            }

            if (strpos($h, 'TITULO') !== false ||
                strpos($h, 'TITLE') !== false ||
                strpos($h, 'NOMBRE') !== false) {
                if (!isset($columnas['titulo'])) {
                    $columnas['titulo'] = $index;
                }
            }

            if (strpos($h, 'AUTOR') !== false ||
                strpos($h, 'AUTHOR') !== false) {
                if (!isset($columnas['autor'])) {
                    $columnas['autor'] = $index;
                }
            }

            if (strpos($h, 'UBICACION') !== false ||
                strpos($h, 'LOCATION') !== false ||
                strpos($h, 'ESTANTE') !== false) {
                if (!isset($columnas['ubicacion'])) {
                    $columnas['ubicacion'] = $index;
                }
            }

            if (strpos($h, 'CANTIDAD') !== false ||
                strpos($h, 'EJEMPLAR') !== false ||
                strpos($h, 'STOCK') !== false ||
                strpos($h, 'N DE') !== false) {
                if (!isset($columnas['cantidad'])) {
                    $columnas['cantidad'] = $index;
                }
            }

            if (strpos($h, 'GENERO') !== false ||
                strpos($h, 'CATEGORIA') !== false) {
                if (!isset($columnas['genero'])) {
                    $columnas['genero'] = $index;
                }
            }

            if (strpos($h, 'ANO') !== false ||
                strpos($h, 'ANIO') !== false ||
                strpos($h, 'PUBLICACION') !== false) {
                if (!isset($columnas['anio'])) {
                    $columnas['anio'] = $index;
                }
            }
        }

        return $columnas;
    }

    private function insertarLibroExcel($data, $columnas) {
        try {
            $isbn = isset($columnas['isbn']) && isset($data[$columnas['isbn']]) ? trim((string)$data[$columnas['isbn']]) : null;
            $titulo = isset($columnas['titulo']) && isset($data[$columnas['titulo']]) ? trim((string)$data[$columnas['titulo']]) : null;

            if (empty($isbn) || empty($titulo)) {
                return;
            }

            $autor = isset($columnas['autor']) && isset($data[$columnas['autor']]) ? trim((string)$data[$columnas['autor']]) : 'Desconocido';
            $ubicacion = isset($columnas['ubicacion']) && isset($data[$columnas['ubicacion']]) ? trim((string)$data[$columnas['ubicacion']]) : null;
            $cantidad = isset($columnas['cantidad']) && isset($data[$columnas['cantidad']]) ? intval($data[$columnas['cantidad']]) : 1;
            $anio = isset($columnas['anio']) && isset($data[$columnas['anio']]) ? intval($data[$columnas['anio']]) : null;

            $stmt = $this->db->prepare("SELECT id FROM libros WHERE isbn = ?");
            $stmt->execute([$isbn]);
            if ($stmt->fetch()) {
                $this->resultados['errores'][] = "Duplicado: $isbn";
                return;
            }

            $stmt = $this->db->prepare("
                INSERT INTO libros
                (isbn, titulo, autor, editorial, categoria_id, anio_publicacion, num_paginas,
                 idioma, descripcion, ubicacion, cantidad_total, cantidad_disponible, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'disponible')
            ");

            $stmt->execute([
                $isbn,
                $titulo,
                $autor,
                null,
                1,
                $anio,
                null,
                'Espanol',
                null,
                $ubicacion,
                $cantidad > 0 ? $cantidad : 1,
                $cantidad > 0 ? $cantidad : 1
            ]);

            $this->resultados['importados']++;

        } catch (PDOException $e) {
            $this->resultados['errores'][] = "Error al insertar: " . ($isbn ?? 'sin ISBN') . " - " . $e->getMessage();
        }
    }

    private function procesarCSV($archivo) {
        if (($handle = fopen($archivo, "r")) !== FALSE) {
            $primeraLinea = fgets($handle);
            rewind($handle);

            $delimitador = ',';
            if (substr_count($primeraLinea, ';') > substr_count($primeraLinea, ',')) {
                $delimitador = ';';
            }

            $headers = fgetcsv($handle, 1000, $delimitador);

            if (!empty($headers[0])) {
                $headers[0] = str_replace("\xEF\xBB\xBF", '', $headers[0]);
            }

            $mapa = $this->mapearColumnas($headers);

            if (!isset($mapa['isbn']) || !isset($mapa['titulo'])) {
                $this->resultados['errores'][] = "El CSV debe tener columnas: ISBN y TITULO";
                fclose($handle);
                return;
            }

            while (($data = fgetcsv($handle, 1000, $delimitador)) !== FALSE) {
                $this->insertarLibro($data, $mapa);
            }

            fclose($handle);
        } else {
            $this->resultados['errores'][] = "No se pudo abrir el archivo CSV";
        }
    }

    private function mapearColumnas($headers) {
        $map = [];

        foreach ($headers as $index => $header) {
            $h = strtoupper(trim($header));

            if (strpos($h, 'ISBN') !== false || strpos($h, 'CODIGO') !== false ||
                $h == 'N' || $h == 'NO' || strpos($h, 'ID') !== false) {
                $map['isbn'] = $index;
            }

            if (strpos($h, 'TITULO') !== false || strpos($h, 'TITLE') !== false) {
                $map['titulo'] = $index;
            }

            if (strpos($h, 'AUTOR') !== false || strpos($h, 'AUTHOR') !== false) {
                $map['autor'] = $index;
            }

            if (strpos($h, 'UBICACION') !== false || strpos($h, 'LOCATION') !== false) {
                $map['ubicacion'] = $index;
            }

            if (strpos($h, 'CANTIDAD') !== false || strpos($h, 'STOCK') !== false || strpos($h, 'EJEMPLAR') !== false) {
                $map['cantidad_total'] = $index;
            }
        }

        return $map;
    }

    private function insertarLibro($data, $mapa) {
        try {
            $isbn = isset($mapa['isbn']) && isset($data[$mapa['isbn']]) ? trim($data[$mapa['isbn']]) : null;
            $titulo = isset($mapa['titulo']) && isset($data[$mapa['titulo']]) ? trim($data[$mapa['titulo']]) : null;
            $autor = isset($mapa['autor']) && isset($data[$mapa['autor']]) ? trim($data[$mapa['autor']]) : 'Desconocido';

            if (empty($isbn) || empty($titulo)) {
                return;
            }

            $stmt = $this->db->prepare("SELECT id FROM libros WHERE isbn = ?");
            $stmt->execute([$isbn]);
            if ($stmt->fetch()) {
                $this->resultados['errores'][] = "Duplicado: $titulo";
                return;
            }

            $ubicacion = isset($mapa['ubicacion']) && isset($data[$mapa['ubicacion']]) ? trim($data[$mapa['ubicacion']]) : null;
            $cantidad = isset($mapa['cantidad_total']) && isset($data[$mapa['cantidad_total']]) ? intval($data[$mapa['cantidad_total']]) : 1;

            $stmt = $this->db->prepare("
                INSERT INTO libros
                (isbn, titulo, autor, editorial, categoria_id, anio_publicacion, num_paginas,
                 idioma, descripcion, ubicacion, cantidad_total, cantidad_disponible, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'disponible')
            ");

            $stmt->execute([
                $isbn,
                $titulo,
                $autor,
                null,
                1,
                null,
                null,
                'Espanol',
                null,
                $ubicacion,
                $cantidad,
                $cantidad
            ]);

            $this->resultados['importados']++;

        } catch (PDOException $e) {
            $this->resultados['errores'][] = "Error: " . $e->getMessage();
        }
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $this->db->prepare("
                    INSERT INTO libros
                    (isbn, titulo, autor, editorial, categoria_id, anio_publicacion,
                     num_paginas, idioma, descripcion, ubicacion, cantidad_total,
                     cantidad_disponible, estado)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'disponible')
                ");

                $stmt->execute([
                    $_POST['isbn'],
                    $_POST['titulo'],
                    $_POST['autor'],
                    $_POST['editorial'] ?? null,
                    $_POST['categoria_id'] ?? 1,
                    $_POST['anio_publicacion'] ?? null,
                    $_POST['num_paginas'] ?? null,
                    $_POST['idioma'] ?? 'Espanol',
                    $_POST['descripcion'] ?? null,
                    $_POST['ubicacion'] ?? null,
                    $_POST['cantidad_total'] ?? 1,
                    $_POST['cantidad_disponible'] ?? 1
                ]);

                header('Location: index.php?ruta=libros&mensaje=creado');
                exit;
            } catch (PDOException $e) {
                $error = "Error: " . $e->getMessage();
            }
        }

        require_once __DIR__ . '/../views/libros/crear.php';
    }

    // ✅ MÉTODO EDITAR CORREGIDO
    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $this->db->prepare("
                    UPDATE libros SET
                        titulo = ?, autor = ?, editorial = ?, categoria_id = ?,
                        anio_publicacion = ?, num_paginas = ?, idioma = ?,
                        descripcion = ?, ubicacion = ?, cantidad_total = ?,
                        cantidad_disponible = ?, estado = ?
                    WHERE id = ?
                ");

                $stmt->execute([
                    $_POST['titulo'],
                    $_POST['autor'],
                    $_POST['editorial'] ?? null,
                    $_POST['categoria_id'] ?? 1,
                    $_POST['anio_publicacion'] ?? null,
                    $_POST['num_paginas'] ?? null,
                    $_POST['idioma'] ?? 'Espanol',
                    $_POST['descripcion'] ?? null,
                    $_POST['ubicacion'] ?? null,
                    $_POST['cantidad_total'],
                    $_POST['cantidad_disponible'],
                    $_POST['estado'],
                    $_POST['id']
                ]);

                header('Location: index.php?ruta=libros&mensaje=editado');
                exit;
            } catch (PDOException $e) {
                $error = "Error: " . $e->getMessage();
            }
        }

        // Obtener el libro a editar
        $id = $_GET['id'] ?? 0;
        $stmt = $this->db->prepare("SELECT * FROM libros WHERE id = ?");
        $stmt->execute([$id]);
        $libro = $stmt->fetch();

        // ✅ AGREGAR: Obtener todos los libros para el catálogo
        $stmt = $this->db->query("
            SELECT l.*, c.nombre as categoria_nombre
            FROM libros l
            LEFT JOIN categorias c ON l.categoria_id = c.id
            ORDER BY l.titulo
        ");
        $libros = $stmt->fetchAll();

        // Obtener categorías para el formulario
        $stmt = $this->db->query("SELECT * FROM categorias ORDER BY nombre");
        $categorias = $stmt->fetchAll();

        // Si no hay datos, crear arrays vacíos
        if (!$libros) $libros = [];
        if (!$categorias) $categorias = [];

        require_once __DIR__ . '/../views/libros/editar.php';
    }

    public function eliminar() {
        $id = $_GET['id'] ?? 0;

        try {
            $stmt = $this->db->prepare("DELETE FROM libros WHERE id = ?");
            $stmt->execute([$id]);

            header('Location: index.php?ruta=libros&mensaje=eliminado');
            exit;
        } catch (PDOException $e) {
            header('Location: index.php?ruta=libros&error=' . urlencode($e->getMessage()));
            exit;
        }
    }
}