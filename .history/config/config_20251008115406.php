<?php
/**
 * SISTEMA DE BIBLIOTECA CIF
 * Archivo de Configuración General
 * Versión: 2.0 Profesional
 */

// Configuración de zona horaria
date_default_timezone_set('America/El_Salvador');

// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================
// CONFIGURACIÓN DE BASE DE DATOS
// ============================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'biblioteca_cif');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ============================================
// CONFIGURACIÓN DE RUTAS
// ============================================
define('BASE_URL', 'http://localhost/biblioteca-cif/');
define('BASE_PATH', __DIR__ . '/../');
define('UPLOAD_PATH', BASE_PATH . 'public/uploads/');
define('UPLOAD_URL', BASE_URL . 'public/uploads/');

// ============================================
// CONFIGURACIÓN DE LA APLICACIÓN
// ============================================
define('APP_NAME', 'Biblioteca CIF');
define('APP_VERSION', '2.0');
define('APP_AUTHOR', 'José Raphael Pérez');

// ============================================
// CONFIGURACIÓN DE SEGURIDAD
// ============================================
define('PASSWORD_MIN_LENGTH', 6);
define('SESSION_TIMEOUT', 3600); // 1 hora en segundos
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutos

// ============================================
// CONFIGURACIÓN DE EMAIL (para notificaciones futuras)
// ============================================
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'tu-email@gmail.com');
define('SMTP_PASS', 'tu-password');
define('SMTP_FROM', 'biblioteca@cif.edu.sv');
define('SMTP_FROM_NAME', 'Biblioteca CIF');

// ============================================
// CONFIGURACIÓN DE PRÉSTAMOS
// ============================================
define('DIAS_PRESTAMO_ESTUDIANTE', 7);
define('DIAS_PRESTAMO_DOCENTE', 15);
define('DIAS_PRESTAMO_ADMIN', 30);
define('MAX_PRESTAMOS_ESTUDIANTE', 3);
define('MAX_PRESTAMOS_DOCENTE', 5);
define('MAX_PRESTAMOS_ADMIN', 10);
define('MAX_RENOVACIONES', 2);

// ============================================
// CONFIGURACIÓN DE NOTIFICACIONES
// ============================================
define('DIAS_RECORDATORIO', 3);
define('ENVIAR_EMAIL_NOTIFICACIONES', false); // Cambiar a true cuando se configure email

// ============================================
// MODO DE DESARROLLO
// ============================================
define('DEBUG_MODE', true); // Cambiar a false en producción

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ============================================
// AUTOLOAD DE CLASES (simple)
// ============================================
spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . 'core/',
        BASE_PATH . 'models/',
        BASE_PATH . 'controllers/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// ============================================
// FUNCIONES HELPER GLOBALES
// ============================================

/**
 * Redirigir a una URL
 */
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

/**
 * Verificar si el usuario está autenticado
 */
function isLoggedIn() {
    return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
}

/**
 * Obtener el ID del usuario actual
 */
function getUserId() {
    return $_SESSION['usuario_id'] ?? null;
}

/**
 * Obtener el rol del usuario actual
 */
function getUserRole() {
    return $_SESSION['rol_nombre'] ?? null;
}

/**
 * Verificar si el usuario tiene un rol específico
 */
function hasRole($role) {
    return getUserRole() === $role;
}

/**
 * Verificar si el usuario es administrador
 */
function isAdmin() {
    return hasRole('administrador');
}

/**
 * Limpiar y sanitizar datos de entrada
 */
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Generar token CSRF
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verificar token CSRF
 */
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Mostrar mensaje de error o éxito
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type, // 'success', 'error', 'warning', 'info'
        'message' => $message
    ];
}

/**
 * Obtener y limpiar mensaje flash
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * Formatear fecha en español
 */
function formatearFecha($fecha, $formato = 'd/m/Y') {
    $meses = [
        'January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo',
        'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio',
        'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre',
        'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'
    ];
    
    $fecha_formateada = date($formato, strtotime($fecha));
    return str_replace(array_keys($meses), array_values($meses), $fecha_formateada);
}

/**
 * Calcular días entre dos fechas
 */
function diasEntreFechas($fecha1, $fecha2) {
    $datetime1 = new DateTime($fecha1);
    $datetime2 = new DateTime($fecha2);
    $interval = $datetime1->diff($datetime2);
    return $interval->days;
}

/**
 * Generar contraseña hash
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verificar contraseña
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Debug helper
 */
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

// ============================================
// ============================================
// VERIFICAR TIMEOUT DE SESIÓN
// ============================================

// Asegurar que la sesión esté iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (isLoggedIn()) {
    // Tiempo máximo de inactividad (en segundos)
    $timeout = defined('SESSION_TIMEOUT') ? SESSION_TIMEOUT : 1800; // 30 minutos por defecto

    // Última actividad registrada
    $last_activity = $_SESSION['last_activity'] ?? time();

    // Verificar si ha pasado el tiempo límite
    if ((time() - $last_activity) > $timeout) {
        session_unset();
        session_destroy();

        session_start(); // Reiniciar sesión para mostrar mensaje
        setFlashMessage('warning', 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.');
        redirect('/biblioteca-cif/auth/login'); // ✅ Ruta amigable
        exit();
    }

    // Actualizar marca de tiempo
    $_SESSION['last_activity'] = time();
}
