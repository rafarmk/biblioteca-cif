<?php
require_once 'models/Libro.php';
require_once 'models/Usuario.php';

class HomeController {
    
    public function index() {
        $libroModel = new Libro();
        $usuarioModel = new Usuario();
        
        $totalLibros = count($libroModel->listar());
        $totalUsuarios = count($usuarioModel->listar());
        
        $librosRecientes = array_slice($libroModel->listar(), 0, 5);
        
        require_once 'views/home.php';
    }
}