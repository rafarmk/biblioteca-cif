<?php
require_once __DIR__ . '/../config/Database.php';

class PrestamoController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function index() {
        try {
            // Obtener estadísticas
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM prestamos");
            $totalPrestamos = $stmt->fetch()['total'];
            
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'");
            $prestamosActivos = $stmt->fetch()['total'];
            
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo' AND fecha_devolucion_esperada < CURDATE()");
            $prestamosAtrasados = $stmt->fetch()['total'];
            
            // Obtener todos los préstamos con información de usuario y libro
            $stmt = $this->db->query("
                SELECT p.*, 
                       u.nombre as usuario_nombre,
                       u.email as usuario_email,
                       l.titulo as libro_titulo,
                       l.autor as libro_autor,
                       DATEDIFF(p.fecha_devolucion_esperada, CURDATE()) as dias_restantes
                FROM prestamos p
                LEFT JOIN usuarios u ON p.usuario_id = u.id
                LEFT JOIN libros l ON p.libro_id = l.id
                ORDER BY p.fecha_prestamo DESC
            ");
            $prestamos = $stmt->fetchAll();
            
            require_once __DIR__ . '/../views/prestamos/index.php';
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $e->getMessage()];
            $prestamos = [];
            require_once __DIR__ . '/../views/prestamos/index.php';
        }
    }
    
    public function crear() {
        try {
            // Obtener usuarios activos
            $stmt = $this->db->query("
                SELECT id, nombre, email, tipo_usuario 
                FROM usuarios 
                WHERE estado = 'activo'
                ORDER BY nombre
            ");
            $usuarios = $stmt->fetchAll();
            
            // Obtener libros disponibles (activos y con stock)
            $stmt = $this->db->query("
                SELECT id, isbn, titulo, autor, cantidad_disponible 
                FROM libros 
                WHERE activo = 1 AND cantidad_disponible > 0
                ORDER BY titulo
            ");
            $libros = $stmt->fetchAll();
            
            require_once __DIR__ . '/../views/prestamos/crear.php';
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $e->getMessage()];
            header('Location: index.php?ruta=prestamos');
            exit;
        }
    }
    
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?ruta=prestamos');
            exit;
        }
        
        try {
            $this->db->beginTransaction();
            
            $usuario_id = $_POST['usuario_id'];
            $libro_id = $_POST['libro_id'];
            $fecha_devolucion = $_POST['fecha_devolucion'];
            
            // Verificar que el libro esté disponible
            $stmt = $this->db->prepare("SELECT cantidad_disponible FROM libros WHERE id = ? FOR UPDATE");
            $stmt->execute([$libro_id]);
            $libro = $stmt->fetch();
            
            if (!$libro || $libro['cantidad_disponible'] < 1) {
                throw new Exception('El libro no está disponible');
            }
            
            // Insertar préstamo
            $stmt = $this->db->prepare("
                INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo, fecha_devolucion_esperada, estado)
                VALUES (?, ?, CURDATE(), ?, 'activo')
            ");
            $stmt->execute([$usuario_id, $libro_id, $fecha_devolucion]);
            
            // Actualizar cantidad disponible del libro
            $stmt = $this->db->prepare("
                UPDATE libros 
                SET cantidad_disponible = cantidad_disponible - 1 
                WHERE id = ?
            ");
            $stmt->execute([$libro_id]);
            
            $this->db->commit();
            
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '✅ Préstamo registrado exitosamente'];
            header('Location: index.php?ruta=prestamos');
            exit;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $e->getMessage()];
            header('Location: index.php?ruta=prestamos&accion=crear');
            exit;
        }
    }
    
    public function devolver() {
        if (!isset($_GET['id'])) {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'ID no válido'];
            header('Location: index.php?ruta=prestamos');
            exit;
        }
        
        try {
            $this->db->beginTransaction();
            
            $prestamo_id = $_GET['id'];
            
            // Obtener información del préstamo
            $stmt = $this->db->prepare("SELECT libro_id, estado FROM prestamos WHERE id = ?");
            $stmt->execute([$prestamo_id]);
            $prestamo = $stmt->fetch();
            
            if (!$prestamo) {
                throw new Exception('Préstamo no encontrado');
            }
            
            if ($prestamo['estado'] !== 'activo') {
                throw new Exception('Este préstamo ya fue devuelto');
            }
            
            // Actualizar estado del préstamo
            $stmt = $this->db->prepare("
                UPDATE prestamos 
                SET estado = 'devuelto', fecha_devolucion_real = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$prestamo_id]);
            
            // Devolver libro al inventario
            $stmt = $this->db->prepare("
                UPDATE libros 
                SET cantidad_disponible = cantidad_disponible + 1 
                WHERE id = ?
            ");
            $stmt->execute([$prestamo['libro_id']]);
            
            $this->db->commit();
            
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '✅ Devolución registrada exitosamente'];
            header('Location: index.php?ruta=prestamos');
            exit;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $e->getMessage()];
            header('Location: index.php?ruta=prestamos');
            exit;
        }
    }
    
    public function activos() {
        try {
            $stmt = $this->db->query("
                SELECT p.*, 
                       u.nombre as usuario_nombre,
                       u.email as usuario_email,
                       l.titulo as libro_titulo,
                       l.autor as libro_autor,
                       DATEDIFF(p.fecha_devolucion_esperada, CURDATE()) as dias_restantes
                FROM prestamos p
                LEFT JOIN usuarios u ON p.usuario_id = u.id
                LEFT JOIN libros l ON p.libro_id = l.id
                WHERE p.estado = 'activo'
                ORDER BY p.fecha_devolucion_esperada ASC
            ");
            $prestamos = $stmt->fetchAll();
            
            $totalPrestamos = count($prestamos);
            $prestamosActivos = count($prestamos);
            $prestamosAtrasados = 0;
            
            foreach ($prestamos as $p) {
                if ($p['dias_restantes'] < 0) {
                    $prestamosAtrasados++;
                }
            }
            
            require_once __DIR__ . '/../views/prestamos/index.php';
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $e->getMessage()];
            header('Location: index.php?ruta=prestamos');
            exit;
        }
    }
    
    public function atrasados() {
        try {
            $stmt = $this->db->query("
                SELECT p.*, 
                       u.nombre as usuario_nombre,
                       u.email as usuario_email,
                       l.titulo as libro_titulo,
                       l.autor as libro_autor,
                       DATEDIFF(CURDATE(), p.fecha_devolucion_esperada) as dias_atraso
                FROM prestamos p
                LEFT JOIN usuarios u ON p.usuario_id = u.id
                LEFT JOIN libros l ON p.libro_id = l.id
                WHERE p.estado = 'activo' AND p.fecha_devolucion_esperada < CURDATE()
                ORDER BY p.fecha_devolucion_esperada ASC
            ");
            $prestamos = $stmt->fetchAll();
            
            $totalPrestamos = count($prestamos);
            $prestamosActivos = count($prestamos);
            $prestamosAtrasados = count($prestamos);
            
            require_once __DIR__ . '/../views/prestamos/index.php';
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $e->getMessage()];
            header('Location: index.php?ruta=prestamos');
            exit;
        }
    }
    
    public function ver() {
        if (!isset($_GET['id'])) {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'ID no válido'];
            header('Location: index.php?ruta=prestamos');
            exit;
        }
        
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, 
                       u.nombre as usuario_nombre,
                       u.email as usuario_email,
                       u.tipo_usuario,
                       l.titulo as libro_titulo,
                       l.autor as libro_autor,
                       l.isbn as libro_isbn
                FROM prestamos p
                LEFT JOIN usuarios u ON p.usuario_id = u.id
                LEFT JOIN libros l ON p.libro_id = l.id
                WHERE p.id = ?
            ");
            $stmt->execute([$_GET['id']]);
            $prestamo = $stmt->fetch();
            
            if (!$prestamo) {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Préstamo no encontrado'];
                header('Location: index.php?ruta=prestamos');
                exit;
            }
            
            require_once __DIR__ . '/../views/prestamos/ver.php';
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $e->getMessage()];
            header('Location: index.php?ruta=prestamos');
            exit;
        }
    }
}