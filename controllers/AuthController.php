<?php
/**
 * Controlador: AuthController
 * 
 * Descripción: Maneja la autenticación de usuarios
 * Autor: José Raphael Ernesto Pérez Hernández
 * Fecha: 19 de Octubre, 2025
 */

require_once 'core/models/Administrador.php';
require_once 'config/conexion.php';

class AuthController {
    private $db;
    private $administrador;
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $database = new Conexion();
        $this->db = $database->conectar();
        $this->administrador = new Administrador($this->db);
    }
    
    /**
     * Mostrar formulario de login
     */
    public function login() {
        // Si ya está autenticado, redirigir al dashboard
        if (isset($_SESSION['admin_id'])) {
            header('Location: index.php?ruta=home');
            exit();
        }
        
        // Mostrar vista de login
        require_once 'views/auth/login.php';
    }
    
    /**
     * Procesar login
     */
    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?ruta=login');
            exit();
        }
        
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validar campos
        if (empty($usuario) || empty($password)) {
            $_SESSION['error'] = 'Usuario y contraseña son obligatorios';
            header('Location: index.php?ruta=login');
            exit();
        }
        
        // Intentar login
        $admin = $this->administrador->login($usuario, $password);
        
        if ($admin) {
            // Login exitoso - Crear sesión
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_usuario'] = $admin['usuario'];
            $_SESSION['admin_nombre'] = $admin['nombre'];
            $_SESSION['admin_rol'] = $admin['rol'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['login_time'] = time();
            
            // Redirigir al dashboard
            header('Location: index.php?ruta=home');
            exit();
        } else {
            // Login fallido
            $_SESSION['error'] = 'Usuario o contraseña incorrectos';
            header('Location: index.php?ruta=login');
            exit();
        }
    }
    
    /**
     * Cerrar sesión
     */
    public function logout() {
        // Destruir todas las variables de sesión
        $_SESSION = array();
        
        // Destruir la cookie de sesión si existe
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Destruir la sesión
        session_destroy();
        
        // Redirigir al login
        header('Location: index.php?ruta=login');
        exit();
    }
    
    /**
     * Verificar si el usuario está autenticado
     */
    public static function verificarAutenticacion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['admin_id'])) {
            $_SESSION['error'] = 'Debe iniciar sesión para acceder';
            header('Location: index.php?ruta=login');
            exit();
        }
        
        // Verificar tiempo de sesión (opcional - 2 horas)
        if (isset($_SESSION['login_time'])) {
            $tiempoTranscurrido = time() - $_SESSION['login_time'];
            $tiempoMaximo = 2 * 60 * 60; // 2 horas
            
            if ($tiempoTranscurrido > $tiempoMaximo) {
                // Sesión expirada
                session_destroy();
                $_SESSION['error'] = 'Su sesión ha expirado. Por favor, inicie sesión nuevamente';
                header('Location: index.php?ruta=login');
                exit();
            }
        }
        
        return true;
    }
    
    /**
     * Verificar si el usuario es administrador
     */
    public static function verificarAdmin() {
        self::verificarAutenticacion();
        
        if ($_SESSION['admin_rol'] !== 'admin') {
            $_SESSION['error'] = 'No tiene permisos para acceder a esta sección';
            header('Location: index.php?ruta=home');
            exit();
        }
        
        return true;
    }
    
    /**
     * Obtener datos del usuario autenticado
     */
    public static function obtenerUsuarioActual() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['admin_id'])) {
            return null;
        }
        
        return [
            'id' => $_SESSION['admin_id'],
            'usuario' => $_SESSION['admin_usuario'],
            'nombre' => $_SESSION['admin_nombre'],
            'rol' => $_SESSION['admin_rol'],
            'email' => $_SESSION['admin_email'] ?? null
        ];
    }
}
?>