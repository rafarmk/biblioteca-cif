<?php
require_once 'config/Database.php';

class Prestamo {
    private $conn;
    private $table = 'prestamos';
    
    public $id;
    public $usuario_id;
    public $libro_id;
    public $fecha_prestamo;
    public $fecha_devolucion_esperada;
    public $fecha_devolucion_real;
    public $estado;
    public $notas;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  (usuario_id, libro_id, fecha_prestamo, fecha_devolucion_esperada, estado, notas) 
                  VALUES (:usuario_id, :libro_id, :fecha_prestamo, :fecha_devolucion_esperada, :estado, :notas)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':usuario_id', $this->usuario_id);
        $stmt->bindParam(':libro_id', $this->libro_id);
        $stmt->bindParam(':fecha_prestamo', $this->fecha_prestamo);
        $stmt->bindParam(':fecha_devolucion_esperada', $this->fecha_devolucion_esperada);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':notas', $this->notas);
        
        if ($stmt->execute()) {
            $this->actualizarCantidadLibro($this->libro_id, -1);
            return true;
        }
        return false;
    }
    
    public function listarTodos() {
        $query = "SELECT p.*, 
                         u.nombre as usuario_nombre, u.email as usuario_email,
                         l.titulo as libro_titulo, l.autor as libro_autor, l.isbn as libro_isbn
                  FROM " . $this->table . " p
                  LEFT JOIN usuarios u ON p.usuario_id = u.id
                  LEFT JOIN libros l ON p.libro_id = l.id
                  ORDER BY p.fecha_prestamo DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function listarActivos() {
        $query = "SELECT p.*, 
                         u.nombre as usuario_nombre, u.email as usuario_email,
                         l.titulo as libro_titulo, l.autor as libro_autor, l.isbn as libro_isbn,
                         DATEDIFF(CURDATE(), p.fecha_devolucion_esperada) as dias_atraso
                  FROM " . $this->table . " p
                  LEFT JOIN usuarios u ON p.usuario_id = u.id
                  LEFT JOIN libros l ON p.libro_id = l.id
                  WHERE p.estado = 'activo'
                  ORDER BY p.fecha_devolucion_esperada ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function listarAtrasados() {
        $query = "SELECT p.*, 
                         u.nombre as usuario_nombre, u.email as usuario_email,
                         l.titulo as libro_titulo, l.autor as libro_autor,
                         DATEDIFF(CURDATE(), p.fecha_devolucion_esperada) as dias_atraso
                  FROM " . $this->table . " p
                  LEFT JOIN usuarios u ON p.usuario_id = u.id
                  LEFT JOIN libros l ON p.libro_id = l.id
                  WHERE p.estado = 'activo' 
                  AND p.fecha_devolucion_esperada < CURDATE()
                  ORDER BY p.fecha_devolucion_esperada ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorId($id) {
        $query = "SELECT p.*, 
                         u.nombre as usuario_nombre, u.email as usuario_email,
                         l.titulo as libro_titulo, l.autor as libro_autor, l.isbn as libro_isbn
                  FROM " . $this->table . " p
                  LEFT JOIN usuarios u ON p.usuario_id = u.id
                  LEFT JOIN libros l ON p.libro_id = l.id
                  WHERE p.id = :id
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function devolver($id, $fecha_devolucion_real, $notas = '') {
        $query = "UPDATE " . $this->table . " 
                  SET fecha_devolucion_real = :fecha_devolucion_real,
                      estado = 'devuelto',
                      notas = CONCAT(COALESCE(notas, ''), ' ', :notas)
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':fecha_devolucion_real', $fecha_devolucion_real);
        $stmt->bindParam(':notas', $notas);
        
        if ($stmt->execute()) {
            $prestamo = $this->obtenerPorId($id);
            $this->actualizarCantidadLibro($prestamo['libro_id'], 1);
            return true;
        }
        return false;
    }
    
    public function actualizarAtrasados() {
        $query = "UPDATE " . $this->table . " 
                  SET estado = 'atrasado'
                  WHERE estado = 'activo' 
                  AND fecha_devolucion_esperada < CURDATE()";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
    
    public function libroDisponible($libro_id) {
        $query = "SELECT cantidad_disponible FROM libros WHERE id = :libro_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':libro_id', $libro_id);
        $stmt->execute();
        $libro = $stmt->fetch(PDO::FETCH_ASSOC);
        return $libro && $libro['cantidad_disponible'] > 0;
    }
    
    private function actualizarCantidadLibro($libro_id, $cantidad) {
        $query = "UPDATE libros 
                  SET cantidad_disponible = cantidad_disponible + :cantidad 
                  WHERE id = :libro_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':libro_id', $libro_id);
        return $stmt->execute();
    }
    
    public function obtenerEstadisticas() {
        $stats = [];
        
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_prestamos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE estado = 'activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['prestamos_activos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                  WHERE estado = 'activo' AND fecha_devolucion_esperada < CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['prestamos_atrasados'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE estado = 'devuelto'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['prestamos_devueltos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return $stats;
    }
}