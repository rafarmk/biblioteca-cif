<?php
require_once 'config/Database.php';
class LandingController {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function index() {
        // Verificar si es admin o usuario normal
        $tipoUsuario = $_SESSION['tipo_usuario'] ?? '';
        $esAdmin = in_array($tipoUsuario, ['administrador', 'personal_administrativo', 'personal_operativo']);
        
        // Obtener estadísticas generales
        $query = "SELECT COUNT(*) as total FROM libros";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $totalLibros = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $query = "SELECT COUNT(*) as total FROM libros WHERE estado = 'disponible'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $librosDisponibles = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Cargar navbar y vista de landing (para todos)
        require_once 'views/layouts/navbar.php';
        require_once 'views/landing/index.php';
    }
}
?>