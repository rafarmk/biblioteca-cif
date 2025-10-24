<?php
session_start();

// Obtener la ruta solicitada
$ruta = $_GET['ruta'] ?? 'landing';  // Ruta por defecto: landing
$accion = $_GET['accion'] ?? 'index';  // Acción por defecto: index

// Rutas públicas (no requieren autenticación)
$rutasPublicas = ['login', 'landing'];

// Verificar autenticación para rutas protegidas
if (!in_array($ruta, $rutasPublicas)) {
    if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
        header('Location: index.php?ruta=login');
        exit();
    }
}

// Enrutamiento principal
switch ($ruta) {
    case 'login':
        if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
            header('Location: index.php?ruta=landing');
            exit();
        }
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case 'logout':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'landing':
        require_once 'views/landing.php';
        break;

    case 'home':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    case 'usuarios':
        require_once 'controllers/UsuarioController.php';
        $controller = new UsuarioController();
        switch ($accion) {
            case 'crear':
                $controller->crear();
                break;
            case 'editar':
                $controller->editar();
                break;
            case 'eliminar':
                $controller->eliminar();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    case 'libros':
        require_once 'controllers/LibroController.php';
        $controller = new LibroController();
        switch ($accion) {
            case 'crear':
                $controller->crear();
                break;
            case 'editar':
                $controller->editar();
                break;
            case 'eliminar':
                $controller->eliminar();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    case 'prestamos':
    case 'prestamos/activos':
    case 'prestamos/atrasados':
    case 'prestamos/crear':
    case 'prestamos/guardar':
    case 'prestamos/ver':
    case 'prestamos/devolver':
    case 'prestamos/historialUsuario':
    case 'prestamos/historialLibro':
        require_once 'controllers/PrestamoController.php';
        $controller = new PrestamoController();

        // Enrutamiento específico para préstamos
        switch ($ruta) {
            case 'prestamos/activos':
                $controller->activos();
                break;
            case 'prestamos/atrasados':
                $controller->atrasados();
                break;
            case 'prestamos/crear':
                $controller->crear();
                break;
            case 'prestamos/guardar':
                $controller->guardar();
                break;
            case 'prestamos/ver':
                $controller->ver();
                break;
            case 'prestamos/devolver':
                $controller->devolver();
                break;
            case 'prestamos/historialUsuario':
                $controller->historialUsuario();
                break;
            case 'prestamos/historialLibro':
                $controller->historialLibro();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    default:
        header('Location: index.php?ruta=landing');
        exit();
}