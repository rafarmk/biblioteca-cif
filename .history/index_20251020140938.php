<?php
/**
 * Front Controller - Sistema Biblioteca CIF
 * 
 * Descripción: Enrutador principal del sistema
 * Autor: José Raphael Ernesto Pérez Hernández
 * Fecha: 15 de Octubre, 2025
 * Versión: 2.2 - Corrección de rutas para usuarios
 */

// ========================================
// CONFIGURACIÓN INICIAL
// ========================================

// Iniciar sesión
session_start();

// Mostrar errores en desarrollo (cambiar a 0 en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir la ruta base del proyecto
define('BASE_PATH', __DIR__);

// ========================================
// ENRUTAMIENTO
// ========================================

/**
 * Obtener la ruta solicitada
 * Método 1: Con parámetro ?ruta= (ej: index.php?ruta=libros)
 * Método 2: URLs limpias (ej: /biblioteca-cif/libros)
 */

$ruta = '';

// Primero intentar con parámetro GET
if (isset($_GET['ruta'])) {
    $ruta = $_GET['ruta'];
} else {
    // Método alternativo: parsear URI
    $uri = trim($_SERVER['REQUEST_URI'], '/');
    $base = 'biblioteca-cif';
    
    // Remover el nombre del proyecto de la URI si existe
    if (strpos($uri, $base) === 0) {
        $uri = substr($uri, strlen($base));
        $uri = trim($uri, '/');
    }
    
    // Remover parámetros query string
    if (($pos = strpos($uri, '?')) !== false) {
        $uri = substr($uri, 0, $pos);
    }
    
    $ruta = $uri;
}

// Si no hay ruta, mostrar página de inicio
if (empty($ruta) || $ruta === 'home') {
    // Verificar que el archivo existe antes de incluirlo
    if (file_exists(BASE_PATH . '/views/home.php')) {
        require_once BASE_PATH . '/views/home.php';
    } else {
        // Si no existe home.php, mostrar página por defecto
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <title>Sistema Biblioteca CIF</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body class='bg-light'>
            <div class='container mt-5'>
                <div class='alert alert-warning'>
                    <h4>⚠️ Archivo home.php no encontrado</h4>
                    <p>Por favor, crea el archivo: <code>views/home.php</code></p>
                    <hr>
                    <a href='?ruta=libros' class='btn btn-primary'>Ver Libros</a>
                    <a href='?ruta=usuarios' class='btn btn-success'>Ver Usuarios</a>
                </div>
            </div>
        </body>
        </html>";
    }
    exit;
}

// ========================================
// PARSEAR RUTA
// ========================================

$partes = explode('/', $ruta);
$controlador = $partes[0] ?? 'home';
$accion = $partes[1] ?? 'index';
$id = $_GET['id'] ?? null;

// ========================================
// ENRUTAMIENTO POR CONTROLADOR
// ========================================

switch ($controlador) {
    // ========================================
    // CONTROLADOR: LIBROS
    // ========================================
    case 'libros':
        // Verificar que el controlador existe
        $controllerPath = BASE_PATH . '/controllers/LibroController.php';
        
        if (!file_exists($controllerPath)) {
            die("Error: No se encontró el controlador LibroController.php en " . $controllerPath);
        }
        
        require_once $controllerPath;
        
        // Verificar que la clase existe
        if (!class_exists('LibroController')) {
            die("Error: La clase LibroController no existe");
        }
        
        $controller = new LibroController();
        
        // Enrutar según la acción
        switch ($accion) {
            case 'index':
            case 'listar':
                $controller->index();
                break;
            
            case 'crear':
            case 'nuevo':
                $controller->crear();
                break;
            
            case 'guardar':
            case 'store':
                $controller->guardar();
                break;
            
            case 'editar':
            case 'edit':
                if ($id) {
                    $controller->editar($id);
                } else {
                    $_SESSION['error'] = "❌ ID no proporcionado para editar";
                    header('Location: ?ruta=libros');
                    exit;
                }
                break;
            
            case 'actualizar':
            case 'update':
                if ($id) {
                    $controller->actualizar($id);
                } else {
                    $_SESSION['error'] = "❌ ID no proporcionado para actualizar";
                    header('Location: ?ruta=libros');
                    exit;
                }
                break;
            
            case 'eliminar':
            case 'delete':
                if ($id) {
                    $controller->eliminar($id);
                } else {
                    $_SESSION['error'] = "❌ ID no proporcionado para eliminar";
                    header('Location: ?ruta=libros');
                    exit;
                }
                break;
            
            case 'buscar':
            case 'search':
                $controller->buscar();
                break;
            
            case 'categoria':
                $controller->categoria();
                break;
            
            case 'ver':
            case 'show':
                if ($id) {
                    $controller->ver($id);
                } else {
                    $_SESSION['error'] = "❌ ID no proporcionado";
                    header('Location: ?ruta=libros');
                    exit;
                }
                break;
            
            default:
                // Acción no reconocida, mostrar listado
                $controller->index();
        }
        break;
    
    // ========================================
    // CONTROLADOR: USUARIOS
    // ========================================
    case 'usuarios':
        // Verificar que el controlador existe
        $controllerPath = BASE_PATH . '/controllers/UsuarioController.php';
        
        if (!file_exists($controllerPath)) {
            die("Error: No se encontró el controlador UsuarioController.php en " . $controllerPath);
        }
        
        require_once $controllerPath;
        
        // Verificar que la clase existe
        if (!class_exists('UsuarioController')) {
            die("Error: La clase UsuarioController no existe");
        }
        
        $controller = new UsuarioController();
        
        // Enrutar según la acción
        switch ($accion) {
            case 'index':
            case 'listar':
                $controller->index();
                break;
            
            case 'crear':
            case 'nuevo':
                // Si es POST, guardar. Si es GET, mostrar formulario
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->guardar();
                } else {
                    $controller->crear();
                }
                break;
            
            case 'guardar':
            case 'store':
                $controller->guardar();
                break;
            
            case 'editar':
            case 'edit':
                if ($id) {
                    // Si es POST, actualizar. Si es GET, mostrar formulario
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->actualizar();
                    } else {
                        $controller->editar();
                    }
                } else {
                    $_SESSION['error'] = "❌ ID no proporcionado para editar";
                    header('Location: ?ruta=usuarios');
                    exit;
                }
                break;
            
            case 'actualizar':
            case 'update':
                $controller->actualizar();
                break;
            
            case 'eliminar':
            case 'delete':
                if ($id) {
                    $controller->eliminar();
                } else {
                    $_SESSION['error'] = "❌ ID no proporcionado para eliminar";
                    header('Location: ?ruta=usuarios');
                    exit;
                }
                break;
            
            case 'buscar':
            case 'search':
                $controller->buscar();
                break;
            
            case 'ver':
            case 'show':
                if ($id) {
                    $controller->ver();
                } else {
                    $_SESSION['error'] = "❌ ID no proporcionado";
                    header('Location: ?ruta=usuarios');
                    exit;
                }
                break;
            
            default:
                // Acción no reconocida, mostrar listado
                $controller->index();
        }
        break;
    
    // ========================================
    // CONTROLADOR: PRÉSTAMOS (Próximamente)
    // ========================================
    case 'prestamos':
        mostrarProximamente('Préstamos', 'hand-holding-heart', 'Registrar préstamos, devoluciones y controlar multas');
        break;
    
    // ========================================
    // CONTROLADOR: DASHBOARD (Próximamente)
    // ========================================
    case 'dashboard':
        mostrarProximamente('Dashboard', 'chart-line', 'Ver gráficas, reportes y análisis del sistema');
        break;
    
    // ========================================
    // CONTROLADOR: AUTENTICACIÓN (Próximamente)
    // ========================================
    case 'auth':
