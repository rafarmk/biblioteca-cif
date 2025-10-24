<?php
require_once 'core/models/Prestamo.php';
require_once 'models/Libro.php';
require_once 'models/Usuario.php';

class PrestamoController {
    
    public function index() {
        $prestamo = new Prestamo();
        $prestamo->actualizarAtrasados();
        $prestamos = $prestamo->listarTodos();
        require_once 'views/prestamos/index.php';
    }
    
    public function activos() {
        $prestamo = new Prestamo();
        $prestamo->actualizarAtrasados();
        $prestamos = $prestamo->listarActivos();
        require_once 'views/prestamos/activos.php';
    }
    
    public function atrasados() {
        $prestamo = new Prestamo();
        $prestamo->actualizarAtrasados();
        $prestamos = $prestamo->listarAtrasados();
        require_once 'views/prestamos/atrasados.php';
    }
    
    public function crear() {
        $libro = new Libro();
        $usuario = new Usuario();
        
        $libros = $libro->listar();
        $usuarios = $usuario->listar();
        
        require_once 'views/prestamos/crear.php';
    }
    
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prestamo = new Prestamo();
            
            $prestamo->usuario_id = $_POST['usuario_id'];
            $prestamo->libro_id = $_POST['libro_id'];
            $prestamo->fecha_prestamo = $_POST['fecha_prestamo'];
            $prestamo->fecha_devolucion_esperada = $_POST['fecha_devolucion_esperada'];
            $prestamo->estado = 'activo';
            $prestamo->notas = $_POST['notas'] ?? '';
            
            if (!$prestamo->libroDisponible($prestamo->libro_id)) {
                $_SESSION['error'] = "El libro no está disponible para préstamo";
                header('Location: index.php?ruta=prestamos/crear');
                exit();
            }
            
            if ($prestamo->crear()) {
                $_SESSION['mensaje'] = "Préstamo registrado exitosamente";
                header('Location: index.php?ruta=prestamos/activos');
            } else {
                $_SESSION['error'] = "Error al registrar el préstamo";
                header('Location: index.php?ruta=prestamos/crear');
            }
            exit();
        }
    }
    
    public function ver() {
        if (isset($_GET['id'])) {
            $prestamo = new Prestamo();
            $datos = $prestamo->obtenerPorId($_GET['id']);
            
            if ($datos) {
                require_once 'views/prestamos/ver.php';
            } else {
                $_SESSION['error'] = "Préstamo no encontrado";
                header('Location: index.php?ruta=prestamos');
                exit();
            }
        }
    }
    
    public function devolver() {
        if (isset($_GET['id'])) {
            $prestamo = new Prestamo();
            $fecha_devolucion = date('Y-m-d');
            $notas = $_POST['notas'] ?? '';
            
            if ($prestamo->devolver($_GET['id'], $fecha_devolucion, $notas)) {
                $_SESSION['mensaje'] = "Libro devuelto exitosamente";
            } else {
                $_SESSION['error'] = "Error al registrar la devolución";
            }
            
            header('Location: index.php?ruta=prestamos/activos');
            exit();
        }
    }
}