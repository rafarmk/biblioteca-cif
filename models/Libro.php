<?php
class Libro {
    private $conn;
    private $table = "libros";
    
    // Propiedades definidas explícitamente
    public $id;
    public $titulo;
    public $autor;
    public $isbn;
    public $editorial;
    public $anio_publicacion;
    public $categoria;
    public $ubicacion;
    public $cantidad_total;
    public $cantidad_disponible;
    public $descripcion;
    public $estado;
    public $fecha_registro;
    
    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Crear libro
    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  SET titulo = :titulo,
                      autor = :autor,
                      isbn = :isbn,
                      editorial = :editorial,
                      anio_publicacion = :anio_publicacion,
                      categoria = :categoria,
                      ubicacion = :ubicacion,
                      cantidad_total = :cantidad_total,
                      cantidad_disponible = :cantidad_disponible,
                      descripcion = :descripcion,
                      estado = :estado";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->autor = htmlspecialchars(strip_tags($this->autor));
        $this->isbn = htmlspecialchars(strip_tags($this->isbn ?? ''));
        $this->editorial = htmlspecialchars(strip_tags($this->editorial ?? ''));
        $this->anio_publicacion = htmlspecialchars(strip_tags($this->anio_publicacion ?? null));
        $this->categoria = htmlspecialchars(strip_tags($this->categoria ?? ''));
        $this->ubicacion = htmlspecialchars(strip_tags($this->ubicacion ?? ''));
        $this->cantidad_total = htmlspecialchars(strip_tags($this->cantidad_total ?? 1));
        $this->cantidad_disponible = htmlspecialchars(strip_tags($this->cantidad_disponible ?? 1));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion ?? ''));
        $this->estado = htmlspecialchars(strip_tags($this->estado ?? 'disponible'));
        
        // Bind
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":autor", $this->autor);
        $stmt->bindParam(":isbn", $this->isbn);
        $stmt->bindParam(":editorial", $this->editorial);
        $stmt->bindParam(":anio_publicacion", $this->anio_publicacion);
        $stmt->bindParam(":categoria", $this->categoria);
        $stmt->bindParam(":ubicacion", $this->ubicacion);
        $stmt->bindParam(":cantidad_total", $this->cantidad_total);
        $stmt->bindParam(":cantidad_disponible", $this->cantidad_disponible);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":estado", $this->estado);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Leer todos los libros
    public function leer() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY fecha_registro DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Leer un libro
    public function leerUno() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->titulo = $row['titulo'];
            $this->autor = $row['autor'];
            $this->isbn = $row['isbn'];
            $this->editorial = $row['editorial'];
            $this->anio_publicacion = $row['anio_publicacion'];
            $this->categoria = $row['categoria'];
            $this->ubicacion = $row['ubicacion'];
            $this->cantidad_total = $row['cantidad_total'];
            $this->cantidad_disponible = $row['cantidad_disponible'];
            $this->descripcion = $row['descripcion'];
            $this->estado = $row['estado'];
            $this->fecha_registro = $row['fecha_registro'];
            return true;
        }
        return false;
    }
    
    // Actualizar libro
    public function actualizar() {
        $query = "UPDATE " . $this->table . "
                  SET titulo = :titulo,
                      autor = :autor,
                      isbn = :isbn,
                      editorial = :editorial,
                      anio_publicacion = :anio_publicacion,
                      categoria = :categoria,
                      ubicacion = :ubicacion,
                      cantidad_total = :cantidad_total,
                      descripcion = :descripcion,
                      estado = :estado
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->autor = htmlspecialchars(strip_tags($this->autor));
        $this->isbn = htmlspecialchars(strip_tags($this->isbn ?? ''));
        $this->editorial = htmlspecialchars(strip_tags($this->editorial ?? ''));
        $this->anio_publicacion = htmlspecialchars(strip_tags($this->anio_publicacion ?? null));
        $this->categoria = htmlspecialchars(strip_tags($this->categoria ?? ''));
        $this->ubicacion = htmlspecialchars(strip_tags($this->ubicacion ?? ''));
        $this->cantidad_total = htmlspecialchars(strip_tags($this->cantidad_total ?? 1));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion ?? ''));
        $this->estado = htmlspecialchars(strip_tags($this->estado ?? 'disponible'));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":autor", $this->autor);
        $stmt->bindParam(":isbn", $this->isbn);
        $stmt->bindParam(":editorial", $this->editorial);
        $stmt->bindParam(":anio_publicacion", $this->anio_publicacion);
        $stmt->bindParam(":categoria", $this->categoria);
        $stmt->bindParam(":ubicacion", $this->ubicacion);
        $stmt->bindParam(":cantidad_total", $this->cantidad_total);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Eliminar libro
    public function eliminar() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Contar libros
    public function contar() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    
    // Contar libros disponibles
    public function contarDisponibles() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE cantidad_disponible > 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    
    // Actualizar disponibilidad
    public function actualizarDisponibilidad($libro_id, $cantidad) {
        $query = "UPDATE " . $this->table . " 
                  SET cantidad_disponible = cantidad_disponible + :cantidad 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":id", $libro_id);
        return $stmt->execute();
    }
}
?>
