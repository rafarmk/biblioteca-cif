<?php
require_once 'config/Database.php';

class SolicitudController {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Mostrar todas las solicitudes (usuarios pendientes)
     */
    public function index() {
        require_once 'views/solicitudes/index.php';
    }

    /**
     * Aprobar un usuario pendiente
     */
    public function aprobar() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "ID de usuario no válido";
            header('Location: index.php?ruta=solicitudes');
            exit();
        }

        try {
            // Actualizar estado del usuario a 'activo'
            $query = "UPDATE usuarios SET estado = 'activo' WHERE id = :id AND estado = 'pendiente'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $_SESSION['mensaje'] = "Usuario aprobado exitosamente";
            } else {
                $_SESSION['error'] = "No se pudo aprobar el usuario";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al aprobar usuario: " . $e->getMessage();
        }

        header('Location: index.php?ruta=solicitudes');
        exit();
    }

    /**
     * Rechazar un usuario pendiente
     */
    public function rechazar() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "ID de usuario no válido";
            header('Location: index.php?ruta=solicitudes');
            exit();
        }

        try {
            // Actualizar estado del usuario a 'inactivo'
            $query = "UPDATE usuarios SET estado = 'inactivo' WHERE id = :id AND estado = 'pendiente'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $_SESSION['mensaje'] = "Solicitud rechazada correctamente";
            } else {
                $_SESSION['error'] = "No se pudo rechazar la solicitud";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al rechazar solicitud: " . $e->getMessage();
        }

        header('Location: index.php?ruta=solicitudes');
        exit();
    }
}