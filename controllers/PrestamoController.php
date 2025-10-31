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

    // Listar todos los préstamos
    public function index() {
        $stmt = $this->prestamo->leer();
        $prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/prestamos/index.php';
    }

    // Mostrar formulario de crear
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("=== CREAR PRÉSTAMO ===");
            error_log("Libro ID: " . $_POST['libro_id']);
            error_log("Usuario ID: " . $_POST['usuario_id']);
            error_log("Fecha devolución: " . $_POST['fecha_devolucion_esperada']);

            $this->prestamo->libro_id = $_POST['libro_id'];
            $this->prestamo->usuario_id = $_POST['usuario_id'];
            $this->prestamo->fecha_prestamo = date('Y-m-d');
            $this->prestamo->fecha_devolucion_esperada = $_POST['fecha_devolucion_esperada'];
            $this->prestamo->estado = 'activo';

            if ($this->prestamo->crear()) {
                error_log("Préstamo creado exitosamente");
                header("Location: index.php?ruta=prestamos&mensaje=Préstamo creado exitosamente");
                exit();
            } else {
                error_log("ERROR: No se pudo crear el préstamo");
                $_SESSION['error'] = "Error al crear el préstamo. Verifique que el libro esté disponible.";
                header("Location: index.php?ruta=prestamos&accion=crear");
                exit();
            }
        }

        // Obtener libros y usuarios para el formulario
        $libroModel = new Libro($this->db);
        $usuarioModel = new Usuario($this->db);

        $libros = $libroModel->leer()->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = $usuarioModel->leer()->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/prestamos/crear.php';
    }

    // Solicitar préstamo (para usuarios comunes)
    public function solicitar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libro_id = $_POST['libro_id'] ?? null;
            
            if (!$libro_id) {
                $_SESSION['error'] = "Libro no especificado";
                header("Location: index.php?ruta=catalogo");
                exit();
            }

            // El usuario es el que está logueado
            $usuario_id = $_SESSION['usuario_id'] ?? null;
            
            if (!$usuario_id) {
                $_SESSION['error'] = "Debe iniciar sesión";
                header("Location: index.php?ruta=login");
                exit();
            }

            // Calcular fecha de devolución (7 días por defecto)
            $fecha_devolucion = date('Y-m-d', strtotime('+7 days'));

            $this->prestamo->libro_id = $libro_id;
            $this->prestamo->usuario_id = $usuario_id;
            $this->prestamo->fecha_prestamo = date('Y-m-d');
            $this->prestamo->fecha_devolucion_esperada = $fecha_devolucion;
            $this->prestamo->estado = 'activo';

            if ($this->prestamo->crear()) {
                $_SESSION['mensaje'] = "¡Préstamo solicitado exitosamente! Fecha de devolución: " . date('d/m/Y', strtotime($fecha_devolucion));
                header("Location: index.php?ruta=mis-prestamos");
                exit();
            } else {
                $_SESSION['error'] = "Error al solicitar el préstamo. El libro podría no estar disponible.";
                header("Location: index.php?ruta=catalogo");
                exit();
            }
        } else {
            header("Location: index.php?ruta=catalogo");
            exit();
        }
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

    // Eliminar préstamo
    public function eliminar() {
        if (isset($_GET['id'])) {
            $this->prestamo->id = $_GET['id'];

            if ($this->prestamo->eliminar()) {
                header("Location: index.php?ruta=prestamos&mensaje=Préstamo eliminado exitosamente");
                exit();
            } else {
                header("Location: index.php?ruta=prestamos&error=Error al eliminar el préstamo");
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

    // Ver préstamos activos
    public function activos() {
        $query = "SELECT p.*, l.titulo as libro_titulo, u.nombre as usuario_nombre
                  FROM prestamos p
                  LEFT JOIN libros l ON p.libro_id = l.id
                  LEFT JOIN usuarios u ON p.usuario_id = u.id
                  WHERE p.estado = 'activo'
                  ORDER BY p.fecha_prestamo DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/prestamos/activos.php';
    }

    // Ver préstamos atrasados
    public function atrasados() {
        $query = "SELECT p.*, l.titulo as libro_titulo, u.nombre as usuario_nombre,
                         DATEDIFF(CURDATE(), p.fecha_devolucion_esperada) as dias_atraso
                  FROM prestamos p
                  LEFT JOIN libros l ON p.libro_id = l.id
                  LEFT JOIN usuarios u ON p.usuario_id = u.id
                  WHERE p.estado = 'activo'
                  AND p.fecha_devolucion_esperada < CURDATE()
                  ORDER BY p.fecha_devolucion_esperada ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/prestamos/atrasados.php';
    }
}
?>