<?php
require_once __DIR__ . '/../config/Database.php';

class AuthController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            try {
                $stmt = $this->db->prepare("
                    SELECT id, nombre, email, password, tipo_usuario, estado 
                    FROM usuarios 
                    WHERE email = ?
                ");
                $stmt->execute([$email]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario && password_verify($password, $usuario['password'])) {
                    if ($usuario['estado'] !== 'activo') {
                        $error = "Tu cuenta está pendiente de aprobación o inactiva";
                    } else {
                        $_SESSION['logueado'] = true;
                        $_SESSION['usuario_id'] = $usuario['id'];
                        $_SESSION['usuario_nombre'] = $usuario['nombre'];
                        $_SESSION['usuario_email'] = $usuario['email'];
                        $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

                        header('Location: index.php?ruta=landing');
                        exit;
                    }
                } else {
                    $error = "Credenciales incorrectas";
                }
            } catch (PDOException $e) {
                $error = "Error en el sistema: " . $e->getMessage();
            }
        }

        require_once __DIR__ . '/../views/login.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?ruta=login');
        exit;
    }
}