case 'login':
    mostrarProximamente('Login', 'lock', 'Iniciar sesión de forma segura');
    break;
    
    // ========================================
    // RUTA POR DEFECTO
    // ========================================
    default:
        // Si la ruta no existe, mostrar página de inicio
        if (file_exists(BASE_PATH . '/views/home.php')) {
            require_once BASE_PATH . '/views/home.php';
        } else {
            // Página 404
            http_response_code(404);
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>404 - Página No Encontrada</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        min-height: 100vh;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    .error-card {
                        background: white;
                        padding: 60px;
                        border-radius: 20px;
                        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                        text-align: center;
                        max-width: 500px;
                    }
                    .error-card h1 {
                        font-size: 120px;
                        color: #667eea;
                        margin: 0;
                        font-weight: bold;
                    }
                    .btn-custom {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        padding: 12px 30px;
                        border-radius: 10px;
                        text-decoration: none;
                        display: inline-block;
                        margin-top: 20px;
                        font-weight: 600;
                    }
                </style>
            </head>
            <body>
                <div class="error-card">
                    <h1>404</h1>
                    <h3 class="mt-3">Página No Encontrada</h3>
                    <p class="text-muted mt-3">La ruta "<?= htmlspecialchars($ruta) ?>" no existe en el sistema.</p>
                    <a href="?ruta=home" class="btn-custom">
                        🏠 Ir al Inicio
                    </a>
                    <a href="?ruta=libros" class="btn-custom">
                        📚 Ver Libros
                    </a>
                    <a href="?ruta=usuarios" class="btn-custom">
                        👥 Ver Usuarios
                    </a>
                </div>
            </body>
            </html>
            <?php
        }
        break;
}

// ========================================
// FUNCIONES AUXILIARES
// ========================================

/**
 * Mostrar página de "Próximamente"
 * 
 * @param string $titulo Título de la sección
 * @param string $icono Nombre del icono de Font Awesome
 * @param string $descripcion Descripción de la funcionalidad
 */
function mostrarProximamente($titulo, $icono, $descripcion) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $titulo ?> - Próximamente</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .prox-card {
                background: white;
                padding: 60px;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                text-align: center;
                max-width: 500px;
                animation: fadeIn 0.5s ease;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .prox-card i {
                font-size: 5rem;
                color: #667eea;
                margin-bottom: 20px;
            }
            .prox-card h1 {
                color: #667eea;
                margin-bottom: 20px;
                font-weight: bold;
            }
            .prox-card .badge {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 8px 20px;
                font-size: 0.9rem;
                margin-bottom: 20px;
            }
            .btn-custom {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 12px 30px;
                border-radius: 10px;
                text-decoration: none;
                display: inline-block;
                margin-top: 20px;
                font-weight: 600;
                transition: transform 0.3s ease;
            }
            .btn-custom:hover {
                transform: translateY(-3px);
                box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
                color: white;
            }
        </style>
    </head>
    <body>
        <div class="prox-card">
            <i class="fas fa-<?= $icono ?>"></i>
            <span class="badge">Próximamente</span>
            <h1><?= $titulo ?></h1>
            <p class="lead">Esta funcionalidad estará disponible pronto.</p>
            <p class="text-muted"><?= $descripcion ?></p>
            <div class="mt-4">
                <a href="?ruta=home" class="btn-custom">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <a href="?ruta=libros" class="btn-custom">
                    <i class="fas fa-book"></i> Libros
                </a>
                <a href="?ruta=usuarios" class="btn-custom">
                    <i class="fas fa-users"></i> Usuarios
                </a>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    </body>
    </html>
    <?php
    exit;
}
?>