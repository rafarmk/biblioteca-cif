<?php
require_once 'config/Database.php';

class Libro {
    private $conn;
    private $table = 'libros';
    
    public $id;
    public $titulo;
    public $autor;
    public $isbn;
    public $editorial;
    public $anio_publicacion;
    public $categoria;
    public $cantidad_disponible;
    public $ubicacion;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function listar() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY titulo ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function crear() {
        $query = "INSERT INTO " . $this->table . " (titulo, autor, isbn, editorial, anio_publicacion, categoria, cantidad_disponible, ubicacion) VALUES (:titulo, :autor, :isbn, :editorial, :anio, :categoria, :cantidad, :ubicacion)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':autor', $this->autor);
        $stmt->bindParam(':isbn', $this->isbn);
        $stmt->bindParam(':editorial', $this->editorial);
        $stmt->bindParam(':anio', $this->anio_publicacion);
        $stmt->bindParam(':categoria', $this->categoria);
        $stmt->bindParam(':cantidad', $this->cantidad_disponible);
        $stmt->bindParam(':ubicacion', $this->ubicacion);
        
        return $stmt->execute();
    }
    
    public function actualizar() {
        $query = "UPDATE " . $this->table . " SET titulo = :titulo, autor = :autor, isbn = :isbn, editorial = :editorial, anio_publicacion = :anio, categoria = :categoria, cantidad_disponible = :cantidad, ubicacion = :ubicacion WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':autor', $this->autor);
        $stmt->bindParam(':isbn', $this->isbn);
        $stmt->bindParam(':editorial', $this->editorial);
        $stmt->bindParam(':anio', $this->anio_publicacion);
        $stmt->bindParam(':categoria', $this->categoria);
        $stmt->bindParam(':cantidad', $this->cantidad_disponible);
        $stmt->bindParam(':ubicacion', $this->ubicacion);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }
    
    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    public function buscar($termino) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE titulo LIKE ? 
                  OR autor LIKE ? 
                  OR isbn LIKE ? 
                  ORDER BY titulo ASC";
        
        $stmt = $this->conn->prepare($query);
        $terminoBusqueda = "%{$termino}%";
        $stmt->execute([$terminoBusqueda, $terminoBusqueda, $terminoBusqueda]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}