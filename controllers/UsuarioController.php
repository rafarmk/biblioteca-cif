<?php
require_once 'models/Usuario.php';

class UsuarioController {
    
    public function index() {
        $usuarioModel = new Usuario();
        
        if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
            $usuarios = $usuarioModel->buscar($_GET['buscar']);
        } else {
            $usuarios = $usuarioModel->listar();
        }
        
        require_once 'views/usuarios/index.php';
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $usuarioModel = new Usuario();
                $usuarioModel->nombre = $_POST['nombre'];
                $usuarioModel->email = $_POST['email'];
                $usuarioModel->telefono = $_POST['telefono'] ?? null;
                $usuarioModel->dui = $_POST['dui'] ?? null;
                $usuarioModel->direccion = $_POST['direccion'] ?? null;
                $usuarioModel->tipo_usuario = $_POST['tipo_usuario'];
                $usuarioModel->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $usuarioModel->estado = 'activo';
                $usuarioModel->puede_prestar = 1;
                $usuarioModel->dias_max_prestamo = 7;
                $usuarioModel->max_libros_simultaneos = 3;

                if ($usuarioModel->crear()) {
                    $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '✅ Usuario creado exitosamente'];
                    header('Location: index.php?ruta=usuarios');
                    exit();
                } else {
                    $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al crear el usuario'];
                }
            } catch (Exception $e) {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $e->getMessage()];
            }
        }
        
        // CRÍTICO: Asegurarse que el archivo existe
        $vistaCrear = __DIR__ . '/../views/usuarios/crear.php';
        if (file_exists($vistaCrear)) {
            require_once $vistaCrear;
        } else {
            die("ERROR: No se encuentra el archivo views/usuarios/crear.php");
        }
    }

    public function editar() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'ID no válido'];
            header('Location: index.php?ruta=usuarios');
            exit();
        }

        $usuarioModel = new Usuario();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $usuarioModel->id = $_POST['id'];
                $usuarioModel->nombre = $_POST['nombre'];
                $usuarioModel->email = $_POST['email'];
                $usuarioModel->telefono = $_POST['telefono'] ?? null;
                $usuarioModel->dui = $_POST['dui'] ?? null;
                $usuarioModel->direccion = $_POST['direccion'] ?? null;
                $usuarioModel->tipo_usuario = $_POST['tipo_usuario'];
                $usuarioModel->estado = $_POST['estado'] ?? 'activo';
                $usuarioModel->puede_prestar = isset($_POST['puede_prestar']) ? 1 : 0;
                $usuarioModel->dias_max_prestamo = $_POST['dias_max_prestamo'] ?? 7;
                $usuarioModel->max_libros_simultaneos = $_POST['max_libros_simultaneos'] ?? 3;

                if ($usuarioModel->actualizar()) {
                    $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '✅ Usuario actualizado exitosamente'];
                    header('Location: index.php?ruta=usuarios');
                    exit();
                } else {
                    $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al actualizar el usuario'];
                }
            } catch (Exception $e) {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $e->getMessage()];
            }
        }

        $usuario = $usuarioModel->obtenerPorId($id);
        
        if (!$usuario) {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Usuario no encontrado'];
            header('Location: index.php?ruta=usuarios');
            exit();
        }
        
        $vistaEditar = __DIR__ . '/../views/usuarios/editar.php';
        if (file_exists($vistaEditar)) {
            require_once $vistaEditar;
        } else {
            die("ERROR: No se encuentra el archivo views/usuarios/editar.php");
        }
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'ID no válido'];
            header('Location: index.php?ruta=usuarios');
            exit();
        }
        
        try {
            $usuarioModel = new Usuario();
            
            if ($usuarioModel->eliminar($id)) {
                $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '✅ Usuario eliminado exitosamente'];
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al eliminar el usuario'];
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: ' . $e->getMessage()];
        }
        
        header('Location: index.php?ruta=usuarios');
        exit();
    }
}