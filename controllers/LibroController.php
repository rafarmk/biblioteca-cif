<?php
require_once 'models/Libro.php';

class LibroController {
    private $db;
    private $libro;

    public function __construct($db) {
        $this->db = $db;
        $this->libro = new Libro($db);
    }

    // Listar todos los libros
    public function index() {
        $stmt = $this->libro->leer();
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/libros/index.php';
    }

    // Mostrar formulario de crear
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->libro->titulo = $_POST['titulo'];
            $this->libro->autor = $_POST['autor'];
            $this->libro->isbn = $_POST['isbn'] ?? '';
            $this->libro->editorial = $_POST['editorial'] ?? '';
            $this->libro->anio_publicacion = !empty($_POST['anio_publicacion']) ? $_POST['anio_publicacion'] : null;
            $this->libro->categoria = $_POST['categoria'] ?? '';
            $this->libro->ubicacion = $_POST['ubicacion'] ?? '';
            $this->libro->cantidad_total = $_POST['cantidad_total'] ?? 1;
            $this->libro->cantidad_disponible = $_POST['cantidad_total'] ?? 1;
            $this->libro->descripcion = $_POST['descripcion'] ?? '';
            $this->libro->estado = 'disponible';

            if ($this->libro->crear()) {
                header("Location: index.php?ruta=libros&mensaje=Libro creado exitosamente");
                exit();
            } else {
                $error = "Error al crear el libro";
            }
        }
        require_once 'views/libros/crear.php';
    }

    // Mostrar formulario de editar
    public function editar() {
        // Si es POST, actualizar
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->libro->id = $_GET['id']; // CAMBIO: Tomar ID de GET
            $this->libro->titulo = $_POST['titulo'];
            $this->libro->autor = $_POST['autor'];
            $this->libro->isbn = $_POST['isbn'] ?? '';
            $this->libro->editorial = $_POST['editorial'] ?? '';
            $this->libro->anio_publicacion = !empty($_POST['anio_publicacion']) ? $_POST['anio_publicacion'] : null;
            $this->libro->categoria = $_POST['categoria'] ?? '';
            $this->libro->ubicacion = $_POST['ubicacion'] ?? '';
            $this->libro->cantidad_total = $_POST['cantidad_total'] ?? 1;
            $this->libro->descripcion = $_POST['descripcion'] ?? '';
            $this->libro->estado = $_POST['estado'] ?? 'disponible';

            if ($this->libro->actualizar()) {
                header("Location: index.php?ruta=libros&mensaje=Libro actualizado exitosamente");
                exit();
            } else {
                $_SESSION['error'] = "Error al actualizar el libro";
                header("Location: index.php?ruta=libros&accion=editar&id=" . $_GET['id']);
                exit();
            }
        }

        // Si es GET, mostrar formulario
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "ID de libro no especificado";
            header("Location: index.php?ruta=libros");
            exit();
        }

        $this->libro->id = $_GET['id'];

        if (!$this->libro->leerUno()) {
            $_SESSION['error'] = "Libro no encontrado";
            header("Location: index.php?ruta=libros");
            exit();
        }

        // Crear array con los datos
        $libro = [
            'id' => $this->libro->id,
            'titulo' => $this->libro->titulo,
            'autor' => $this->libro->autor,
            'isbn' => $this->libro->isbn,
            'editorial' => $this->libro->editorial,
            'anio_publicacion' => $this->libro->anio_publicacion,
            'categoria' => $this->libro->categoria,
            'ubicacion' => $this->libro->ubicacion,
            'cantidad_total' => $this->libro->cantidad_total,
            'cantidad_disponible' => $this->libro->cantidad_disponible,
            'descripcion' => $this->libro->descripcion,
            'estado' => $this->libro->estado
        ];

        require_once 'views/libros/editar.php';
    }

    // Eliminar libro
    public function eliminar() {
        if (isset($_GET['id'])) {
            $this->libro->id = $_GET['id'];

            if ($this->libro->eliminar()) {
                header("Location: index.php?ruta=libros&mensaje=Libro eliminado exitosamente");
                exit();
            } else {
                header("Location: index.php?ruta=libros&error=Error al eliminar el libro");
                exit();
            }
        }
    }
}
?>
