<?php
require_once 'models/Prestamo.php';
require_once 'models/Libro.php';
require_once 'models/Usuario.php';

class PrestamoController {
    private $db;
    private $prestamo;
    
    public function __construct($db) {
        $this->db = $db;
        $this->prestamo = new Prestamo($db);
    }
    
    // Listar todos los pr�stamos
    public function index() {
        $stmt = $this->prestamo->leer();
        $prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/prestamos/index.php';
    }
    
    // Mostrar formulario de crear
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prestamo->libro_id = $_POST['libro_id'];
            $this->prestamo->usuario_id = $_POST['usuario_id'];
            $this->prestamo->fecha_prestamo = date('Y-m-d');
            $this->prestamo->fecha_devolucion_esperada = $_POST['fecha_devolucion_esperada'];
            $this->prestamo->estado = 'activo';
            
            if ($this->prestamo->crear()) {
                header("Location: index.php?ruta=prestamos&mensaje=Pr�stamo creado exitosamente");
                exit();
            } else {
                $error = "Error al crear el pr�stamo";
            }
        }
        
        // Obtener libros y usuarios para el formulario
        $libroModel = new Libro($this->db);
        $usuarioModel = new Usuario($this->db);
        
        $libros = $libroModel->leer()->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = $usuarioModel->leer()->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/prestamos/crear.php';
    }
    
    // Devolver libro
    public function devolver() {
        if (isset($_GET['id'])) {
            $this->prestamo->id = $_GET['id'];
            
            if ($this->prestamo->devolver()) {
                header("Location: index.php?ruta=prestamos&mensaje=Libro devuelto exitosamente");
                exit();
            } else {
                header("Location: index.php?ruta=prestamos&error=Error al devolver el libro");
                exit();
            }
        }
    }
    
    // Eliminar pr�stamo
    public function eliminar() {
        if (isset($_GET['id'])) {
            $this->prestamo->id = $_GET['id'];
            
            if ($this->prestamo->eliminar()) {
                header("Location: index.php?ruta=prestamos&mensaje=Pr�stamo eliminado exitosamente");
                exit();
            } else {
                header("Location: index.php?ruta=prestamos&error=Error al eliminar el pr�stamo");
                exit();
            }
        }
    }
    
    // Ver detalles de un préstamo
    public function ver() {
        if (isset($_GET['id'])) {
            $this->prestamo->id = $_GET['id'];
            
            // Obtener datos del préstamo con JOIN
            $query = "SELECT p.*, 
                             l.titulo as libro_titulo, 
                             l.autor as libro_autor,
                             u.nombre as usuario_nombre,
                             u.email as usuario_email,
                             u.telefono as usuario_telefono
                      FROM prestamos p
                      INNER JOIN libros l ON p.libro_id = l.id
                      INNER JOIN usuarios u ON p.usuario_id = u.id
                      WHERE p.id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $this->prestamo->id);
            $stmt->execute();
            
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($datos) {
                require_once 'views/prestamos/ver.php';
            } else {
                header("Location: index.php?ruta=prestamos&error=Préstamo no encontrado");
                exit();
            }
        } else {
            header("Location: index.php?ruta=prestamos");
            exit();
        }
    }
}
?>
