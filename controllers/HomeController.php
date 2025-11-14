<?php
require_once __DIR__ . '/../config/Database.php';

class HomeController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function index() {
        $errores = [];
        
        // Inicializar variables con valores por defecto
        $libros = [];
        $usuarios = [];
        $stats_prestamos = ['activos' => 0, 'atrasados' => 0, 'total' => 0];
        $prestamos_activos = [];
        $prestamos_atrasados = [];
        
        try {
            // Obtener todos los libros
            try {
                $stmt = $this->db->query("SELECT * FROM libros ORDER BY titulo");
                $libros = $stmt->fetchAll();
            } catch (PDOException $e) {
                $errores[] = "Error al cargar libros: " . $e->getMessage();
            }
            
            // Obtener todos los usuarios
            try {
                $stmt = $this->db->query("SELECT * FROM usuarios WHERE estado = 'activo' ORDER BY nombre");
                $usuarios = $stmt->fetchAll();
            } catch (PDOException $e) {
                $errores[] = "Error al cargar usuarios: " . $e->getMessage();
            }
            
            // Estadísticas de préstamos
            try {
                $stmt = $this->db->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'");
                $result = $stmt->fetch();
                $stats_prestamos['activos'] = $result ? $result['total'] : 0;
            } catch (PDOException $e) {
                $errores[] = "Error al contar préstamos activos: " . $e->getMessage();
            }
            
            try {
                $stmt = $this->db->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo' AND fecha_devolucion_esperada < CURDATE()");
                $result = $stmt->fetch();
                $stats_prestamos['atrasados'] = $result ? $result['total'] : 0;
            } catch (PDOException $e) {
                $errores[] = "Error al contar préstamos atrasados: " . $e->getMessage();
            }
            
            try {
                $stmt = $this->db->query("SELECT COUNT(*) as total FROM prestamos");
                $result = $stmt->fetch();
                $stats_prestamos['total'] = $result ? $result['total'] : 0;
            } catch (PDOException $e) {
                $errores[] = "Error al contar total de préstamos: " . $e->getMessage();
            }
            
            // Préstamos activos recientes
            try {
                $stmt = $this->db->query("
                    SELECT 
                        p.*,
                        l.titulo as libro_titulo,
                        u.nombre as usuario_nombre
                    FROM prestamos p
                    INNER JOIN libros l ON p.libro_id = l.id
                    INNER JOIN usuarios u ON p.usuario_id = u.id
                    WHERE p.estado = 'activo'
                    ORDER BY p.fecha_prestamo DESC
                    LIMIT 10
                ");
                $prestamos_activos = $stmt->fetchAll();
            } catch (PDOException $e) {
                $errores[] = "Error al cargar préstamos activos: " . $e->getMessage();
            }
            
            // Préstamos atrasados
            try {
                $stmt = $this->db->query("
                    SELECT 
                        p.*,
                        l.titulo as libro_titulo,
                        u.nombre as usuario_nombre,
                        p.fecha_devolucion_esperada as fecha_devolucion_estimada
                    FROM prestamos p
                    INNER JOIN libros l ON p.libro_id = l.id
                    INNER JOIN usuarios u ON p.usuario_id = u.id
                    WHERE p.estado = 'activo'
                    AND p.fecha_devolucion_esperada < CURDATE()
                    ORDER BY p.fecha_devolucion_esperada ASC
                    LIMIT 10
                ");
                $prestamos_atrasados = $stmt->fetchAll();
            } catch (PDOException $e) {
                $errores[] = "Error al cargar préstamos atrasados: " . $e->getMessage();
            }
            
        } catch (PDOException $e) {
            $errores[] = "Error general: " . $e->getMessage();
        }
        
        // Cargar la vista (SIEMPRE, incluso si hay errores)
        require_once __DIR__ . '/../views/home.php';
    }
}