<?php
session_start();

// Obtener la ruta
$ruta = $_GET['ruta'] ?? 'landing';
$accion = $_GET['accion'] ?? 'index';

// Router
switch ($ruta) {
    // ==================== RUTAS PÚBLICAS ====================
    case 'landing':
        require_once __DIR__ . '/views/landing.php';
        break;
        
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/controllers/AuthController.php';
            $controller = new AuthController();
            $controller->login();
        } else {
            require_once __DIR__ . '/views/login.php';
        }
        break;
        
    case 'registro':
        require_once __DIR__ . '/controllers/RegistroController.php';
        $controller = new RegistroController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->registrar();
        } else {
            $controller->index();
        }
        break;
        
    case 'logout':
        session_destroy();
        header('Location: index.php?ruta=login');
        exit;
        break;

    // ==================== VERIFICAR LOGIN PARA RUTAS PROTEGIDAS ====================
    default:
        if (!isset($_SESSION['logueado'])) {
            header('Location: index.php?ruta=login');
            exit;
        }
        
        // ==================== RUTAS PROTEGIDAS ====================
        switch ($ruta) {
            case 'home':
                require_once __DIR__ . '/controllers/HomeController.php';
                $controller = new HomeController();
                $controller->index();
                break;

            case 'solicitudes':
                require_once __DIR__ . '/controllers/SolicitudController.php';
                $controller = new SolicitudController();
                
                switch ($accion) {
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

            case 'libros':
                require_once __DIR__ . '/controllers/LibroController.php';
                $controller = new LibroController();
                
                switch ($accion) {
                    case 'crear':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->store();
                        } else {
                            $controller->crear();
                        }
                        break;
                    case 'editar':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->update();
                        } else {
                            $controller->editar();
                        }
                        break;
                    case 'eliminar':
                        $controller->eliminar();
                        break;
                    case 'importar':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->importar();
                        } else {
                            require_once __DIR__ . '/views/libros/importar.php';
                        }
                        break;
                    default:
                        $controller->index();
                }
                break;

            case 'catalogo':
                require_once __DIR__ . '/views/usuario/catalogo.php';
                break;

            case 'mis_prestamos':
                require_once __DIR__ . '/views/usuario/mis_prestamos.php';
                break;

            case 'prestamos':
                require_once __DIR__ . '/controllers/PrestamoController.php';
                $controller = new PrestamoController();
                
                switch ($accion) {
                    case 'crear':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->store();
                        } else {
                            $controller->crear();
                        }
                        break;
                    case 'solicitar':
                        $controller->solicitar();
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

            case 'usuarios':
                require_once __DIR__ . '/controllers/UsuarioController.php';
                $controller = new UsuarioController();
                
                switch ($accion) {
                    case 'crear':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->store();
                        } else {
                            $controller->crear();
                        }
                        break;
                    case 'editar':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->update();
                        } else {
                            $controller->editar();
                        }
                        break;
                    case 'eliminar':
                        $controller->eliminar();
                        break;
                    default:
                        $controller->index();
                }
                break;

            case 'importar':
                require_once __DIR__ . '/controllers/ImportarController.php';
                $controller = new ImportarController();
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->importar();
                } else {
                    $controller->index();
                }
                break;

            default:
                header('Location: index.php?ruta=home');
                exit;
        }
}