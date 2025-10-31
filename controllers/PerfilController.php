<?php
require_once 'config/Database.php';

class PerfilController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $usuario_id = $_SESSION['usuario_id'] ?? null;
        
        if (!$usuario_id) {
            header('Location: index.php?ruta=login');
            exit();
        }

        // Obtener datos del usuario
        $query = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $usuario_id);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $_SESSION['error'] = "Usuario no encontrado";
            header('Location: index.php?ruta=login');
            exit();
        }

        require_once 'views/layouts/navbar.php';
        require_once 'views/perfil/index.php';
    }

    public function editar() {
        $usuario_id = $_SESSION['usuario_id'] ?? null;
        
        if (!$usuario_id) {
            header('Location: index.php?ruta=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($nombre) || empty($email)) {
                $_SESSION['error'] = "Nombre y email son obligatorios";
                header("Location: index.php?ruta=perfil&accion=editar");
                exit();
            }

            // Actualizar datos (con o sin contraseña)
            if (!empty($password)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE usuarios 
                          SET nombre = :nombre, email = :email, telefono = :telefono, 
                              direccion = :direccion, password = :password
                          WHERE id = :id";
                $params = [
                    ':nombre' => $nombre,
                    ':email' => $email,
                    ':telefono' => $telefono,
                    ':direccion' => $direccion,
                    ':password' => $password_hash,
                    ':id' => $usuario_id
                ];
            } else {
                $query = "UPDATE usuarios 
                          SET nombre = :nombre, email = :email, telefono = :telefono, 
                              direccion = :direccion
                          WHERE id = :id";
                $params = [
                    ':nombre' => $nombre,
                    ':email' => $email,
                    ':telefono' => $telefono,
                    ':direccion' => $direccion,
                    ':id' => $usuario_id
                ];
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);

            // Actualizar sesión
            $_SESSION['usuario_nombre'] = $nombre;
            $_SESSION['usuario_email'] = $email;

            $_SESSION['mensaje'] = "Perfil actualizado exitosamente";
            header("Location: index.php?ruta=perfil");
            exit();
        }

        // Obtener datos actuales
        $query = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $usuario_id);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        require_once 'views/layouts/navbar.php';
        require_once 'views/perfil/editar.php';
    }
}
?>
