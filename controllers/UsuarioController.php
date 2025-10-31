<?php
require_once 'config/Database.php';

class UsuarioController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        // Obtener usuarios pendientes primero
        $queryPendientes = "SELECT * FROM usuarios WHERE estado = 'pendiente' ORDER BY fecha_registro DESC";
        $stmtPendientes = $this->db->prepare($queryPendientes);
        $stmtPendientes->execute();
        $usuariosPendientes = $stmtPendientes->fetchAll(PDO::FETCH_ASSOC);

        // Obtener todos los usuarios
        $query = "SELECT * FROM usuarios ORDER BY fecha_registro DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/layouts/navbar.php';
        require_once 'views/usuarios/index.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $tipo_usuario = $_POST['tipo_usuario'] ?? 'estudiante_mayor';
            $dui = $_POST['dui'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($nombre) || empty($email)) {
                $_SESSION['error'] = "Nombre y email son obligatorios";
                header("Location: index.php?ruta=usuarios");
                exit();
            }

            // Encriptar contraseña si se proporciona
            $password_hash = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

            $query = "INSERT INTO usuarios 
                      (nombre, email, telefono, direccion, tipo_usuario, dui, password, estado, puede_prestar, dias_max_prestamo, max_libros_simultaneos)
                      VALUES 
                      (:nombre, :email, :telefono, :direccion, :tipo_usuario, :dui, :password, 'activo', 1, 7, 3)";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':telefono' => $telefono,
                ':direccion' => $direccion,
                ':tipo_usuario' => $tipo_usuario,
                ':dui' => $dui,
                ':password' => $password_hash
            ]);

            $_SESSION['mensaje'] = "Usuario creado exitosamente";
            header("Location: index.php?ruta=usuarios");
            exit();
        }

        require_once 'views/layouts/navbar.php';
        require_once 'views/usuarios/crear.php';
    }

    public function editar() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "ID de usuario no válido";
            header("Location: index.php?ruta=usuarios");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $tipo_usuario = $_POST['tipo_usuario'] ?? 'estudiante_mayor';
            $estado = $_POST['estado'] ?? 'activo';
            $dui = $_POST['dui'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($nombre) || empty($email)) {
                $_SESSION['error'] = "Nombre y email son obligatorios";
                header("Location: index.php?ruta=usuarios&accion=editar&id=$id");
                exit();
            }

            // Actualizar contraseña solo si se proporciona una nueva
            if (!empty($password)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE usuarios 
                          SET nombre = :nombre, email = :email, telefono = :telefono, 
                              direccion = :direccion, tipo_usuario = :tipo_usuario, 
                              estado = :estado, dui = :dui, password = :password
                          WHERE id = :id";
                $params = [
                    ':nombre' => $nombre,
                    ':email' => $email,
                    ':telefono' => $telefono,
                    ':direccion' => $direccion,
                    ':tipo_usuario' => $tipo_usuario,
                    ':estado' => $estado,
                    ':dui' => $dui,
                    ':password' => $password_hash,
                    ':id' => $id
                ];
            } else {
                $query = "UPDATE usuarios 
                          SET nombre = :nombre, email = :email, telefono = :telefono, 
                              direccion = :direccion, tipo_usuario = :tipo_usuario, 
                              estado = :estado, dui = :dui
                          WHERE id = :id";
                $params = [
                    ':nombre' => $nombre,
                    ':email' => $email,
                    ':telefono' => $telefono,
                    ':direccion' => $direccion,
                    ':tipo_usuario' => $tipo_usuario,
                    ':estado' => $estado,
                    ':dui' => $dui,
                    ':id' => $id
                ];
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);

            $_SESSION['mensaje'] = "Usuario actualizado exitosamente";
            header("Location: index.php?ruta=usuarios");
            exit();
        }

        $query = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $_SESSION['error'] = "Usuario no encontrado";
            header("Location: index.php?ruta=usuarios");
            exit();
        }

        require_once 'views/layouts/navbar.php';
        require_once 'views/usuarios/editar.php';
    }

    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error'] = "ID de usuario no válido";
                header("Location: index.php?ruta=usuarios");
                exit();
            }

            $query = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $_SESSION['mensaje'] = "Usuario eliminado exitosamente";
            header("Location: index.php?ruta=usuarios");
            exit();
        }
    }

    public function aprobar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error'] = "ID de usuario no válido";
                header("Location: index.php?ruta=usuarios");
                exit();
            }

            // Cambiar estado a activo y habilitar préstamos
            $query = "UPDATE usuarios 
                      SET estado = 'activo', puede_prestar = 1
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $_SESSION['mensaje'] = "Usuario aprobado exitosamente";
            header("Location: index.php?ruta=usuarios");
            exit();
        }
    }

    public function rechazar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $_SESSION['error'] = "ID de usuario no válido";
                header("Location: index.php?ruta=usuarios");
                exit();
            }

            // Eliminar el usuario rechazado
            $query = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $_SESSION['mensaje'] = "Usuario rechazado y eliminado";
            header("Location: index.php?ruta=usuarios");
            exit();
        }
    }

    public function contarPendientes() {
        $query = "SELECT COUNT(*) as total FROM usuarios WHERE estado = 'pendiente'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>