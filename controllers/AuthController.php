<?php
require_once __DIR__ . '/../config/Database.php';

class AuthController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function mostrarLogin() {
        require_once __DIR__ . '/../views/login.php';
    }
    
    public function login() {
        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Por favor complete todos los campos';
                header('Location: index.php?ruta=login');
                exit;
            }
            
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$usuario) {
                $_SESSION['error'] = 'Credenciales incorrectas';
                header('Location: index.php?ruta=login');
                exit;
            }
            
            if ($usuario['estado'] === 'pendiente') {
                $_SESSION['error'] = 'Tu cuenta está pendiente de aprobación';
                header('Location: index.php?ruta=login');
                exit;
            }
            
            if ($usuario['estado'] === 'inactivo') {
                $_SESSION['error'] = 'Tu cuenta ha sido desactivada';
                header('Location: index.php?ruta=login');
                exit;
            }
            
            if (password_verify($password, $usuario['password'])) {
                $_SESSION['logueado'] = true;
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
                
                if ($usuario['tipo_usuario'] === 'admin') {
                    header('Location: index.php?ruta=home');
                } else {
                    header('Location: index.php?ruta=catalogo');
                }
                exit;
            } else {
                $_SESSION['error'] = 'Credenciales incorrectas';
                header('Location: index.php?ruta=login');
                exit;
            }
            
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error del sistema: ' . $e->getMessage();
            header('Location: index.php?ruta=login');
            exit;
        }
    }
    
    public function logout() {
        session_destroy();
        header('Location: index.php?ruta=login');
        exit;
    }
}