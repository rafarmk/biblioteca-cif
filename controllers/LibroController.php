<?php
require_once 'models/Libro.php';

class LibroController {
    public function index() {
        $libro = new Libro();
        if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
            $libros = $libro->buscar($_GET['buscar']);
        } else {
            $libros = $libro->listar();
        }
        require_once 'views/libros/index.php';
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libro = new Libro();
            $libro->titulo = $_POST['titulo'];
            $libro->autor = $_POST['autor'];
            $libro->isbn = $_POST['isbn'] ?? '';
            $libro->editorial = $_POST['editorial'] ?? '';
            $libro->anio_publicacion = $_POST['anio_publicacion'] ?? null;
            $libro->categoria = $_POST['categoria'] ?? '';
            $libro->cantidad_total = $_POST['cantidad_total'] ?? 1;
            $libro->cantidad_disponible = $_POST['cantidad_total'] ?? 1;
            $libro->ubicacion = $_POST['ubicacion'] ?? '';
            $libro->descripcion = $_POST['descripcion'] ?? '';
            
            if ($libro->crear()) {
                $_SESSION['mensaje'] = "Libro creado exitosamente";
                header('Location: index.php?ruta=libros');
                exit();
            } else {
                $_SESSION['error'] = "Error al crear el libro";
            }
        }
        require_once 'views/libros/crear.php';
    }
    
    public function editar() {
        $libroModel = new Libro();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libroModel->id = $_POST['id'];
            $libroModel->titulo = $_POST['titulo'];
            $libroModel->autor = $_POST['autor'];
            $libroModel->isbn = $_POST['isbn'] ?? '';
            $libroModel->editorial = $_POST['editorial'] ?? '';
            $libroModel->anio_publicacion = $_POST['anio_publicacion'] ?? null;
            $libroModel->categoria = $_POST['categoria'] ?? '';
            $libroModel->cantidad_total = $_POST['cantidad_total'] ?? 1;
            $libroModel->ubicacion = $_POST['ubicacion'] ?? '';
            $libroModel->descripcion = $_POST['descripcion'] ?? '';
            
            if ($libroModel->actualizar()) {
                $_SESSION['mensaje'] = "Libro actualizado exitosamente";
                header('Location: index.php?ruta=libros');
                exit();
            } else {
                $_SESSION['error'] = "Error al actualizar el libro";
            }
        }
        
        $id = $_GET['id'] ?? null;
        if ($id) {
            // IMPORTANTE: guardar en $libro para que la vista lo encuentre
            $libro = $libroModel->obtenerPorId($id);
            if ($libro) {
                require_once 'views/libros/editar.php';
            } else {
                $_SESSION['error'] = "Libro no encontrado";
                header('Location: index.php?ruta=libros');
                exit();
            }
        } else {
            header('Location: index.php?ruta=libros');
            exit();
        }
    }
    
    public function eliminar() {
        if (isset($_GET['id'])) {
            $libro = new Libro();
            if ($libro->eliminar($_GET['id'])) {
                $_SESSION['mensaje'] = "Libro eliminado exitosamente";
            } else {
                $_SESSION['error'] = "Error al eliminar el libro";
            }
        }
        header('Location: index.php?ruta=libros');
        exit();
    }
}
