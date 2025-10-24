<?php
require_once 'models/Usuario.php';

class UsuarioController {
    
    public function index() {
        $usuario = new Usuario();
        
        if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
            $usuarios = $usuario->buscar($_GET['buscar']);
        } else {
            $usuarios = $usuario->listar();
        }
        
        require_once 'views/usuarios/index.php';
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario();
            $usuario->nombre = $_POST['nombre'];
            $usuario->email = $_POST['email'];
            $usuario->telefono = $_POST['telefono'] ?? '';
            $usuario->direccion = $_POST['direccion'] ?? '';
            
            if ($usuario->crear()) {
                header('Location: index.php?ruta=usuarios&msg=creado');
                exit();
            } else {
                $error = "Error al crear el usuario";
            }
        }
        
        require_once 'views/usuarios/crear.php';
    }
    
    public function editar() {
        $usuario = new Usuario();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->id = $_POST['id'];
            $usuario->nombre = $_POST['nombre'];
            $usuario->email = $_POST['email'];
            $usuario->telefono = $_POST['telefono'] ?? '';
            $usuario->direccion = $_POST['direccion'] ?? '';
            
            if ($usuario->actualizar()) {
                header('Location: index.php?ruta=usuarios&msg=actualizado');
                exit();
            } else {
                $error = "Error al actualizar el usuario";
            }
        }
        
        $id = $_GET['id'] ?? null;
        if ($id) {
            $usuarioData = $usuario->obtenerPorId($id);
            require_once 'views/usuarios/editar.php';
        } else {
            header('Location: index.php?ruta=usuarios');
            exit();
        }
    }
    
    public function eliminar() {
        if (isset($_GET['id'])) {
            $usuario = new Usuario();
            if ($usuario->eliminar($_GET['id'])) {
                header('Location: index.php?ruta=usuarios&msg=eliminado');
            } else {
                header('Location: index.php?ruta=usuarios&msg=error');
            }
        }
        exit();
    }
}