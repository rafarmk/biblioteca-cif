<?php
/**
 * Modelo: Libro
 * 
 * Descripción: Maneja todas las operaciones con la tabla 'libros'
 * Autor: José Raphael Ernesto Pérez Hernández
 * Fecha: 13 de Octubre, 2025
 * 
 * ESTRUCTURA DE LA TABLA LIBROS:
 * - id, isbn, titulo, subtitulo, autor, editorial
 * - anio_publicacion, categoria_id, idioma, paginas
 * - descripcion, portada, tipo (fisico/digital/ambos)
 * - total_copias, copias_disponibles, veces_prestado
 * - estado (activo/mantenimiento/baja), fecha_registro
 */

class Libro {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    /**
     * ==========================================
     * CREAR: Insertar nuevo libro
     * ==========================================
     */
    public function crear($datos) {
        $sql = "INSERT INTO libros (
                    isbn,
                    titulo, 
                    subtitulo,
                    autor, 
                    editorial,
                    anio_publicacion,
                    categoria_id,
                    idioma,
                    paginas,
                    descripcion,
                    tipo,
                    total_copias,
                    copias_disponibles
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        
        // Preparar valores con valores por defecto
        $isbn = $datos['isbn'] ?? null;
        $titulo = $datos['titulo'];
        $subtitulo = $datos['subtitulo'] ?? null;
        $autor = $datos['autor'];
        $editorial = $datos['editorial'] ?? null;
        $anio = $datos['anio_publicacion'] ?? null;
        $categoria_id = $datos['categoria_id'] ?? null;
        $idioma = $datos['idioma'] ?? 'Español';
        $paginas = $datos['paginas'] ?? null;
        $descripcion = $datos['descripcion'] ?? null;
        
        // ⭐ VALIDAR TIPO - Solo acepta: fisico, digital, ambos
        $tipos_validos = ['fisico', 'digital', 'ambos'];
        $tipo = isset($datos['tipo']) && in_array($datos['tipo'], $tipos_validos) ? $datos['tipo'] : 'fisico';
        
        $total_copias = $datos['total_copias'] ?? 1;
        $copias_disponibles = $total_copias; // Inicialmente todas están disponibles

        // ✅ DEFINITIVO: 13 caracteres para 13 variables
        // s s s s s i i s i s s i i
        // 1 2 3 4 5 6 7 8 9 0 1 2 3
        $stmt->bind_param("sssssiisissii",
            $isbn,              // 1 - string
            $titulo,            // 2 - string
            $subtitulo,         // 3 - string
            $autor,             // 4 - string
            $editorial,         // 5 - string
            $anio,              // 6 - int
            $categoria_id,      // 7 - int
            $idioma,            // 8 - string
            $paginas,           // 9 - int
            $descripcion,       // 10 - string
            $tipo,              // 11 - string
            $total_copias,      // 12 - int
            $copias_disponibles // 13 - int
        );

        return $stmt->execute();
    }

