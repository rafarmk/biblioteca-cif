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
            $libro->cantidad_disponible = $_POST['cantidad_disponible'] ?? 1;
            $libro->ubicacion = $_POST['ubicacion'] ?? '';
            
            if ($libro->crear()) {
                header('Location: index.php?ruta=libros&msg=creado');
                exit();
            } else {
                $error = "Error al crear el libro";
            }
        }
        
        require_once 'views/libros/crear.php';
    }
    
    public function editar() {
        $libro = new Libro();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libro->id = $_POST['id'];
            $libro->titulo = $_POST['titulo'];
            $libro->autor = $_POST['autor'];
            $libro->isbn = $_POST['isbn'] ?? '';
            $libro->editorial = $_POST['editorial'] ?? '';
            $libro->anio_publicacion = $_POST['anio_publicacion'] ?? null;
            $libro->categoria = $_POST['categoria'] ?? '';
            $libro->cantidad_disponible = $_POST['cantidad_disponible'] ?? 1;
            $libro->ubicacion = $_POST['ubicacion'] ?? '';
            
            if ($libro->actualizar()) {
                header('Location: index.php?ruta=libros&msg=actualizado');
                exit();
            } else {
                $error = "Error al actualizar el libro";
            }
        }
        
        $id = $_GET['id'] ?? null;
        if ($id) {
            $libroData = $libro->obtenerPorId($id);
            require_once 'views/libros/editar.php';
        } else {
            header('Location: index.php?ruta=libros');
            exit();
        }
    }
    
    public function eliminar() {
        if (isset($_GET['id'])) {
            $libro = new Libro();
            if ($libro->eliminar($_GET['id'])) {
                header('Location: index.php?ruta=libros&msg=eliminado');
            } else {
                header('Location: index.php?ruta=libros&msg=error');
            }
        }
        exit();
    }
}