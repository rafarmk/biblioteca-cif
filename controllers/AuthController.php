<?php
require_once 'core/models/Administrador.php';

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $admin = new Administrador();
            $resultado = $admin->login($email, $password);
            
            if ($resultado) {
                $_SESSION['admin_id'] = $resultado['id'];
                $_SESSION['admin_nombre'] = $resultado['nombre'];
                $_SESSION['admin_email'] = $resultado['email'];
                $_SESSION['admin_rol'] = $resultado['rol'];
                $_SESSION['logueado'] = true;
                
                // Redirigir al LANDING (página de bienvenida bonita)
                header('Location: index.php?ruta=landing');
                exit();
            } else {
                $error = "Email o contraseña incorrectos";
                require_once 'views/auth/login.php';
            }
        } else {
            require_once 'views/auth/login.php';
        }
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?ruta=login');
        exit();
    }
}
