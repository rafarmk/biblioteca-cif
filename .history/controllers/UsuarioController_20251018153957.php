<?php
// controllers/UsuarioController.php

require_once 'core/models/Usuario.php';
require_once 'config/conexion.php';

class UsuarioController {
    private $db;
    private $usuario;
    
    public function __construct() {
        $database = new Conexion();
        $this->db = $database->conectar();
        $this->usuario = new Usuario($this->db);
    }
    
    /**
     * Listar todos los usuarios
     */
    public function index() {
        try {
            $usuarios = $this->usuario->obtenerTodos();
            $tiposUsuario = Usuario::obtenerTiposUsuario();
            
            // Cargar vista
            require_once 'views/usuarios/index.php';
            
        } catch (Exception $e) {
            $this->manejarError("Error al listar usuarios: " . $e->getMessage());
        }
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function crear() {
        try {
            // Solo mostrar formulario vacío
            $tiposUsuario = Usuario::obtenerTiposUsuario();
            require_once 'views/usuarios/crear.php';
            
        } catch (Exception $e) {
            $this->manejarError("Error al mostrar formulario: " . $e->getMessage());
        }
    }
    
    /**
     * Guardar nuevo usuario
     */
    public function guardar() {
        try {
            // Validar datos básicos
            if (empty($_POST['nombre']) || empty($_POST['tipo'])) {
                $this->mostrarMensaje('error', 'El nombre y tipo de usuario son obligatorios');
                $tiposUsuario = Usuario::obtenerTiposUsuario();
                require_once 'views/usuarios/crear.php';
                return;
            }
            
            // Asignar datos al modelo
            $this->usuario->nombre = trim($_POST['nombre']);
            $this->usuario->tipo = $_POST['tipo'];
            
            // Si es policía o administrativo, el carnet es obligatorio
            if (in_array($_POST['tipo'], ['policia', 'administrativo'])) {
                if (empty($_POST['carnet_policial'])) {
                    $this->mostrarMensaje('error', 'El carnet es obligatorio para policía y personal administrativo');
                    $tiposUsuario = Usuario::obtenerTiposUsuario();
                    require_once 'views/usuarios/crear.php';
                    return;
                }
                $this->usuario->carnet_policial = strtoupper(trim($_POST['carnet_policial']));
                
                // Validar formato según tipo
                if (!$this->usuario->validarFormatoCarnet($this->usuario->carnet_policial, $this->usuario->tipo)) {
                    $formatoEsperado = $this->usuario->tipo == 'policia' ? 'POL-2024-001' : 'PH00221';
                    $this->mostrarMensaje('error', "Formato de carnet inválido. Formato esperado: {$formatoEsperado}");
                    $tiposUsuario = Usuario::obtenerTiposUsuario();
                    require_once 'views/usuarios/crear.php';
                    return;
                }
                
                // DUI no se usa para policía/admin
                $this->usuario->documento = null;
            }
            
            // Si es estudiante
            if ($_POST['tipo'] == 'estudiante') {
                // DUI es opcional
                $this->usuario->documento = !empty($_POST['documento']) ? trim($_POST['documento']) : null;
                
                // Si tiene DUI, verificar que no exista
                if (!empty($this->usuario->documento) && $this->usuario->documentoExiste($this->usuario->documento)) {
                    $this->mostrarMensaje('error', 'El DUI ya está registrado en otro usuario');
                    $tiposUsuario = Usuario::obtenerTiposUsuario();
                    require_once 'views/usuarios/crear.php';
                    return;
                }
            }
            
            // Intentar crear usuario
            if ($this->usuario->crear()) {
                $mensaje = "Usuario creado exitosamente";
                if ($this->usuario->tipo == 'estudiante') {
                    $mensaje .= ". Token temporal asignado: <strong>" . $this->usuario->token_temporal . "</strong>";
                }
                $this->mostrarMensaje('success', $mensaje);
                header("Location: index.php?ruta=usuarios");
                exit();
            } else {
                // Verificar por qué falló
                if (!empty($this->usuario->carnet_policial) && $this->usuario->carnetExiste($this->usuario->carnet_policial)) {
                    $this->mostrarMensaje('error', 'El carnet policial ya está registrado');
                } else {
                    $this->mostrarMensaje('error', 'Error al crear usuario. Verifica los datos.');
                }
                $tiposUsuario = Usuario::obtenerTiposUsuario();
                require_once 'views/usuarios/crear.php';
                return;
            }
            
        } catch (Exception $e) {
            $this->manejarError("Error al guardar usuario: " . $e->getMessage());
        }
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function editar() {
        try {
            if (!isset($_GET['id'])) {
                $this->mostrarMensaje('error', 'ID de usuario no especificado');
                header("Location: index.php?ruta=usuarios");
                exit();
            }
            
            $usuarioData = $this->usuario->obtenerPorId($_GET['id']);
            
            if (!$usuarioData) {
                $this->mostrarMensaje('error', 'Usuario no encontrado');
                header("Location: index.php?ruta=usuarios");
                exit();
            }
            
            $tiposUsuario = Usuario::obtenerTiposUsuario();
            require_once 'views/usuarios/editar.php';
            
        } catch (Exception $e) {
            $this->manejarError("Error al editar usuario: " . $e->getMessage());
        }
    }
    
    /**
     * Actualizar usuario existente
     */
    public function actualizar() {
        try {
            // Validar datos básicos
            if (empty($_POST['id']) || empty($_POST['nombre']) || empty($_POST['tipo'])) {
                $this->mostrarMensaje('error', 'El nombre y tipo de usuario son obligatorios');
                header("Location: index.php?ruta=usuarios/editar&id=" . ($_POST['id'] ?? ''));
                exit();
            }
            
            // Asignar datos al modelo
            $this->usuario->id = $_POST['id'];
            $this->usuario->nombre = trim($_POST['nombre']);
            $this->usuario->tipo = $_POST['tipo'];
            
            // Si es policía o administrativo, el carnet es obligatorio
            if (in_array($_POST['tipo'], ['policia', 'administrativo'])) {
                if (empty($_POST['carnet_policial'])) {
                    $this->mostrarMensaje('error', 'El carnet es obligatorio para policía y personal administrativo');
                    header("Location: index.php?ruta=usuarios/editar&id=" . $_POST['id']);
                    exit();
                }
                $this->usuario->carnet_policial = strtoupper(trim($_POST['carnet_policial']));
                
                // Validar formato según tipo
                if (!$this->usuario->validarFormatoCarnet($this->usuario->carnet_policial, $this->usuario->tipo)) {
                    $formatoEsperado = $this->usuario->tipo == 'policia' ? 'POL-2024-001' : 'PH00221';
                    $this->mostrarMensaje('error', "Formato de carnet inválido. Formato esperado: {$formatoEsperado}");
                    header("Location: index.php?ruta=usuarios/editar&id=" . $_POST['id']);
                    exit();
                }
                
                // DUI no se usa para policía/admin
                $this->usuario->documento = null;
            } else {
                // Si es estudiante, DUI es opcional
                $this->usuario->documento = !empty($_POST['documento']) ? trim($_POST['documento']) : null;
                $this->usuario->carnet_policial = null;
            }
            
            // Intentar actualizar
            if ($this->usuario->actualizar()) {
                $this->mostrarMensaje('success', 'Usuario actualizado exitosamente');
                header("Location: index.php?ruta=usuarios");
                exit();
            } else {
                // Verificar por qué falló
                if (!empty($this->usuario->documento) && $this->usuario->documentoExiste($this->usuario->documento, $this->usuario->id)) {
                    $this->mostrarMensaje('error', 'El DUI ya está registrado en otro usuario');
                } elseif (!empty($this->usuario->carnet_policial) && $this->usuario->carnetExiste($this->usuario->carnet_policial, $this->usuario->id)) {
                    $this->mostrarMensaje('error', 'El carnet policial ya está registrado en otro usuario');
                } else {
                    $this->mostrarMensaje('error', 'Error al actualizar usuario. Verifica los datos.');
                }
                header("Location: index.php?ruta=usuarios/editar&id=" . $_POST['id']);
                exit();
            }
            
        } catch (Exception $e) {
            $this->manejarError("Error al actualizar usuario: " . $e->getMessage());
        }
    }
    
    /**
     * Eliminar usuario
     */
    public function eliminar() {
        try {
            if (!isset($_GET['id'])) {
                $this->mostrarMensaje('error', 'ID de usuario no especificado');
                header("Location: index.php?ruta=usuarios");
                exit();
            }
            
            $this->usuario->id = $_GET['id'];
            
            // Verificar si tiene préstamos activos
            if ($this->usuario->tienePrestamosActivos()) {
                $this->mostrarMensaje('error', 'No se puede eliminar: el usuario tiene préstamos activos');
                header("Location: index.php?ruta=usuarios");
                exit();
            }
            
            // Eliminar
            if ($this->usuario->eliminar()) {
                $this->mostrarMensaje('success', 'Usuario eliminado exitosamente');
            } else {
                $this->mostrarMensaje('error', 'Error al eliminar usuario');
            }
            
            header("Location: index.php?ruta=usuarios");
            exit();
            
        } catch (Exception $e) {
            $this->manejarError("Error al eliminar usuario: " . $e->getMessage());
        }
    }
    
    /**
     * Buscar usuarios
     */
    public function buscar() {
        try {
            $termino = isset($_GET['q']) ? trim($_GET['q']) : '';
            
            if (empty($termino)) {
                return $this->index();
            }
            
            $usuarios = $this->usuario->buscar($termino);
            $tiposUsuario = Usuario::obtenerTiposUsuario();
            
            require_once 'views/usuarios/index.php';
            
        } catch (Exception $e) {
            $this->manejarError("Error al buscar usuarios: " . $e->getMessage());
        }
    }
    
    /**
     * Ver detalles de un usuario
     */
    public function ver() {
        try {
            if (!isset($_GET['id'])) {
                $this->mostrarMensaje('error', 'ID de usuario no especificado');
                header("Location: index.php?ruta=usuarios");
                exit();
            }
            
            $usuarioData = $this->usuario->obtenerPorId($_GET['id']);
            
            if (!$usuarioData) {
                $this->mostrarMensaje('error', 'Usuario no encontrado');
                header("Location: index.php?ruta=usuarios");
                exit();
            }
            
            // Obtener estadísticas
            $this->usuario->id = $usuarioData['id'];
            $estadisticas = $this->usuario->obtenerEstadisticas();
            
            require_once 'views/usuarios/ver.php';
            
        } catch (Exception $e) {
            $this->manejarError("Error al ver usuario: " . $e->getMessage());
        }
    }
    
    /**
     * Mostrar mensaje en sesión
     */
    private function mostrarMensaje($tipo, $mensaje) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['mensaje'] = $mensaje;
        $_SESSION['mensaje_tipo'] = $tipo;
    }
    
    /**
     * Manejar errores
     */
    private function manejarError($mensaje) {
        error_log($mensaje);
        $this->mostrarMensaje('error', 'Ha ocurrido un error. Por favor, intenta nuevamente.');
        header("Location: index.php?ruta=usuarios");
        exit();
    }
}
?>