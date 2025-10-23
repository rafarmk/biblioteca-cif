<?php
require_once 'models/Libro.php';
require_once 'models/Usuario.php';
require_once 'core/models/Prestamo.php';

class HomeController {
    public function index() {
        // Obtener estadísticas
        $libro = new Libro();
        $usuario = new Usuario();
        $prestamo = new Prestamo();
        
        // Contar totales
        $libros = $libro->listar();
        $usuarios = $usuario->listar();
        $stats_prestamos = $prestamo->obtenerEstadisticas();
        
        // Obtener préstamos activos recientes
        $prestamos_activos = $prestamo->listarActivos();
        
        // Obtener préstamos atrasados
        $prestamos_atrasados = $prestamo->listarAtrasados();
        
        require_once 'views/home.php';
    }
}