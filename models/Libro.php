<?php
require_once 'config/Database.php';

class Libro {
    private $conn;
    private $table = 'libros';
    
    // Propiedades
    public $id;
    public $titulo;
    public $autor;
    public $isbn;
    public $editorial;
    public $anio_publicacion;
    public $categoria_id;
    public $cantidad_disponible;
    public $cantidad_total;
    public $ubicacion;
    public $descripcion;
    public $num_paginas;
    public $idioma;
    public $estado;
    public $imagen_portada;
    public $activo;  // ← AGREGADO

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listar($soloActivos = true) {
        $whereActivo = $soloActivos ? "WHERE l.activo = 1" : "";
        
        $query = "SELECT l.*, c.nombre as categoria_nombre
                  FROM " . $this->table . " l
                  LEFT JOIN categorias c ON l.categoria_id = c.id
                  $whereActivo
                  ORDER BY l.titulo ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarDisponibles() {
        $query = "SELECT l.*, c.nombre as categoria_nombre
                  FROM " . $this->table . " l
                  LEFT JOIN categorias c ON l.categoria_id = c.id
                  WHERE l.activo = 1 AND l.estado = 'disponible'
                  ORDER BY l.titulo ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT l.*, c.nombre as categoria_nombre
                  FROM " . $this->table . " l
                  LEFT JOIN categorias c ON l.categoria_id = c.id
                  WHERE l.id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table . "
                  (titulo, autor, isbn, editorial, anio_publicacion, categoria_id,
                   cantidad_disponible, cantidad_total, ubicacion, descripcion, activo)
                  VALUES
                  (:titulo, :autor, :isbn, :editorial, :anio, :categoria_id,
                   :cantidad_disponible, :cantidad_total, :ubicacion, :descripcion, 1)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':autor', $this->autor);
        $stmt->bindParam(':isbn', $this->isbn);
        $stmt->bindParam(':editorial', $this->editorial);
        $stmt->bindParam(':anio', $this->anio_publicacion);
        $stmt->bindParam(':categoria_id', $this->categoria_id);
        $stmt->bindParam(':cantidad_disponible', $this->cantidad_disponible);
        $stmt->bindParam(':cantidad_total', $this->cantidad_total);
        $stmt->bindParam(':ubicacion', $this->ubicacion);
        $stmt->bindParam(':descripcion', $this->descripcion);

        return $stmt->execute();
    }

    public function actualizar() {
        $query = "UPDATE " . $this->table . "
                  SET titulo = :titulo,
                      autor = :autor,
                      isbn = :isbn,
                      editorial = :editorial,
                      anio_publicacion = :anio,
                      categoria_id = :categoria_id,
                      cantidad_disponible = :cantidad_disponible,
                      cantidad_total = :cantidad_total,
                      ubicacion = :ubicacion,
                      descripcion = :descripcion,
                      activo = :activo
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':autor', $this->autor);
        $stmt->bindParam(':isbn', $this->isbn);
        $stmt->bindParam(':editorial', $this->editorial);
        $stmt->bindParam(':anio', $this->anio_publicacion);
        $stmt->bindParam(':categoria_id', $this->categoria_id);
        $stmt->bindParam(':cantidad_disponible', $this->cantidad_disponible);
        $stmt->bindParam(':cantidad_total', $this->cantidad_total);
        $stmt->bindParam(':ubicacion', $this->ubicacion);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':activo', $this->activo);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    public function eliminar($id) {
        // Eliminación lógica (soft delete)
        $query = "UPDATE " . $this->table . " SET activo = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function eliminarFisico($id) {
        // Eliminación física (solo si es necesario)
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function buscar($termino, $soloActivos = true) {
        $whereActivo = $soloActivos ? "AND l.activo = 1" : "";
        
        $query = "SELECT l.*, c.nombre as categoria_nombre
                  FROM " . $this->table . " l
                  LEFT JOIN categorias c ON l.categoria_id = c.id
                  WHERE (l.titulo LIKE :termino
                  OR l.autor LIKE :termino
                  OR l.isbn LIKE :termino)
                  $whereActivo
                  ORDER BY l.titulo ASC";

        $stmt = $this->conn->prepare($query);
        $terminoBusqueda = "%{$termino}%";
        $stmt->bindParam(':termino', $terminoBusqueda);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}