<?php
/**
 * Controlador: LibroController
 *
 * Descripción: Gestiona todas las operaciones relacionadas con libros
 * Autor: José Raphael Ernesto Pérez Hernández
 * Fecha: 13 de Octubre, 2025
 */

// Cargar dependencias
require_once 'config/conexion.php';
require_once 'models/Libro.php';

class LibroController {
    private $conexion;
    private $modelo;

    /**
     * Constructor
     * Inicializa la conexión y el modelo
     */
    public function __construct() {
        $conexionObj = new Conexion();
        $this->conexion = $conexionObj->conectar();
        $this->modelo = new Libro($this->conexion);
    }

    /**
     * Mostrar lista de libros
     * Ruta: /index.php?ruta=libros
     */
    public function index() {
        $libros = $this->modelo->obtenerTodos();
        $total_libros = $this->modelo->contarTotal();
        $disponibles = $this->modelo->contarDisponibles();
        require_once 'views/libros/index.php';
    }

    /**
     * Mostrar formulario de nuevo libro
     * Ruta: /index.php?ruta=libros/crear
     */
    public function crear() {
        require_once 'views/libros/crear.php';
    }

    /**
     * Guardar nuevo libro
     * Ruta: /index.php?ruta=libros/guardar (POST)
     */
    public function guardar() {
        // Verificar que sea POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?ruta=libros');
            exit;
        }

        // ==========================================
        // VALIDAR CAMPOS OBLIGATORIOS
        // ==========================================
        $errores = [];

        if (empty($_POST['titulo'])) {
            $errores[] = "El título es obligatorio";
        }

        if (empty($_POST['autor'])) {
            $errores[] = "El autor es obligatorio";
        }

        // Validar ISBN si se proporciona
        if (!empty($_POST['isbn'])) {
            if ($this->modelo->existeISBN($_POST['isbn'])) {
                $errores[] = "Ya existe un libro con ese ISBN";
            }
        }

        // Validar año
        if (!empty($_POST['anio_publicacion'])) {
            $anio = intval($_POST['anio_publicacion']);
            if ($anio < 1000 || $anio > date('Y')) {
                $errores[] = "El año no es válido";
            }
        }

        // Si hay errores, regresar al formulario
        if (!empty($errores)) {
            $_SESSION['error'] = implode('. ', $errores);
            $_SESSION['datos_form'] = $_POST;
            header('Location: index.php?ruta=libros/crear');
            exit;
        }

        // ==========================================
        // PREPARAR DATOS LIMPIOS
        // ==========================================
        $datos = [
            'isbn' => !empty($_POST['isbn']) ? trim($_POST['isbn']) : null,
            'titulo' => trim($_POST['titulo']),
            'subtitulo' => !empty($_POST['subtitulo']) ? trim($_POST['subtitulo']) : null,
            'autor' => trim($_POST['autor']),
            'editorial' => !empty($_POST['editorial']) ? trim($_POST['editorial']) : null,
            'anio_publicacion' => !empty($_POST['anio_publicacion']) ? intval($_POST['anio_publicacion']) : null,
            'categoria_id' => !empty($_POST['categoria_id']) ? intval($_POST['categoria_id']) : null,
            'idioma' => !empty($_POST['idioma']) ? trim($_POST['idioma']) : 'Español',
            'paginas' => !empty($_POST['paginas']) ? intval($_POST['paginas']) : null,
            'descripcion' => !empty($_POST['descripcion']) ? trim($_POST['descripcion']) : null,
            'tipo' => !empty($_POST['tipo']) ? $_POST['tipo'] : 'fisico',
            'total_copias' => !empty($_POST['total_copias']) ? intval($_POST['total_copias']) : 1
        ];

        // ==========================================
        // GUARDAR EN BASE DE DATOS
        // ==========================================
        if ($this->modelo->crear($datos)) {
            $_SESSION['exito'] = "✅ Libro '{$datos['titulo']}' registrado exitosamente";
        } else {
            $_SESSION['error'] = "❌ Error al registrar el libro. Intente nuevamente.";
        }

