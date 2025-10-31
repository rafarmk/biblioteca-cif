<?php
require_once 'models/Libro.php';
require_once 'models/Usuario.php';
require_once 'models/Prestamo.php';

class HomeController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function index() {
        // Obtener estadísticas de libros
        $libroModel = new Libro($this->db);
        $libros = $libroModel->leer()->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener estadísticas de usuarios
        $usuarioModel = new Usuario($this->db);
        $usuarios = $usuarioModel->leer()->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener estadísticas de préstamos
        $prestamoModel = new Prestamo($this->db);
        
        // Contar préstamos activos
        $query = "SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $prestamosActivos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Contar préstamos atrasados
        $query = "SELECT COUNT(*) as total FROM prestamos 
                  WHERE estado = 'activo' 
                  AND fecha_devolucion_esperada < CURDATE()";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $prestamosAtrasados = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Contar total de préstamos
        $query = "SELECT COUNT(*) as total FROM prestamos";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $totalPrestamos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Crear array de estadísticas de préstamos para la vista
        $stats_prestamos = [
            'activos' => $prestamosActivos,
            'atrasados' => $prestamosAtrasados,
            'total' => $totalPrestamos
        ];
        
        // Obtener préstamos activos recientes
        $query = "SELECT p.*, l.titulo as libro_titulo, u.nombre as usuario_nombre
                  FROM prestamos p
                  LEFT JOIN libros l ON p.libro_id = l.id
                  LEFT JOIN usuarios u ON p.usuario_id = u.id
                  WHERE p.estado = 'activo'
                  ORDER BY p.fecha_prestamo DESC
                  LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $prestamos_activos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener préstamos atrasados
        $query = "SELECT p.*, l.titulo as libro_titulo, u.nombre as usuario_nombre
                  FROM prestamos p
                  LEFT JOIN libros l ON p.libro_id = l.id
                  LEFT JOIN usuarios u ON p.usuario_id = u.id
                  WHERE p.estado = 'activo' 
                  AND p.fecha_devolucion_esperada < CURDATE()
                  ORDER BY p.fecha_devolucion_esperada ASC
                  LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $prestamos_atrasados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/home.php';
    }
}
?>