    /**
     * ==========================================
     * LEER: Obtener todos los libros activos
     * ==========================================
     */
    public function obtenerTodos() {
        $sql = "SELECT l.*, c.nombre as categoria_nombre 
                FROM libros l 
                LEFT JOIN categorias c ON l.categoria_id = c.id 
                WHERE l.estado = 'activo' 
                ORDER BY l.id DESC";
        
        $resultado = $this->conexion->query($sql);

        $libros = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($libro = $resultado->fetch_assoc()) {
                // Agregar campo calculado 'disponible'
                $libro['disponible'] = $libro['copias_disponibles'] > 0;
                $libros[] = $libro;
            }
        }
        return $libros;
    }

    /**
     * ==========================================
     * LEER: Obtener un libro por ID
     * ==========================================
     */
    public function obtenerPorId($id) {
        $sql = "SELECT l.*, c.nombre as categoria_nombre 
                FROM libros l 
                LEFT JOIN categorias c ON l.categoria_id = c.id 
                WHERE l.id = ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $resultado = $stmt->get_result()->fetch_assoc();
        
        if ($resultado) {
            $resultado['disponible'] = $resultado['copias_disponibles'] > 0;
        }
        
        return $resultado;
    }

    /**
     * ==========================================
     * ACTUALIZAR: Modificar libro existente
     * ==========================================
     */
    public function actualizar($id, $datos) {
        $sql = "UPDATE libros SET 
                    isbn = ?,
                    titulo = ?, 
                    subtitulo = ?,
                    autor = ?, 
                    editorial = ?,
                    anio_publicacion = ?,
                    categoria_id = ?,
                    idioma = ?,
                    paginas = ?,
                    descripcion = ?,
                    tipo = ?,
                    total_copias = ?
                WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);
        
        $isbn = $datos['isbn'] ?? null;
        $titulo = $datos['titulo'];
        $subtitulo = $datos['subtitulo'] ?? null;
        $autor = $datos['autor'];
        $editorial = $datos['editorial'] ?? null;
        $anio = $datos['anio_publicacion'] ?? null;
        $categoria_id = $datos['categoria_id'] ?? null;
        $idioma = $datos['idioma'] ?? 'Español';
        $paginas = $datos['paginas'] ?? null;
        $descripcion = $datos['descripcion'] ?? null;
        
        // ⭐ VALIDAR TIPO - Solo acepta: fisico, digital, ambos
        $tipos_validos = ['fisico', 'digital', 'ambos'];
        $tipo = isset($datos['tipo']) && in_array($datos['tipo'], $tipos_validos) ? $datos['tipo'] : 'fisico';
        
        $total_copias = $datos['total_copias'] ?? 1;

        // ✅ DEFINITIVO: 13 caracteres para 13 variables (12 campos + id)
        $stmt->bind_param("sssssiisissii",
            $isbn,
            $titulo,
            $subtitulo,
            $autor,
            $editorial,
            $anio,
            $categoria_id,
            $idioma,
            $paginas,
            $descripcion,
            $tipo,
            $total_copias,
            $id
        );

        return $stmt->execute();
    }

    /**
     * ==========================================
     * ELIMINAR: Borrar libro (solo si no tiene préstamos)
     * ==========================================
     */
    public function eliminar($id) {
        // Verificar si tiene préstamos activos
        if ($this->tienePrestamosActivos($id)) {
            return false;
        }

        // Cambiar estado a 'baja' en lugar de eliminar
        $sql = "UPDATE libros SET estado = 'baja' WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * ==========================================
     * UTILIDAD: Verificar préstamos activos
     * ==========================================
     */
    private function tienePrestamosActivos($libro_id) {
        $sql = "SELECT COUNT(*) as total 
                FROM prestamos 
                WHERE libro_id = ? AND estado = 'activo'";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $libro_id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        
        return $resultado['total'] > 0;
    }

    /**
     * ==========================================
     * LEER: Obtener libros disponibles
     * ==========================================
     */
    public function obtenerDisponibles() {
        $sql = "SELECT l.*, c.nombre as categoria_nombre 
                FROM libros l 
                LEFT JOIN categorias c ON l.categoria_id = c.id 
                WHERE l.copias_disponibles > 0 
                AND l.estado = 'activo' 
                ORDER BY l.titulo";
        
        $resultado = $this->conexion->query($sql);

        $libros = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($libro = $resultado->fetch_assoc()) {
                $libro['disponible'] = true;
                $libros[] = $libro;
            }
        }
        return $libros;
    }

    /**
     * ==========================================
     * BUSCAR: Por título, autor o ISBN
     * ==========================================
     */
    public function buscar($termino) {
        $sql = "SELECT l.*, c.nombre as categoria_nombre 
                FROM libros l 
                LEFT JOIN categorias c ON l.categoria_id = c.id 
                WHERE (l.titulo LIKE ? OR l.autor LIKE ? OR l.isbn LIKE ?) 
                AND l.estado = 'activo' 
                ORDER BY l.titulo";
        
        $busqueda = "%{$termino}%";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sss", $busqueda, $busqueda, $busqueda);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $libros = [];
        
        while ($libro = $resultado->fetch_assoc()) {
            $libro['disponible'] = $libro['copias_disponibles'] > 0;
            $libros[] = $libro;
        }
        
        return $libros;
    }

    /**
     * ==========================================
     * FILTRAR: Por categoría
     * ==========================================
     */
    public function obtenerPorCategoria($categoria_id) {
        $sql = "SELECT l.*, c.nombre as categoria_nombre 
                FROM libros l 
                LEFT JOIN categorias c ON l.categoria_id = c.id 
                WHERE l.categoria_id = ? 
                AND l.estado = 'activo' 
                ORDER BY l.titulo";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $categoria_id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $libros = [];
        
        while ($libro = $resultado->fetch_assoc()) {
            $libro['disponible'] = $libro['copias_disponibles'] > 0;
            $libros[] = $libro;
        }
        
        return $libros;
    }

    /**
     * ==========================================
     * INVENTARIO: Marcar como no disponible
     * ==========================================
     */
    public function marcarNoDisponible($id) {
        $sql = "UPDATE libros 
                SET copias_disponibles = copias_disponibles - 1,
                    veces_prestado = veces_prestado + 1
                WHERE id = ? AND copias_disponibles > 0";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * ==========================================
     * INVENTARIO: Marcar como disponible
     * ==========================================
     */
    public function marcarDisponible($id) {
        $sql = "UPDATE libros 
                SET copias_disponibles = copias_disponibles + 1 
                WHERE id = ? AND copias_disponibles < total_copias";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * ==========================================
     * UTILIDAD: Verificar disponibilidad
     * ==========================================
     */
    public function estaDisponible($id) {
        $sql = "SELECT copias_disponibles FROM libros WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        
        return $resultado && $resultado['copias_disponibles'] > 0;
    }

    /**
     * ==========================================
     * ESTADÍSTICAS: Contar total de libros
     * ==========================================
     */
    public function contarTotal() {
        $sql = "SELECT COUNT(*) as total FROM libros WHERE estado = 'activo'";
        $resultado = $this->conexion->query($sql);
        $datos = $resultado->fetch_assoc();
        return $datos['total'];
    }

    /**
     * ==========================================
     * ESTADÍSTICAS: Contar copias disponibles
     * ==========================================
     */
    public function contarDisponibles() {
        $sql = "SELECT SUM(copias_disponibles) as total 
                FROM libros 
                WHERE estado = 'activo'";
        
        $resultado = $this->conexion->query($sql);
        $datos = $resultado->fetch_assoc();
        return $datos['total'] ?? 0;
    }

    /**
     * ==========================================
     * UTILIDAD: Verificar si ISBN existe
     * ==========================================
     */
    public function existeISBN($isbn, $excluir_id = null) {
        if (empty($isbn)) {
            return false;
        }

        $sql = "SELECT COUNT(*) as total FROM libros WHERE isbn = ?";
        $params = [$isbn];
        $types = "s";

        if ($excluir_id) {
            $sql .= " AND id != ?";
            $params[] = $excluir_id;
            $types .= "i";
        }

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        return $resultado['total'] > 0;
    }

    /**
     * ==========================================
     * UTILIDAD: Obtener todas las categorías
     * ==========================================
     */
    public function obtenerCategorias() {
        $sql = "SELECT * FROM categorias ORDER BY nombre";
        $resultado = $this->conexion->query($sql);
        
        $categorias = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($cat = $resultado->fetch_assoc()) {
                $categorias[] = $cat;
            }
        }
        return $categorias;
    }
}