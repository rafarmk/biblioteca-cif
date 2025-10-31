<?php
/**
 * Modelo: Libro
 * 
 * Descripción: Maneja todas las operaciones con la tabla 'libros'
 * Autor: José Raphael Ernesto Pérez Hernández
 * Fecha: 13 de Octubre, 2025 - Actualizado a PDO
 * Versión: 2.0 - Convertido de MySQLi a PDO
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
                    autor, 
                    editorial,
                    año_publicacion,
                    categoria,
                    idioma,
                    num_paginas,
                    descripcion,
                    cantidad_total,
                    cantidad_disponible
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        
        $isbn = $datos['isbn'] ?? null;
        $titulo = $datos['titulo'];
        $autor = $datos['autor'];
        $editorial = $datos['editorial'] ?? null;
        $anio = $datos['año_publicacion'] ?? null;
        $categoria = $datos['categoria'] ?? 'General';
        $idioma = $datos['idioma'] ?? 'Español';
        $paginas = $datos['num_paginas'] ?? null;
        $descripcion = $datos['descripcion'] ?? null;
        $total_copias = $datos['cantidad_total'] ?? 1;
        $copias_disponibles = $total_copias;

        return $stmt->execute([
            $isbn,
            $titulo,
            $autor,
            $editorial,
            $anio,
            $categoria,
            $idioma,
            $paginas,
            $descripcion,
            $total_copias,
            $copias_disponibles
        ]);
    }

    /**
     * ==========================================
     * LEER: Obtener todos los libros activos
     * ==========================================
     */
    public function obtenerTodos() {
        $sql = "SELECT * FROM libros 
                WHERE estado = 'activo' 
                ORDER BY id DESC";
        
        $stmt = $this->conexion->query($sql);
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($libros as &$libro) {
            $libro['disponible'] = $libro['cantidad_disponible'] > 0;
            $libro['categoria_nombre'] = $libro['categoria'];
        }
        
        return $libros;
    }

    /**
     * ==========================================
     * LEER: Obtener un libro por ID
     * ==========================================
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM libros WHERE id = ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            $resultado['disponible'] = $resultado['cantidad_disponible'] > 0;
            $resultado['categoria_nombre'] = $resultado['categoria'];
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
                    autor = ?, 
                    editorial = ?,
                    año_publicacion = ?,
                    categoria = ?,
                    idioma = ?,
                    num_paginas = ?,
                    descripcion = ?,
                    cantidad_total = ?
                WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);
        
        return $stmt->execute([
            $datos['isbn'] ?? null,
            $datos['titulo'],
            $datos['autor'],
            $datos['editorial'] ?? null,
            $datos['año_publicacion'] ?? null,
            $datos['categoria'] ?? 'General',
            $datos['idioma'] ?? 'Español',
            $datos['num_paginas'] ?? null,
            $datos['descripcion'] ?? null,
            $datos['cantidad_total'] ?? 1,
            $id
        ]);
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

        $sql = "UPDATE libros SET estado = 'inactivo' WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * ==========================================
     * UTILIDAD: Verificar préstamos activos
     * ==========================================
     */
    private function tienePrestamosActivos($libro_id) {
        // Como aún no existe la tabla prestamos, retornar false
        return false;
        
        /* Descomentar cuando exista la tabla prestamos:
        $sql = "SELECT COUNT(*) as total 
                FROM prestamos 
                WHERE libro_id = ? AND estado = 'activo'";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$libro_id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $resultado['total'] > 0;
        */
    }

    /**
     * ==========================================
     * LEER: Obtener libros disponibles
     * ==========================================
     */
    public function obtenerDisponibles() {
        $sql = "SELECT * FROM libros 
                WHERE cantidad_disponible > 0 
                AND estado = 'activo' 
                ORDER BY titulo";
        
        $stmt = $this->conexion->query($sql);
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($libros as &$libro) {
            $libro['disponible'] = true;
            $libro['categoria_nombre'] = $libro['categoria'];
        }
        
        return $libros;
    }

    /**
     * ==========================================
     * BUSCAR: Por título, autor o ISBN
     * ==========================================
     */
    public function buscar($termino) {
        $sql = "SELECT * FROM libros 
                WHERE (titulo LIKE ? OR autor LIKE ? OR isbn LIKE ?) 
                AND estado = 'activo' 
                ORDER BY titulo";
        
        $busqueda = "%{$termino}%";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$busqueda, $busqueda, $busqueda]);

        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($libros as &$libro) {
            $libro['disponible'] = $libro['cantidad_disponible'] > 0;
            $libro['categoria_nombre'] = $libro['categoria'];
        }
        
        return $libros;
    }

    /**
     * ==========================================
     * FILTRAR: Por categoría
     * ==========================================
     */
    public function obtenerPorCategoria($categoria) {
        $sql = "SELECT * FROM libros 
                WHERE categoria = ? 
                AND estado = 'activo' 
                ORDER BY titulo";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$categoria]);

        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($libros as &$libro) {
            $libro['disponible'] = $libro['cantidad_disponible'] > 0;
            $libro['categoria_nombre'] = $libro['categoria'];
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
                SET cantidad_disponible = cantidad_disponible - 1
                WHERE id = ? AND cantidad_disponible > 0";
        
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * ==========================================
     * INVENTARIO: Marcar como disponible
     * ==========================================
     */
    public function marcarDisponible($id) {
        $sql = "UPDATE libros 
                SET cantidad_disponible = cantidad_disponible + 1 
                WHERE id = ? AND cantidad_disponible < cantidad_total";
        
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * ==========================================
     * UTILIDAD: Verificar disponibilidad
     * ==========================================
     */
    public function estaDisponible($id) {
        $sql = "SELECT cantidad_disponible FROM libros WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $resultado && $resultado['cantidad_disponible'] > 0;
    }

    /**
     * ==========================================
     * ESTADÍSTICAS: Contar total de libros
     * ==========================================
     */
    public function contarTotal() {
        $sql = "SELECT COUNT(*) as total FROM libros WHERE estado = 'activo'";
        $stmt = $this->conexion->query($sql);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);
        return $datos['total'];
    }

    /**
     * ==========================================
     * ESTADÍSTICAS: Contar copias disponibles
     * ==========================================
     */
    public function contarDisponibles() {
        $sql = "SELECT SUM(cantidad_disponible) as total 
                FROM libros 
                WHERE estado = 'activo'";
        
        $stmt = $this->conexion->query($sql);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);
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

        if ($excluir_id) {
            $sql .= " AND id != ?";
            $params[] = $excluir_id;
        }

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'] > 0;
    }

    /**
     * ==========================================
     * UTILIDAD: Obtener todas las categorías
     * ==========================================
     */
    public function obtenerCategorias() {
        $sql = "SELECT * FROM categorias ORDER BY nombre";
        $stmt = $this->conexion->query($sql);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>