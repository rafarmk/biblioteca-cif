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
            $usuario->correo = $_POST['correo'] ?? '';
            $usuario->telefono = $_POST['telefono'] ?? '';
            $usuario->tipo_usuario = $_POST['tipo_usuario'];
            $usuario->direccion = $_POST['direccion'] ?? '';
            
            if ($usuario->crear()) {
                $_SESSION['mensaje'] = "Usuario creado exitosamente";
                header('Location: index.php?ruta=usuarios');
                exit();
            } else {
                $_SESSION['error'] = "Error al crear el usuario";
            }
        }
        require_once 'views/usuarios/crear.php';
    }
    
    public function editar() {
        $usuarioModel = new Usuario();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioModel->id = $_POST['id'];
            $usuarioModel->nombre = $_POST['nombre'];
            $usuarioModel->correo = $_POST['correo'] ?? '';
            $usuarioModel->telefono = $_POST['telefono'] ?? '';
            $usuarioModel->tipo_usuario = $_POST['tipo_usuario'];
            $usuarioModel->direccion = $_POST['direccion'] ?? '';
            
            if ($usuarioModel->actualizar()) {
                $_SESSION['mensaje'] = "Usuario actualizado exitosamente";
                header('Location: index.php?ruta=usuarios');
                exit();
            } else {
                $_SESSION['error'] = "Error al actualizar el usuario";
            }
        }
        
        $id = $_GET['id'] ?? null;
        if ($id) {
            // IMPORTANTE: guardar en $usuario para que la vista lo encuentre
            $usuario = $usuarioModel->obtenerPorId($id);
            if ($usuario) {
                require_once 'views/usuarios/editar.php';
            } else {
                $_SESSION['error'] = "Usuario no encontrado";
                header('Location: index.php?ruta=usuarios');
                exit();
            }
        } else {
            header('Location: index.php?ruta=usuarios');
            exit();
        }
    }
    
    public function eliminar() {
        if (isset($_GET['id'])) {
            $usuario = new Usuario();
            if ($usuario->eliminar($_GET['id'])) {
                $_SESSION['mensaje'] = "Usuario eliminado exitosamente";
            } else {
                $_SESSION['error'] = "Error al eliminar el usuario";
            }
        }
        header('Location: index.php?ruta=usuarios');
        exit();
    }
}
