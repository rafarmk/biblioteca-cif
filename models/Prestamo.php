<?php
class Prestamo {
    private $conn;
    private $table = "prestamos";
    
    // Propiedades
    public $id;
    public $libro_id;
    public $usuario_id;
    public $fecha_prestamo;
    public $fecha_devolucion_esperada;
    public $fecha_devolucion_real;
    public $estado;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Crear préstamo
    public function crear() {
        // Verificar disponibilidad del libro
        $query = "SELECT cantidad_disponible FROM libros WHERE id = :libro_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":libro_id", $this->libro_id);
        $stmt->execute();
        $libro = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($libro && $libro['cantidad_disponible'] > 0) {
            // Crear el préstamo
            $query = "INSERT INTO " . $this->table . " 
                      SET libro_id = :libro_id,
                          usuario_id = :usuario_id,
                          fecha_prestamo = :fecha_prestamo,
                          fecha_devolucion_esperada = :fecha_devolucion_esperada,
                          estado = :estado";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":libro_id", $this->libro_id);
            $stmt->bindParam(":usuario_id", $this->usuario_id);
            $stmt->bindParam(":fecha_prestamo", $this->fecha_prestamo);
            $stmt->bindParam(":fecha_devolucion_esperada", $this->fecha_devolucion_esperada);
            $stmt->bindParam(":estado", $this->estado);
            
            if ($stmt->execute()) {
                // Actualizar disponibilidad del libro
                $updateQuery = "UPDATE libros SET cantidad_disponible = cantidad_disponible - 1 WHERE id = :libro_id";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(":libro_id", $this->libro_id);
                $updateStmt->execute();
                return true;
            }
        }
        return false;
    }
    
    // Leer todos los préstamos
    public function leer() {
        $query = "SELECT p.*, l.titulo as libro_titulo, u.nombre as usuario_nombre 
                  FROM " . $this->table . " p
                  LEFT JOIN libros l ON p.libro_id = l.id
                  LEFT JOIN usuarios u ON p.usuario_id = u.id
                  ORDER BY p.fecha_prestamo DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Devolver libro
    public function devolver() {
        $query = "UPDATE " . $this->table . " 
                  SET estado = 'devuelto', 
                      fecha_devolucion_real = NOW() 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        if ($stmt->execute()) {
            // Obtener libro_id
            $query2 = "SELECT libro_id FROM " . $this->table . " WHERE id = :id";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(":id", $this->id);
            $stmt2->execute();
            $row = $stmt2->fetch(PDO::FETCH_ASSOC);
            
            // Aumentar disponibilidad
            $updateQuery = "UPDATE libros SET cantidad_disponible = cantidad_disponible + 1 WHERE id = :libro_id";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(":libro_id", $row['libro_id']);
            $updateStmt->execute();
            
            return true;
        }
        return false;
    }
    
    // Eliminar préstamo
    public function eliminar() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
    
    // Contar préstamos activos
    public function contarActivos() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE estado = 'activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    
    // Contar préstamos atrasados
    public function contarAtrasados() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                  WHERE estado = 'activo' AND fecha_devolucion_esperada < CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    
    // Obtener préstamos recientes
    public function obtenerRecientes($limite = 5) {
        $query = "SELECT p.*, l.titulo as libro_titulo, u.nombre as usuario_nombre 
                  FROM " . $this->table . " p
                  LEFT JOIN libros l ON p.libro_id = l.id
                  LEFT JOIN usuarios u ON p.usuario_id = u.id
                  ORDER BY p.fecha_prestamo DESC
                  LIMIT :limite";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
