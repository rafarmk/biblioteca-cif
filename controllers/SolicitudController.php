<?php
require_once __DIR__ . '/../config/Database.php';

class SolicitudController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function index() {
        // Obtener usuarios pendientes
        $stmt = $this->db->prepare("
            SELECT id, nombre, apellido, email, tipo_usuario, fecha_registro 
            FROM usuarios 
            WHERE estado = 'pendiente' 
            ORDER BY fecha_registro DESC
        ");
        $stmt->execute();
        $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/solicitudes/index.php';
    }
    
    public function aprobar() {
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            try {
                $stmt = $this->db->prepare("UPDATE usuarios SET estado = 'activo' WHERE id = ?");
                $stmt->execute([$id]);
                
                $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '✅ Usuario aprobado exitosamente'];
            } catch (PDOException $e) {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $e->getMessage()];
            }
        }
        
        header('Location: index.php?ruta=solicitudes');
        exit;
    }
    
    public function rechazar() {
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            try {
                $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
                $stmt->execute([$id]);
                
                $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '✅ Solicitud rechazada'];
            } catch (PDOException $e) {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $e->getMessage()];
            }
        }
        
        header('Location: index.php?ruta=solicitudes');
        exit;
    }
    
    public function contarPendientes() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM usuarios WHERE estado = 'pendiente'");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }
}