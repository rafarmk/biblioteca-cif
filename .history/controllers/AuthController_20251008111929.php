<?php
/**
 * AuthController
 * Maneja autenticación: login, logout, registro
 * Sistema de Biblioteca CIF
 */

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelos/Usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $conexion = new Conexion();
        $db = $conexion->conectar();
        $this->usuarioModel = new Usuario($db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
                setFlashMessage('error', 'Token de seguridad inválido');
                redirect('views/auth/login.php');
                return;
            }

            $email = cleanInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                setFlashMessage('error', 'Por favor complete todos los campos');
                redirect('views/auth/login.php');
                return;
            }

            $usuario = $this->usuarioModel->login($email, $password);

            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['apellidos'] = $usuario['apellidos'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['rol_id'] = $usuario['rol_id'];
                $_SESSION['rol_nombre'] = $usuario['rol_nombre'];
                $_SESSION['foto_perfil'] = $usuario['foto_perfil'];
                $_SESSION['last_activity'] = time();
                $_SESSION['login_time'] = time();

                setFlashMessage('success', '¡Bienvenido, ' . $usuario['nombre'] . '!');
                $this->redirigirPorRol($usuario['rol_nombre']);
            } else {
                setFlashMessage('error', 'Email o contraseña incorrectos');
                redirect('views/auth/login.php');
            }
        } else {
            require_once __DIR__ . '/../views/auth/login.php';
        }
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
                setFlashMessage('error', 'Token de seguridad inválido');
                redirect('views/auth/registro.php');
                return;
            }

            $datos = [
                'nombre' => cleanInput($_POST['nombre'] ?? ''),
                'apellidos' => cleanInput($_POST['apellidos'] ?? ''),
                'email' => cleanInput($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'password_confirm' => $_POST['password_confirm'] ?? '',
                'documento' => cleanInput($_POST['documento'] ?? ''),
                'telefono' => cleanInput($_POST['telefono'] ?? ''),
                'rol_id' => 4
            ];

            if ($datos['password'] !== $datos['password_confirm']) {
                setFlashMessage('error', 'Las contraseñas no coinciden');
                redirect('views/auth/registro.php');
                return;
            }

            $errores = $this->usuarioModel->validar($datos);

            if (!empty($errores)) {
                setFlashMessage('error', implode('<br>', $errores));
                redirect('views/auth/registro.php');
                return;
            }

            $resultado = $this->usuarioModel->registrar($datos);

            if ($resultado['success']) {
                setFlashMessage('success', 'Registro exitoso. Por favor, inicia sesión.');
                redirect('views/auth/login.php');
            } else {
                setFlashMessage('error', $resultado['message']);
                redirect('views/auth/registro.php');
            }
        } else {
            require_once __DIR__ . '/../views/auth/registro.php';
        }
    }

    public function logout() {
        session_unset();
        session_destroy();

        session_start();
        setFlashMessage('success', 'Has cerrado sesión correctamente');
        redirect('views/auth/login.php');
    }

    public function verificarAutenticacion() {
        if (!isLoggedIn()) {
            setFlashMessage('warning', 'Debes iniciar sesión para acceder');
            redirect('views/auth/login.php');
            exit();
        }
    }

    public function verificarRol($rolesPermitidos = []) {
        $this->verificarAutenticacion();
        $rolActual = getUserRole();

        if (!in_array($rolActual, $rolesPermitidos)) {
            setFlashMessage('error', 'No tienes permisos para acceder a esta sección');
            $this->redirigirPorRol($rolActual);
            exit();
        }
    }

    private function redirigirPorRol($rol) {
        switch ($rol) {
            case 'administrador':
            case 'bibliotecario':
                redirect('views/dashboard/admin.php');
                break;
            case 'docente':
            case 'estudiante':
            default:
                redirect('views/dashboard/usuario.php');
                break;
        }
    }

    public function obtenerUsuarioActual() {
        if (!isLoggedIn()) {
            return null;
        }

        return $this->usuarioModel->obtenerPorId(getUserId());
    }
}
