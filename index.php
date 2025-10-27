<?php
session_start();

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir configuración de base de datos
require_once 'config/Database.php';

// Crear conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Obtener parámetros de la URL
$ruta = $_GET['ruta'] ?? 'login';
$accion = $_GET['accion'] ?? 'index';

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
        // Si ya está logueado, redirigir al LANDING
        if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
            header('Location: index.php?ruta=landing');
            exit();
        }
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($db);
        $controller->login();
        break;
        
    case 'logout':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($db);
        $controller->logout();
        break;
        
    case 'landing':
        // PERMITIR que usuarios logueados vean el landing
        require_once 'views/landing.php';
        break;
        
    case 'home':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController($db);
        $controller->index();
        break;
        
    case 'usuarios':
        require_once 'controllers/UsuarioController.php';
        $controller = new UsuarioController($db);
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
        }
        break;
        
    case 'libros':
        require_once 'controllers/LibroController.php';
        $controller = new LibroController($db);
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
        }
        break;
        
    case 'prestamos':
        require_once 'controllers/PrestamoController.php';
        $controller = new PrestamoController($db);
        switch ($accion) {
            case 'crear':
                $controller->crear();
                break;
            case 'editar':
                $controller->editar();
                break;
            case 'devolver':
                $controller->devolver();
                break;
            case 'eliminar':
                $controller->eliminar();
                break;
            default:
                $controller->index();
        }
        break;
        
    default:
        // Por defecto ir al login si no está logueado, o al home si lo está
        if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
            header('Location: index.php?ruta=home');
        } else {
            header('Location: index.php?ruta=login');
        }
        exit();
}
?>
