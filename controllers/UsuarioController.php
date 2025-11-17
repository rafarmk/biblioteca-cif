<?php
require_once __DIR__ . '/../config/Database.php';

class UsuarioController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function index() {
        require_once __DIR__ . '/../views/usuario/index.php';
    }
    
    public function crear() {
        require_once __DIR__ . '/../views/usuario/crear.php';
    }
    
    public function store() {
        try {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $telefono = $_POST['telefono'] ?? null;
            $direccion = $_POST['direccion'] ?? null;
            $dui = $_POST['dui'] ?? null;
            $tipo_usuario = $_POST['tipo_usuario'];
            
            $stmt = $this->db->prepare("
                INSERT INTO usuarios (nombre, apellido, email, password, telefono, direccion, dui, tipo_usuario, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'activo')
            ");
            $stmt->execute([$nombre, $apellido, $email, $password, $telefono, $direccion, $dui, $tipo_usuario]);
            
            $_SESSION['mensaje'] = '✅ Usuario creado exitosamente';
            
        } catch (PDOException $e) {
            $_SESSION['error'] = '❌ Error: ' . $e->getMessage();
        }
        
        header('Location: index.php?ruta=usuarios');
        exit;
    }
    
    public function editar() {
        require_once __DIR__ . '/../views/usuario/editar.php';
    }
    
    public function update() {
        try {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'] ?? null;
            $direccion = $_POST['direccion'] ?? null;
            $dui = $_POST['dui'] ?? null;
            $tipo_usuario = $_POST['tipo_usuario'];
            $estado = $_POST['estado'];
            
            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("
                    UPDATE usuarios 
                    SET nombre = ?, apellido = ?, email = ?, password = ?, telefono = ?, 
                        direccion = ?, dui = ?, tipo_usuario = ?, estado = ? 
                    WHERE id = ?
                ");
                $stmt->execute([$nombre, $apellido, $email, $password, $telefono, $direccion, $dui, $tipo_usuario, $estado, $id]);
            } else {
                $stmt = $this->db->prepare("
                    UPDATE usuarios 
                    SET nombre = ?, apellido = ?, email = ?, telefono = ?, 
                        direccion = ?, dui = ?, tipo_usuario = ?, estado = ? 
                    WHERE id = ?
                ");
                $stmt->execute([$nombre, $apellido, $email, $telefono, $direccion, $dui, $tipo_usuario, $estado, $id]);
            }
            
            $_SESSION['mensaje'] = '✅ Usuario actualizado exitosamente';
            
        } catch (PDOException $e) {
            $_SESSION['error'] = '❌ Error: ' . $e->getMessage();
        }
        
        header('Location: index.php?ruta=usuarios');
        exit;
    }
    
    public function eliminar() {
        try {
            $id = $_GET['id'];
            
            $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            
            $_SESSION['mensaje'] = '✅ Usuario eliminado exitosamente';
            
        } catch (PDOException $e) {
            $_SESSION['error'] = '❌ Error: ' . $e->getMessage();
        }
        
        header('Location: index.php?ruta=usuarios');
        exit;
    }
}