        // Redirigir a lista
        header('Location: index.php?ruta=libros');
        exit;
    }

    /**
     * Mostrar formulario de edición
     * Ruta: /index.php?ruta=libros/editar&id=X
     */
    public function editar($id) {
        $libro = $this->modelo->obtenerPorId($id);

        if (!$libro) {
            $_SESSION['error'] = "Libro no encontrado";
            header('Location: index.php?ruta=libros');
            exit;
        }

        require_once 'views/libros/editar.php';
    }

    /**
     * Actualizar libro existente
     * Ruta: /index.php?ruta=libros/actualizar&id=X (POST)
     */
    public function actualizar($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?ruta=libros');
            exit;
        }

        $libro = $this->modelo->obtenerPorId($id);
        if (!$libro) {
            $_SESSION['error'] = "Libro no encontrado";
            header('Location: index.php?ruta=libros');
            exit;
        }

        if (empty($_POST['titulo']) || empty($_POST['autor'])) {
            $_SESSION['error'] = "Título y autor son obligatorios";
            header('Location: index.php?ruta=libros/editar&id=' . $id);
            exit;
        }

        $datos = [
            'isbn' => !empty($_POST['isbn']) ? trim($_POST['isbn']) : null,
            'titulo' => trim($_POST['titulo']),
            'subtitulo' => !empty($_POST['subtitulo']) ? trim($_POST['subtitulo']) : null,
            'autor' => trim($_POST['autor']),
            'editorial' => !empty($_POST['editorial']) ? trim($_POST['editorial']) : null,
            'anio_publicacion' => !empty($_POST['anio_publicacion']) ? intval($_POST['anio_publicacion']) : null,
            'categoria_id' => !empty($_POST['categoria_id']) ? intval($_POST['categoria_id']) : null,
            'idioma' => !empty($_POST['idioma']) ? trim($_POST['idioma']) : 'Español',
            'paginas' => !empty($_POST['paginas']) ? intval($_POST['paginas']) : null,
            'descripcion' => !empty($_POST['descripcion']) ? trim($_POST['descripcion']) : null,
            'tipo' => !empty($_POST['tipo']) ? $_POST['tipo'] : 'fisico',
            'total_copias' => !empty($_POST['total_copias']) ? intval($_POST['total_copias']) : 1
        ];

        if ($this->modelo->actualizar($id, $datos)) {
            $_SESSION['exito'] = "✅ Libro actualizado exitosamente";
        } else {
            $_SESSION['error'] = "❌ Error al actualizar el libro";
        }

        header('Location: index.php?ruta=libros');
    }

    /**
     * Eliminar libro
     * Ruta: /index.php?ruta=libros/eliminar&id=X
     */
    public function eliminar($id) {
        $libro = $this->modelo->obtenerPorId($id);
        if (!$libro) {
            $_SESSION['error'] = "Libro no encontrado";
            header('Location: index.php?ruta=libros');
            exit;
        }

        if ($this->modelo->eliminar($id)) {
            $_SESSION['exito'] = "✅ Libro eliminado exitosamente";
        } else {
            $_SESSION['error'] = "❌ No se puede eliminar. El libro tiene préstamos activos.";
        }

        header('Location: index.php?ruta=libros');
    }

    /**
     * Buscar libros
     * Ruta: /index.php?ruta=libros/buscar
     */
    public function buscar() {
        $termino = $_REQUEST['buscar'] ?? '';

        if (empty($termino)) {
            header('Location: index.php?ruta=libros');
            exit;
        }

        $libros = $this->modelo->buscar($termino);
        $total_libros = count($libros);
        $disponibles = 0;

        foreach ($libros as $libro) {
            if ($libro['disponible']) {
                $disponibles++;
            }
        }

        require_once 'views/libros/index.php';
    }

    /**
     * Filtrar por categoría
     * Ruta: /index.php?ruta=libros/categoria&cat=X
     */
    public function categoria() {
        $categoria = $_GET['cat'] ?? '';

        if (empty($categoria)) {
            header('Location: index.php?ruta=libros');
            exit;
        }

        $libros = $this->modelo->obtenerPorCategoria($categoria);
        $total_libros = count($libros);
        $disponibles = 0;

        foreach ($libros as $libro) {
            if ($libro['disponible']) {
                $disponibles++;
            }
        }

        require_once 'views/libros/index.php';
    }

    /**
     * Ver detalles de un libro
     * Ruta: /index.php?ruta=libros/ver&id=X
     */
    public function ver($id) {
        $libro = $this->modelo->obtenerPorId($id);

        if (!$libro) {
            $_SESSION['error'] = "Libro no encontrado";
            header('Location: index.php?ruta=libros');
            exit;
        }

        require_once 'views/libros/ver.php';
    }
}