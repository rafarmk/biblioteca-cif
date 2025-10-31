<?php
require_once 'config/Database.php';

class MisPrestamosController {
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

        // Obtener préstamos del usuario actual
        $query = "SELECT p.*, u.nombre as usuario_nombre, l.titulo as libro_titulo, l.isbn
                  FROM prestamos p
                  INNER JOIN usuarios u ON p.usuario_id = u.id
                  INNER JOIN libros l ON p.libro_id = l.id
                  WHERE p.usuario_id = :usuario_id
                  ORDER BY p.fecha_prestamo DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        $prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Contar estadísticas
        $total = count($prestamos);
        $activos = count(array_filter($prestamos, fn($p) => $p['estado'] === 'activo'));
        $devueltos = count(array_filter($prestamos, fn($p) => $p['estado'] === 'devuelto'));

        require_once 'views/layouts/navbar.php';
        require_once 'views/mis-prestamos/index.php';
    }
}
?>
