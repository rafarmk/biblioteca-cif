<?php
session_start();

// Obtener la ruta solicitada
$ruta = $_GET['ruta'] ?? 'landing';
$accion = $_GET['accion'] ?? 'index';

// Rutas públicas (no requieren autenticación)
$rutasPublicas = ['login', 'landing', 'registro'];

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

    case 'registro':
        // CRÍTICO: Limpiar cualquier sesión activa antes de registrar
        if (isset($_SESSION['logueado'])) {
            $_SESSION = array();
            session_destroy();
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'config/Database.php';
            
            try {
                $db = new Database();
                $conn = $db->getConnection();
                
                // Verificar si el email ya existe
                $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
                $stmt->execute([$_POST['email']]);
                
                if ($stmt->fetch()) {
                    $_SESSION['error'] = "El correo electrónico ya está registrado en el sistema";
                    header('Location: index.php?ruta=registro');
                    exit;
                }
                
                // Hash de la contraseña
                $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                
                // Insertar nuevo usuario
                $stmt = $conn->prepare("
                    INSERT INTO usuarios 
                    (nombre, apellido, email, telefono, dui, direccion, password, tipo_usuario, rol, estado, puede_prestar, dias_max_prestamo, max_libros_simultaneos)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente', 1, 7, 3)
                ");
                
                $stmt->execute([
                    $_POST['nombre'],
                    $_POST['apellido'],
                    $_POST['email'],
                    $_POST['telefono'] ?? null,
                    $_POST['dui'] ?? null,
                    $_POST['direccion'] ?? null,
                    $password_hash,
                    $_POST['tipo_usuario'],
                    'usuario'
                ]);
                
                // Redirigir al login con mensaje de éxito
                header('Location: index.php?ruta=login&mensaje=registro_exitoso');
                exit;
                
            } catch (PDOException $e) {
                $_SESSION['error'] = "Error al crear la cuenta: " . $e->getMessage();
                header('Location: index.php?ruta=registro');
                exit;
            }
        }
        
        require_once 'views/registro_publico.php';
        break;

    case 'solicitudes':
        require_once 'controllers/SolicitudController.php';
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
                break;
        }
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
            case 'importar':
                require_once 'controllers/ImportarController.php';
                $controller = new ImportarController();
                
                $accion = $_GET['accion'] ?? 'index';
                if ($accion === 'procesar') {
                    $controller->procesar();
                } else {
                    $controller->index();
                }
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
            case 'importar':
                $controller->importar();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    case 'prestamos':
        require_once 'controllers/PrestamoController.php';
        $controller = new PrestamoController();
        
        switch ($accion) {
            case 'crear':
                $controller->crear();
                break;
            case 'guardar':
                $controller->guardar();
                break;
            case 'ver':
                $controller->ver();
                break;
            case 'devolver':
                $controller->devolver();
                break;
            case 'activos':
                $controller->activos();
                break;
            case 'atrasados':
                $controller->atrasados();
                break;
            case 'historialUsuario':
                $controller->historialUsuario();
                break;
            case 'historialLibro':
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