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
        // Obtener estadísticas
        $libroModel = new Libro($this->db);
        $usuarioModel = new Usuario($this->db);
        $prestamoModel = new Prestamo($this->db);
        
        // Contar totales
        $totalLibros = $libroModel->contar();
        $totalUsuarios = $usuarioModel->contar();
        
        // Obtener préstamos activos y atrasados
        $prestamosActivos = $prestamoModel->contarActivos();
        $prestamosAtrasados = $prestamoModel->contarAtrasados();
        
        // Obtener actividad reciente (últimos 5 préstamos)
        $actividadReciente = $prestamoModel->obtenerRecientes(5);
        
        require_once 'views/home.php';
    }
}
?>
