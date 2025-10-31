<?php
require_once 'config/Database.php';

class AuthController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Método de login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validar campos
            if (empty($email) || empty($password)) {
                $error = "Por favor complete todos los campos";
                require_once 'views/auth/login.php';
                return;
            }

            // Buscar usuario en la base de datos
            $query = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si existe y la contraseña es correcta
            if ($usuario && password_verify($password, $usuario['password'])) {
                
                // Verificar que el usuario esté activo
                if ($usuario['estado'] !== 'activo') {
                    $error = "Tu cuenta está " . $usuario['estado'] . ". Contacta al administrador.";
                    require_once 'views/auth/login.php';
                    return;
                }
                
                // Login exitoso
                $_SESSION['logueado'] = true;
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'] ?? 'estudiante_mayor';

                // Actualizar último acceso
                $updateQuery = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id";
                $updateStmt = $this->db->prepare($updateQuery);
                $updateStmt->bindParam(':id', $usuario['id']);
                $updateStmt->execute();

                // Redirigir al home
                header('Location: index.php?ruta=home');
                exit();
            } else {
                // Login fallido
                $error = "Email o contraseña incorrectos";
                require_once 'views/auth/login.php';
                return;
            }
        }

        // Mostrar formulario de login
        require_once 'views/auth/login.php';
    }

    // Método de registro
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $dui = trim($_POST['dui'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            $tipo_usuario = $_POST['tipo_usuario'] ?? 'estudiante_mayor';

            // Validar campos obligatorios
            if (empty($nombre) || empty($email) || empty($telefono) || empty($dui) || 
                empty($direccion) || empty($password)) {
                $error = "Por favor complete todos los campos";
                require_once 'views/auth/registro.php';
                return;
            }

            // Validar que las contraseñas coincidan
            if ($password !== $password_confirm) {
                $error = "Las contraseñas no coinciden";
                require_once 'views/auth/registro.php';
                return;
            }

            // Validar longitud de contraseña
            if (strlen($password) < 6) {
                $error = "La contraseña debe tener al menos 6 caracteres";
                require_once 'views/auth/registro.php';
                return;
            }

            // Verificar si el email ya existe
            $checkQuery = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':email', $email);
            $checkStmt->execute();
            
            if ($checkStmt->fetchColumn() > 0) {
                $error = "El correo electrónico ya está registrado";
                require_once 'views/auth/registro.php';
                return;
            }

            // Encriptar contraseña
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insertar usuario con estado "pendiente"
            try {
                $query = "INSERT INTO usuarios 
                          (nombre, email, telefono, dui, direccion, tipo_usuario, password, estado, 
                           puede_prestar, dias_max_prestamo, max_libros_simultaneos)
                          VALUES 
                          (:nombre, :email, :telefono, :dui, :direccion, :tipo_usuario, :password, 'pendiente',
                           0, 7, 3)";

                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':email' => $email,
                    ':telefono' => $telefono,
                    ':dui' => $dui,
                    ':direccion' => $direccion,
                    ':tipo_usuario' => $tipo_usuario,
                    ':password' => $password_hash
                ]);

                $success = "Registro exitoso. Tu cuenta está pendiente de aprobación por el administrador.";
                require_once 'views/auth/registro.php';
                return;

            } catch (Exception $e) {
                $error = "Error al registrar usuario. Intenta nuevamente.";
                require_once 'views/auth/registro.php';
                return;
            }
        }

        // Mostrar formulario de registro
        require_once 'views/auth/registro.php';
    }

    // Método de logout
    public function logout() {
        // Destruir todas las variables de sesión
        $_SESSION = array();

        // Destruir la cookie de sesión si existe
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }

        // Destruir la sesión
        session_destroy();

        // Redirigir al login
        header('Location: index.php?ruta=login');
        exit();
    }

    // Verificar si el usuario está autenticado
    public static function verificarAutenticacion() {
        if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
            header('Location: index.php?ruta=login');
            exit();
        }
    }

    // Obtener información del usuario actual
    public static function getUsuarioActual() {
        if (isset($_SESSION['usuario_id'])) {
            return [
                'id' => $_SESSION['usuario_id'],
                'nombre' => $_SESSION['usuario_nombre'] ?? '',
                'email' => $_SESSION['usuario_email'] ?? '',
                'tipo_usuario' => $_SESSION['tipo_usuario'] ?? 'estudiante_mayor'
            ];
        }
        return null;
    }

    // Verificar si tiene permiso específico
    public static function tienePermiso($permiso) {
        $tipoUsuario = $_SESSION['tipo_usuario'] ?? '';

        $permisos = [
            'administrador' => ['*'], // Todos los permisos
            'gestionador' => ['gestionar_libros'],
            'personal_administrativo' => ['gestionar_prestamos'],
            'personal_operativo' => ['gestionar_prestamos'],
            'estudiante_mayor' => ['solicitar_prestamos'],
            'estudiante_menor' => ['solicitar_prestamos'],
            'visitante' => ['consultar_catalogo']
        ];

        if ($tipoUsuario === 'administrador') {
            return true; // Admin tiene todos los permisos
        }

        $permisosUsuario = $permisos[$tipoUsuario] ?? [];
        return in_array($permiso, $permisosUsuario);
    }
}
?>