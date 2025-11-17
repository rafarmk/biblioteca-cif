<?php
require_once __DIR__ . '/../config/Database.php';

class RegistroController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function index() {
        require_once __DIR__ . '/../views/registro_publico.php';
    }
    
    public function registrar() {
        try {
            $nombre = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $dui = trim($_POST['dui'] ?? '');
            $tipo_usuario = $_POST['tipo_usuario'] ?? '';
            
            // Validaciones
            if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($tipo_usuario)) {
                $_SESSION['error'] = 'Por favor complete todos los campos obligatorios';
                header('Location: index.php?ruta=registro');
                exit;
            }
            
            // Verificar si el email ya existe
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $existe = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existe['total'] > 0) {
                $_SESSION['error'] = 'Este correo electrónico ya está registrado';
                header('Location: index.php?ruta=registro');
                exit;
            }
            
            // Encriptar contraseña
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertar usuario con estado pendiente
            $stmt = $this->db->prepare("
                INSERT INTO usuarios (nombre, apellido, email, password, telefono, direccion, dui, tipo_usuario, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')
            ");
            
            $resultado = $stmt->execute([
                $nombre, 
                $apellido, 
                $email, 
                $password_hash, 
                $telefono, 
                $direccion, 
                $dui, 
                $tipo_usuario
            ]);
            
            if ($resultado) {
                $_SESSION['success'] = '✅ Registro exitoso. Tu cuenta está pendiente de aprobación por un administrador. Te notificaremos cuando puedas acceder al sistema.';
                header('Location: index.php?ruta=registro');
                exit;
            } else {
                $_SESSION['error'] = 'Error al registrar el usuario. Intenta nuevamente.';
                header('Location: index.php?ruta=registro');
                exit;
            }
            
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error del sistema: ' . $e->getMessage();
            header('Location: index.php?ruta=registro');
            exit;
        }
    }
}