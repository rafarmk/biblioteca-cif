<?php
require_once 'models/Usuario.php';

// Log de depuración
file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - Inicio UsuarioController\n", FILE_APPEND);

class UsuarioController {
    private $db;
    private $usuario;
    
    public function __construct($db) {
        $this->db = $db;
        $this->usuario = new Usuario($db);
        file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - Constructor OK\n", FILE_APPEND);
    }
    
    public function index() {
        $stmt = $this->usuario->leer();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/usuarios/index.php';
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $emailExistente = $this->usuario->buscarPorEmail($_POST['email'] ?? '');
            if ($emailExistente) {
                $error = "El email ya está registrado. Por favor use otro email.";
                require_once 'views/usuarios/crear.php';
                return;
            }
            
            $this->usuario->tipo_usuario = $_POST['tipo_usuario'] ?? 'estudiante_mayor';
            $this->usuario->nombre = $_POST['nombre'];
            $this->usuario->email = $_POST['email'] ?? null;
            $this->usuario->telefono = $_POST['telefono'] ?? null;
            $this->usuario->direccion = $_POST['direccion'] ?? null;
            $this->usuario->oni = $_POST['oni'] ?? null;
            $this->usuario->dui = $_POST['dui'] ?? null;
            $this->usuario->password = $_POST['password'] ?? 'password123';
            $this->usuario->estado = 'activo';
            
            try {
                if ($this->usuario->crear()) {
                    header("Location: index.php?ruta=usuarios&mensaje=Usuario creado exitosamente");
                    exit();
                } else {
                    $error = "Error al crear el usuario";
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = "El email ya está registrado. Por favor use otro email.";
                } else {
                    $error = "Error al crear el usuario: " . $e->getMessage();
                }
            }
        }
        require_once 'views/usuarios/crear.php';
    }
    
    public function editar() {
        file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - Inicio editar()\n", FILE_APPEND);
        
        try {
            if (isset($_GET['id'])) {
                file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - ID: " . $_GET['id'] . "\n", FILE_APPEND);
                
                $this->usuario->id = $_GET['id'];
                
                file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - Antes de leerUno()\n", FILE_APPEND);
                $this->usuario->leerUno();
                file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - Después de leerUno()\n", FILE_APPEND);
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - Es POST\n", FILE_APPEND);
                    
                    $emailExistente = $this->usuario->buscarPorEmail($_POST['email'] ?? '');
                    if ($emailExistente && $emailExistente['id'] != $this->usuario->id) {
                        $error = "El email ya está registrado en otro usuario.";
                        $usuario = [
                            'id' => $this->usuario->id,
                            'tipo_usuario' => $this->usuario->tipo_usuario,
                            'nombre' => $this->usuario->nombre,
                            'email' => $this->usuario->email,
                            'telefono' => $this->usuario->telefono,
                            'direccion' => $this->usuario->direccion,
                            'oni' => $this->usuario->oni,
                            'dui' => $this->usuario->dui,
                            'estado' => $this->usuario->estado
                        ];
                        require_once 'views/usuarios/editar.php';
                        return;
                    }
                    
                    $this->usuario->tipo_usuario = $_POST['tipo_usuario'] ?? 'estudiante_mayor';
                    $this->usuario->nombre = $_POST['nombre'];
                    $this->usuario->email = $_POST['email'] ?? null;
                    $this->usuario->telefono = $_POST['telefono'] ?? null;
                    $this->usuario->direccion = $_POST['direccion'] ?? null;
                    $this->usuario->oni = $_POST['oni'] ?? null;
                    $this->usuario->dui = $_POST['dui'] ?? null;
                    $this->usuario->estado = $_POST['estado'] ?? 'activo';
                    
                    file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - Antes de actualizar()\n", FILE_APPEND);
                    
                    try {
                        if ($this->usuario->actualizar()) {
                            file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - Actualización OK, redirigiendo\n", FILE_APPEND);
                            header("Location: index.php?ruta=usuarios&mensaje=Usuario actualizado exitosamente");
                            exit();
                        } else {
                            file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - Error al actualizar\n", FILE_APPEND);
                            $error = "Error al actualizar el usuario";
                        }
                    } catch (PDOException $e) {
                        file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - Excepción: " . $e->getMessage() . "\n", FILE_APPEND);
                        if ($e->getCode() == 23000) {
                            $error = "El email ya está registrado en otro usuario.";
                        } else {
                            $error = "Error al actualizar el usuario: " . $e->getMessage();
                        }
                    }
                }
                
                file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - Preparando array usuario\n", FILE_APPEND);
                
                $usuario = [
                    'id' => $this->usuario->id,
                    'tipo_usuario' => $this->usuario->tipo_usuario,
                    'nombre' => $this->usuario->nombre,
                    'email' => $this->usuario->email,
                    'telefono' => $this->usuario->telefono,
                    'direccion' => $this->usuario->direccion,
                    'oni' => $this->usuario->oni,
                    'dui' => $this->usuario->dui,
                    'estado' => $this->usuario->estado
                ];
                
                file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - Antes de cargar vista\n", FILE_APPEND);
                require_once 'views/usuarios/editar.php';
                file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - Después de cargar vista\n", FILE_APPEND);
            }
        } catch (Exception $e) {
            file_put_contents('debug_editar.log', date('Y-m-d H:i:s') . " - EXCEPCIÓN FATAL: " . $e->getMessage() . "\n", FILE_APPEND);
            die("Error fatal: " . $e->getMessage());
        }
    }
    
    public function eliminar() {
        if (isset($_GET['id'])) {
            $this->usuario->id = $_GET['id'];
            if ($this->usuario->eliminar()) {
                header("Location: index.php?ruta=usuarios&mensaje=Usuario eliminado exitosamente");
                exit();
            } else {
                header("Location: index.php?ruta=usuarios&error=Error al eliminar el usuario");
                exit();
            }
        }
    }
}
?>