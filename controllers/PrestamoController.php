<?php
require_once __DIR__ . '/../config/Database.php';

class PrestamoController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function index() {
        require_once __DIR__ . '/../views/prestamos/index.php';
    }
    
    public function crear() {
        require_once __DIR__ . '/../views/prestamos/crear.php';
    }
    
    public function store() {
        try {
            $libro_id = $_POST['libro_id'];
            $usuario_id = $_POST['usuario_id'];
            $fecha_prestamo = $_POST['fecha_prestamo'];
            $fecha_devolucion = $_POST['fecha_devolucion'];
            
            // Verificar disponibilidad
            $stmt = $this->db->prepare("SELECT cantidad_disponible FROM libros WHERE id = ?");
            $stmt->execute([$libro_id]);
            $libro = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($libro['cantidad_disponible'] <= 0) {
                $_SESSION['mensaje'] = [
                    'tipo' => 'danger',
                    'texto' => '❌ No hay copias disponibles de este libro'
                ];
                header('Location: index.php?ruta=prestamos&accion=crear');
                exit;
            }
            
            // Crear préstamo
            $stmt = $this->db->prepare("
                INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, fecha_devolucion, estado) 
                VALUES (?, ?, ?, ?, 'activo')
            ");
            $stmt->execute([$libro_id, $usuario_id, $fecha_prestamo, $fecha_devolucion]);
            
            // Actualizar disponibilidad
            $stmt = $this->db->prepare("
                UPDATE libros 
                SET cantidad_disponible = cantidad_disponible - 1 
                WHERE id = ?
            ");
            $stmt->execute([$libro_id]);
            
            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => '✅ Préstamo registrado exitosamente'
            ];
            
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = [
                'tipo' => 'danger',
                'texto' => '❌ Error: ' . $e->getMessage()
            ];
        }
        
        header('Location: index.php?ruta=prestamos');
        exit;
    }
    
    public function solicitar() {
        try {
            $libro_id = $_POST['libro_id'];
            $usuario_id = $_SESSION['usuario_id'];
            
            // Verificar disponibilidad
            $stmt = $this->db->prepare("SELECT cantidad_disponible, titulo FROM libros WHERE id = ?");
            $stmt->execute([$libro_id]);
            $libro = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($libro['cantidad_disponible'] <= 0) {
                $_SESSION['mensaje'] = [
                    'tipo' => 'danger',
                    'texto' => '❌ No hay copias disponibles de este libro en este momento'
                ];
                header('Location: index.php?ruta=catalogo');
                exit;
            }
            
            // Verificar que no tenga préstamo activo del mismo libro
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total 
                FROM prestamos 
                WHERE usuario_id = ? AND libro_id = ? AND estado = 'activo'
            ");
            $stmt->execute([$usuario_id, $libro_id]);
            $existe = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existe['total'] > 0) {
                $_SESSION['mensaje'] = [
                    'tipo' => 'danger',
                    'texto' => '❌ Ya tienes un préstamo activo de este libro'
                ];
                header('Location: index.php?ruta=catalogo');
                exit;
            }
            
            // Crear préstamo (fecha de devolución: 15 días)
            $fecha_prestamo = date('Y-m-d');
            $fecha_devolucion = date('Y-m-d', strtotime('+15 days'));
            
            $stmt = $this->db->prepare("
                INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, fecha_devolucion, estado) 
                VALUES (?, ?, ?, ?, 'activo')
            ");
            $stmt->execute([$libro_id, $usuario_id, $fecha_prestamo, $fecha_devolucion]);
            
            // Actualizar disponibilidad
            $stmt = $this->db->prepare("
                UPDATE libros 
                SET cantidad_disponible = cantidad_disponible - 1 
                WHERE id = ?
            ");
            $stmt->execute([$libro_id]);
            
            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => '✅ ¡Préstamo solicitado exitosamente! Tienes 15 días para devolverlo.'
            ];
            
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = [
                'tipo' => 'danger',
                'texto' => '❌ Error al procesar la solicitud: ' . $e->getMessage()
            ];
        }
        
        header('Location: index.php?ruta=mis_prestamos');
        exit;
    }
    
    public function devolver() {
        try {
            $id = $_GET['id'];
            
            // Obtener libro_id antes de marcar como devuelto
            $stmt = $this->db->prepare("SELECT libro_id FROM prestamos WHERE id = ?");
            $stmt->execute([$id]);
            $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$prestamo) {
                throw new Exception("Préstamo no encontrado");
            }
            
            // Marcar como devuelto
            $stmt = $this->db->prepare("
                UPDATE prestamos 
                SET estado = 'devuelto', fecha_devolucion_real = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$id]);
            
            // Aumentar disponibilidad
            $stmt = $this->db->prepare("
                UPDATE libros 
                SET cantidad_disponible = cantidad_disponible + 1 
                WHERE id = ?
            ");
            $stmt->execute([$prestamo['libro_id']]);
            
            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => '✅ Libro devuelto exitosamente'
            ];
            
        } catch (Exception $e) {
            $_SESSION['mensaje'] = [
                'tipo' => 'danger',
                'texto' => '❌ Error: ' . $e->getMessage()
            ];
        }
        
        header('Location: index.php?ruta=prestamos');
        exit;
    }
    
    public function ver() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?ruta=prestamos');
            exit;
        }
        require_once __DIR__ . '/../views/prestamos/ver.php';
    }
}