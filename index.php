<?php
session_start();

$ruta = $_GET['ruta'] ?? 'landing';
$accion = $_GET['accion'] ?? 'index';

// Rutas públicas (no requieren autenticación)
$rutasPublicas = ['landing', 'login', 'registro'];

switch ($ruta) {
    case 'landing':
        if (isset($_SESSION['logueado'])) {
            // Si está logueado, redirigir según tipo de usuario
            if ($_SESSION['tipo_usuario'] === 'administrador') {
                header('Location: index.php?ruta=home');
            } else {
                header('Location: index.php?ruta=catalogo');
            }
            exit;
        }
        require_once __DIR__ . '/views/landing.php';
        break;
        
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/controllers/AuthController.php';
            $controller = new AuthController();
            $controller->login();
        } else {
            if (isset($_SESSION['logueado'])) {
                // Si está logueado, redirigir según tipo de usuario
                if ($_SESSION['tipo_usuario'] === 'administrador') {
                    header('Location: index.php?ruta=home');
                } else {
                    header('Location: index.php?ruta=catalogo');
                }
                exit;
            }
            require_once __DIR__ . '/views/login.php';
        }
        break;
        
    case 'registro':
        if (isset($_SESSION['logueado'])) {
            header('Location: index.php?ruta=home');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/controllers/RegistroController.php';
            $controller = new RegistroController();
            $controller->registrar();
        } else {
            require_once __DIR__ . '/views/registro_publico.php';
        }
        break;
        
    case 'logout':
        session_destroy();
        header('Location: index.php?ruta=login');
        exit;
        break;

    default:
        // Verificar autenticación para rutas protegidas
        if (!isset($_SESSION['logueado'])) {
            header('Location: index.php?ruta=login');
            exit;
        }
        
        switch ($ruta) {
            case 'home':
                require_once __DIR__ . '/controllers/HomeController.php';
                $controller = new HomeController();
                $controller->index();
                break;

            // ========================================
            // 🔔 RUTAS DE SOLICITUDES DE ACCESO
            // ========================================
            case 'solicitudes':
            case 'solicitudes/aprobar':
            case 'solicitudes/rechazar':
            case 'solicitudes/ver':
                require_once __DIR__ . '/controllers/SolicitudController.php';
                $controller = new SolicitudController();

                switch ($ruta) {
                    case 'solicitudes/aprobar':
                        $controller->aprobar();
                        break;
                    case 'solicitudes/rechazar':
                        $controller->rechazar();
                        break;
                    case 'solicitudes/ver':
                        $controller->ver();
                        break;
                    default:
                        $controller->index();
                        break;
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
            case 'prestamos/activos':
            case 'prestamos/atrasados':
            case 'prestamos/crear':
            case 'prestamos/guardar':
            case 'prestamos/ver':
            case 'prestamos/devolver':
            case 'prestamos/historialUsuario':
            case 'prestamos/historialLibro':
                require_once __DIR__ . '/controllers/PrestamoController.php';
                $controller = new PrestamoController();

                switch ($ruta) {
                    case 'prestamos/activos':
                        $controller->activos();
                        break;
                    case 'prestamos/atrasados':
                        $controller->atrasados();
                        break;
                    case 'prestamos/crear':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->store();
                        } else {
                            $controller->crear();
                        }
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
                    case 'prestamos/solicitar':
                        $controller->solicitar();
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

            default:
                header('Location: index.php?ruta=home');
                exit;
        }
}
