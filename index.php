<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/Database.php';

$database = new Database();
$db = $database->getConnection();

// Funciones helper para permisos
function esAdmin() {
    $tipoUsuario = $_SESSION['tipo_usuario'] ?? '';
    return in_array($tipoUsuario, ['administrador', 'personal_administrativo', 'personal_operativo']);
}

function esUsuarioNormal() {
    $tipoUsuario = $_SESSION['tipo_usuario'] ?? '';
    return !in_array($tipoUsuario, ['administrador', 'personal_administrativo', 'personal_operativo']);
}

$ruta = $_GET['ruta'] ?? 'login';
$accion = $_GET['accion'] ?? 'index';

$rutasPublicas = ['login', 'landing', 'registro'];

if (!in_array($ruta, $rutasPublicas)) {
    if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
        header('Location: index.php?ruta=login');
        exit();
    }
}

switch ($ruta) {
    case 'login':
        if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
            header('Location: index.php?ruta=landing');
            exit();
        }
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($db);
        $controller->login();
        break;

    case 'registro':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($db);
        $controller->registrar();
        break;

    case 'logout':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($db);
        $controller->logout();
        break;

    case 'landing':
        require_once 'controllers/LandingController.php';
        $controller = new LandingController($db);
        $controller->index();
        break;

    case 'home':
        // Solo admins pueden ver el dashboard
        if (!esAdmin()) {
            header('Location: index.php?ruta=catalogo');
            exit();
        }
        require_once 'controllers/HomeController.php';
        $controller = new HomeController($db);
        $controller->index();
        break;

    case 'libros':
        // Solo admins pueden gestionar libros (CRUD)
        if (!esAdmin()) {
            header('Location: index.php?ruta=catalogo');
            exit();
        }
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

    case 'usuarios':
        // Solo admins pueden gestionar usuarios
        if (!esAdmin()) {
            header('Location: index.php?ruta=catalogo');
            exit();
        }
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
            case 'aprobar':
                $controller->aprobar();
                break;
            case 'rechazar':
                $controller->rechazar();
                break;
            default:
                $controller->index();
        }
        break;

    case 'prestamos':
        // Solo admins pueden gestionar TODOS los préstamos
        if (!esAdmin()) {
            header('Location: index.php?ruta=catalogo');
            exit();
        }
        require_once 'controllers/PrestamoController.php';
        $controller = new PrestamoController($db);
        switch ($accion) {
            case 'crear':
                $controller->crear();
                break;
            case 'devolver':
                $controller->devolver();
                break;
            case 'ver':
                $controller->ver();
                break;
            default:
                $controller->index();
        }
        break;

    case 'importar':
        // Solo admins pueden importar
        if (!esAdmin()) {
            header('Location: index.php?ruta=catalogo');
            exit();
        }
        require_once 'controllers/ImportController.php';
        $controller = new ImportController($db);
        $controller->index();
        break;

    case 'catalogo':
        // Todos pueden ver el catálogo
        require_once 'controllers/CatalogoController.php';
        $controller = new CatalogoController($db);
        $controller->index();
        break;

        case 'solicitar':
        // Usuarios comunes pueden solicitar préstamos
        require_once 'controllers/PrestamoController.php';
        $controller = new PrestamoController($db);
        $controller->solicitar();
        break;
case 'mis-prestamos':
        // Todos pueden ver sus propios préstamos
        require_once 'controllers/MisPrestamosController.php';
        $controller = new MisPrestamosController($db);
        $controller->index();
        break;

    case 'perfil':
        // Todos pueden ver/editar su perfil
        require_once 'controllers/PerfilController.php';
        $controller = new PerfilController($db);
        switch ($accion) {
            case 'editar':
                $controller->editar();
                break;
            default:
                $controller->index();
        }
        break;

    default:
        header('Location: index.php?ruta=login');
        exit();
}
?>