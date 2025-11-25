<?php
require_once __DIR__ . '/../config/Database.php';

class PrestamoController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        require_once __DIR__ . '/../views/prestamos/index.php';
    }

    public function activos() {
        require_once __DIR__ . '/../views/prestamos/activos.php';
    }

    public function atrasados() {
        require_once __DIR__ . '/../views/prestamos/atrasados.php';
    }

    public function crear() {
        require_once __DIR__ . '/../views/prestamos/crear.php';
    }

    public function ver() {
        require_once __DIR__ . '/../views/prestamos/ver.php';
    }

    public function store() {
        try {
            $libro_id = $_POST['libro_id'];
            $usuario_id = $_POST['usuario_id'];
            $fecha_prestamo = $_POST['fecha_prestamo'];
            $fecha_devolucion_esperada = $_POST['fecha_devolucion'] ?? date('Y-m-d', strtotime('+15 days'));

            $stmt = $this->db->prepare("SELECT cantidad_disponible FROM libros WHERE id = ?");
            $stmt->execute([$libro_id]);
            $libro = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($libro['cantidad_disponible'] <= 0) {
                $_SESSION['mensaje'] = [
                    'tipo' => 'danger',
                    'texto' => '❌ No hay copias disponibles de este libro'
                ];
                header('Location: index.php?ruta=prestamos&accion=crear');
                exit;
            }

            $stmt = $this->db->prepare("
                INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, fecha_devolucion_esperada, estado)
                VALUES (?, ?, ?, ?, 'activo')
            ");
            $stmt->execute([$libro_id, $usuario_id, $fecha_prestamo, $fecha_devolucion_esperada]);

            $stmt = $this->db->prepare("
                UPDATE libros SET cantidad_disponible = cantidad_disponible - 1 WHERE id = ?
            ");
            $stmt->execute([$libro_id]);

            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => '✅ Préstamo registrado exitosamente'
            ];

        } catch (PDOException $e) {
            $_SESSION['mensaje'] = [
                'tipo' => 'danger',
                'texto' => '❌ Error: ' . $e->getMessage()
            ];
        }

        header('Location: index.php?ruta=prestamos');
        exit;
    }

    public function solicitar() {
        try {
            $libro_id = $_POST['libro_id'];
            $usuario_id = $_SESSION['usuario_id'];

            $stmt = $this->db->prepare("
                SELECT puntos_totales, nivel 
                FROM comportamiento_usuarios 
                WHERE usuario_id = ?
            ");
            $stmt->execute([$usuario_id]);
            $comportamiento = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$comportamiento) {
                $stmt = $this->db->prepare("INSERT INTO comportamiento_usuarios (usuario_id) VALUES (?)");
                $stmt->execute([$usuario_id]);
                $comportamiento = ['puntos_totales' => 100, 'nivel' => 'bueno'];
            }
            
            if ($comportamiento['nivel'] === 'suspendido') {
                $_SESSION['mensaje'] = [
                    'tipo' => 'danger',
                    'texto' => '🚫 Tu cuenta está suspendida por mal comportamiento. Puntos actuales: ' . $comportamiento['puntos_totales'] . '. Contacta al administrador.'
                ];
                header('Location: index.php?ruta=catalogo');
                exit;
            }
            
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total 
                FROM prestamos 
                WHERE usuario_id = ? AND estado = 'activo'
            ");
            $stmt->execute([$usuario_id]);
            $prestamos_activos = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $limite_prestamos = 3;
            switch ($comportamiento['nivel']) {
                case 'excelente': $limite_prestamos = 5; break;
                case 'bueno': $limite_prestamos = 3; break;
                case 'regular': $limite_prestamos = 2; break;
                case 'bajo': $limite_prestamos = 1; break;
            }
            
            if ($prestamos_activos['total'] >= $limite_prestamos) {
                $_SESSION['mensaje'] = [
                    'tipo' => 'warning',
                    'texto' => '⚠️ Alcanzaste tu límite de ' . $limite_prestamos . ' préstamo(s) simultáneo(s) (Nivel: ' . strtoupper($comportamiento['nivel']) . '). Devuelve un libro para solicitar otro.'
                ];
                header('Location: index.php?ruta=catalogo');
                exit;
            }

            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total, GROUP_CONCAT(l.titulo SEPARATOR ', ') as libros_atrasados
                FROM prestamos p
                INNER JOIN libros l ON p.libro_id = l.id
                WHERE p.usuario_id = ? 
                AND p.estado = 'activo' 
                AND p.fecha_devolucion_esperada < CURDATE()
            ");
            $stmt->execute([$usuario_id]);
            $atrasados = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($atrasados['total'] > 0) {
                $_SESSION['mensaje'] = [
                    'tipo' => 'danger',
                    'texto' => '🚫 No puedes solicitar más libros. Tienes ' . $atrasados['total'] . ' préstamo(s) atrasado(s). Por favor devuelve: ' . $atrasados['libros_atrasados']
                ];
                header('Location: index.php?ruta=catalogo');
                exit;
            }

            $stmt = $this->db->prepare("SELECT cantidad_disponible, titulo FROM libros WHERE id = ?");
            $stmt->execute([$libro_id]);
            $libro = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$libro || $libro['cantidad_disponible'] <= 0) {
                $_SESSION['mensaje'] = [
                    'tipo' => 'danger',
                    'texto' => '❌ No hay copias disponibles de este libro'
                ];
                header('Location: index.php?ruta=catalogo');
                exit;
            }

            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total
                FROM prestamos
                WHERE usuario_id = ? AND libro_id = ? AND estado = 'activo'
            ");
            $stmt->execute([$usuario_id, $libro_id]);
            $existe = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existe['total'] > 0) {
                $_SESSION['mensaje'] = [
                    'tipo' => 'warning',
                    'texto' => '⚠️ Ya tienes un préstamo activo de este libro'
                ];
                header('Location: index.php?ruta=catalogo');
                exit;
            }

            $fecha_prestamo = date('Y-m-d H:i:s');
            $fecha_devolucion_esperada = date('Y-m-d', strtotime('+15 days'));

            $stmt = $this->db->prepare("
                INSERT INTO prestamos 
                (libro_id, usuario_id, fecha_prestamo, fecha_devolucion_esperada, estado)
                VALUES (?, ?, ?, ?, 'activo')
            ");
            $stmt->execute([$libro_id, $usuario_id, $fecha_prestamo, $fecha_devolucion_esperada]);

            $stmt = $this->db->prepare("
                UPDATE libros SET cantidad_disponible = cantidad_disponible - 1 WHERE id = ?
            ");
            $stmt->execute([$libro_id]);

            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => '✅ Préstamo solicitado exitosamente. Tienes 15 días para devolverlo (hasta el ' . date('d/m/Y', strtotime('+15 days')) . ')'
            ];

        } catch (PDOException $e) {
            $_SESSION['mensaje'] = [
                'tipo' => 'danger',
                'texto' => '❌ Error: ' . $e->getMessage()
            ];
        }

        header('Location: index.php?ruta=mis_prestamos');
        exit;
    }

    public function misPrestamos() {
        require_once __DIR__ . '/../views/prestamos/mis_prestamos.php';
    }

    public function devolver() {
        require_once __DIR__ . '/../views/prestamos/devolver.php';
    }

    public function procesarDevolucion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?ruta=mis_prestamos');
            exit;
        }
        
        try {
            $prestamo_id = $_POST['prestamo_id'];
            $libro_id = $_POST['libro_id'];
            $sin_resena = isset($_POST['sin_resena']) ? 1 : 0;
            
            $stmt = $this->db->prepare("
                SELECT p.*, DATEDIFF(CURDATE(), p.fecha_devolucion_esperada) as dias_retraso
                FROM prestamos p
                WHERE p.id = ? AND p.usuario_id = ? AND p.estado = 'activo'
            ");
            $stmt->execute([$prestamo_id, $_SESSION['usuario_id']]);
            $prestamo = $stmt->fetch();
            
            if (!$prestamo) {
                $_SESSION['mensaje'] = [
                    'tipo' => 'danger',
                    'texto' => '❌ Préstamo no válido'
                ];
                header('Location: index.php?ruta=mis_prestamos');
                exit;
            }
            
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare("
                UPDATE prestamos SET estado = 'devuelto', fecha_devolucion_real = NOW() WHERE id = ?
            ");
            $stmt->execute([$prestamo_id]);
            
            $stmt = $this->db->prepare("
                UPDATE libros SET cantidad_disponible = cantidad_disponible + 1 WHERE id = ?
            ");
            $stmt->execute([$libro_id]);
            
            $dias_retraso = intval($prestamo['dias_retraso']);
            $usuario_id = $_SESSION['usuario_id'];
            $puntos = 0;
            $tipo_incidente = '';
            $descripcion = '';
            
            if ($dias_retraso <= 0) {
                $puntos = 2;
                $tipo_incidente = 'devolucion_a_tiempo';
                $descripcion = 'Devolución puntual del libro';
                
                $stmt = $this->db->prepare("
                    UPDATE comportamiento_usuarios 
                    SET prestamos_consecutivos_a_tiempo = prestamos_consecutivos_a_tiempo + 1
                    WHERE usuario_id = ?
                ");
                $stmt->execute([$usuario_id]);
                
                $stmt = $this->db->prepare("
                    SELECT prestamos_consecutivos_a_tiempo FROM comportamiento_usuarios WHERE usuario_id = ?
                ");
                $stmt->execute([$usuario_id]);
                $comp = $stmt->fetch();
                
                if ($comp && $comp['prestamos_consecutivos_a_tiempo'] >= 10) {
                    $puntos += 20;
                    $descripcion .= ' - ¡Bonus por 10 devoluciones consecutivas a tiempo!';
                    $stmt = $this->db->prepare("
                        UPDATE comportamiento_usuarios 
                        SET prestamos_consecutivos_a_tiempo = 0 WHERE usuario_id = ?
                    ");
                    $stmt->execute([$usuario_id]);
                }
            } else {
                if ($dias_retraso <= 3) {
                    $puntos = -5;
                    $tipo_incidente = 'retraso_leve';
                } elseif ($dias_retraso <= 7) {
                    $puntos = -10;
                    $tipo_incidente = 'retraso_moderado';
                } else {
                    $puntos = -20;
                    $tipo_incidente = 'retraso_grave';
                }
                $descripcion = "Retraso de $dias_retraso día(s)";
                
                $stmt = $this->db->prepare("
                    UPDATE comportamiento_usuarios 
                    SET prestamos_consecutivos_a_tiempo = 0, total_retrasos = total_retrasos + 1
                    WHERE usuario_id = ?
                ");
                $stmt->execute([$usuario_id]);
            }
            
            $stmt = $this->db->prepare("
                INSERT INTO incidentes_usuarios 
                (usuario_id, prestamo_id, tipo_incidente, puntos_afectados, descripcion)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$usuario_id, $prestamo_id, $tipo_incidente, $puntos, $descripcion]);
            
            $stmt = $this->db->prepare("
                UPDATE comportamiento_usuarios 
                SET puntos_totales = puntos_totales + ?,
                    total_prestamos_completados = total_prestamos_completados + 1
                WHERE usuario_id = ?
            ");
            $stmt->execute([$puntos, $usuario_id]);
            
            $stmt = $this->db->prepare("SELECT puntos_totales FROM comportamiento_usuarios WHERE usuario_id = ?");
            $stmt->execute([$usuario_id]);
            $comp = $stmt->fetch();
            $pts = $comp['puntos_totales'];
            
            if ($pts < 0) $nivel = 'suspendido';
            elseif ($pts < 20) $nivel = 'bajo';
            elseif ($pts < 50) $nivel = 'regular';
            elseif ($pts < 100) $nivel = 'bueno';
            else $nivel = 'excelente';
            
            $stmt = $this->db->prepare("UPDATE comportamiento_usuarios SET nivel = ? WHERE usuario_id = ?");
            $stmt->execute([$nivel, $usuario_id]);
            
            if (!$sin_resena && isset($_POST['calificacion']) && !empty($_POST['calificacion'])) {
                $calificacion = intval($_POST['calificacion']);
                $comentario = trim($_POST['comentario'] ?? '');
                
                if ($calificacion >= 1 && $calificacion <= 5) {
                    $stmt = $this->db->prepare("
                        INSERT INTO resenas_libros 
                        (libro_id, usuario_id, prestamo_id, calificacion, comentario) 
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$libro_id, $usuario_id, $prestamo_id, $calificacion, $comentario]);
                    
                    $stmt = $this->db->prepare("
                        INSERT INTO incidentes_usuarios 
                        (usuario_id, prestamo_id, tipo_incidente, puntos_afectados, descripcion)
                        VALUES (?, ?, 'resena_dejada', 5, 'Contribución con reseña del libro')
                    ");
                    $stmt->execute([$usuario_id, $prestamo_id]);
                    
                    $stmt = $this->db->prepare("
                        UPDATE comportamiento_usuarios 
                        SET puntos_totales = puntos_totales + 5 WHERE usuario_id = ?
                    ");
                    $stmt->execute([$usuario_id]);
                    
                    $puntos += 5;
                }
            }
            
            $this->db->commit();
            
            $mensaje_puntos = "";
            if ($puntos > 0) $mensaje_puntos = " Ganaste +$puntos puntos! 🎉";
            elseif ($puntos < 0) $mensaje_puntos = " Perdiste $puntos puntos por retraso. ⚠️";
            
            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => '✅ Libro devuelto exitosamente.' . $mensaje_puntos
            ];
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            $_SESSION['mensaje'] = [
                'tipo' => 'danger',
                'texto' => '❌ Error: ' . $e->getMessage()
            ];
        }

        header('Location: index.php?ruta=mis_prestamos');
        exit;
    }

    public function miCalificacion() {
        require_once __DIR__ . '/../views/usuario/mi_calificacion.php';
    }

    public function calificacionesUsuarios() {
        if ($_SESSION['tipo_usuario'] !== 'administrador') {
            header('Location: index.php?ruta=catalogo');
            exit;
        }
        require_once __DIR__ . '/../views/admin/calificaciones.php';
    }

    public function registrarIncidente() {
        if ($_SESSION['tipo_usuario'] !== 'administrador') {
            header('Location: index.php?ruta=home');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?ruta=calificaciones_usuarios');
            exit;
        }
        
        try {
            $usuario_id = $_POST['usuario_id'];
            $tipo_incidente = $_POST['tipo_incidente'];
            $descripcion = $_POST['descripcion'];
            
            $puntos_map = [
                'libro_danado' => -30,
                'libro_perdido' => -100,
                'advertencia' => -10,
                'recompensa' => 50
            ];
            
            $puntos = $puntos_map[$tipo_incidente] ?? 0;
            
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare("
                INSERT INTO incidentes_usuarios 
                (usuario_id, tipo_incidente, puntos_afectados, descripcion, registrado_por)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$usuario_id, $tipo_incidente, $puntos, $descripcion, $_SESSION['usuario_id']]);
            
            $stmt = $this->db->prepare("
                UPDATE comportamiento_usuarios 
                SET puntos_totales = puntos_totales + ?
                WHERE usuario_id = ?
            ");
            $stmt->execute([$puntos, $usuario_id]);
            
            $stmt = $this->db->prepare("SELECT puntos_totales FROM comportamiento_usuarios WHERE usuario_id = ?");
            $stmt->execute([$usuario_id]);
            $comp = $stmt->fetch();
            $pts = $comp['puntos_totales'];
            
            if ($pts < 0) $nivel = 'suspendido';
            elseif ($pts < 20) $nivel = 'bajo';
            elseif ($pts < 50) $nivel = 'regular';
            elseif ($pts < 100) $nivel = 'bueno';
            else $nivel = 'excelente';
            
            $stmt = $this->db->prepare("UPDATE comportamiento_usuarios SET nivel = ? WHERE usuario_id = ?");
            $stmt->execute([$nivel, $usuario_id]);
            
            $this->db->commit();
            
            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => '✅ Incidente registrado correctamente'
            ];
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            $_SESSION['mensaje'] = [
                'tipo' => 'danger',
                'texto' => '❌ Error: ' . $e->getMessage()
            ];
        }
        
        header('Location: index.php?ruta=calificaciones_usuarios');
        exit;
    }

    public function reactivarUsuario() {
        if ($_SESSION['tipo_usuario'] !== 'administrador') {
            header('Location: index.php?ruta=home');
            exit;
        }
        
        try {
            $usuario_id = $_GET['usuario_id'] ?? 0;
            
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare("
                UPDATE comportamiento_usuarios 
                SET puntos_totales = 50, nivel = 'regular', prestamos_consecutivos_a_tiempo = 0
                WHERE usuario_id = ?
            ");
            $stmt->execute([$usuario_id]);
            
            $stmt = $this->db->prepare("
                INSERT INTO incidentes_usuarios 
                (usuario_id, tipo_incidente, puntos_afectados, descripcion, registrado_por)
                VALUES (?, 'recompensa', 50, 'Reactivación de cuenta por administrador', ?)
            ");
            $stmt->execute([$usuario_id, $_SESSION['usuario_id']]);
            
            $this->db->commit();
            
            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => '✅ Usuario reactivado con 50 puntos (Nivel: Regular)'
            ];
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            $_SESSION['mensaje'] = [
                'tipo' => 'danger',
                'texto' => '❌ Error: ' . $e->getMessage()
            ];
        }
        
        header('Location: index.php?ruta=calificaciones_usuarios');
        exit;
    }

    public function verResenas() {
        $libro_id = $_GET['libro_id'] ?? 0;
        require_once __DIR__ . '/../views/prestamos/resenas.php';
    }

    public function editar() {
        $id = $_GET['id'] ?? 0;
        require_once __DIR__ . '/../views/prestamos/editar.php';
    }

    public function update() {
        try {
            $id = $_POST['id'];
            $fecha_devolucion_esperada = $_POST['fecha_devolucion'];
            $estado = $_POST['estado'];

            $stmt = $this->db->prepare("
                UPDATE prestamos SET fecha_devolucion_esperada = ?, estado = ? WHERE id = ?
            ");
            $stmt->execute([$fecha_devolucion_esperada, $estado, $id]);

            if ($estado === 'devuelto') {
                $stmt = $this->db->prepare("SELECT libro_id FROM prestamos WHERE id = ?");
                $stmt->execute([$id]);
                $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

                $stmt = $this->db->prepare("
                    UPDATE libros SET cantidad_disponible = cantidad_disponible + 1 WHERE id = ?
                ");
                $stmt->execute([$prestamo['libro_id']]);
            }

            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => '✅ Préstamo actualizado'
            ];

        } catch (PDOException $e) {
            $_SESSION['mensaje'] = [
                'tipo' => 'danger',
                'texto' => '❌ Error: ' . $e->getMessage()
            ];
        }

        header('Location: index.php?ruta=prestamos');
        exit;
    }

    public function eliminar() {
        try {
            $id = $_GET['id'] ?? 0;

            $stmt = $this->db->prepare("SELECT libro_id, estado FROM prestamos WHERE id = ?");
            $stmt->execute([$id]);
            $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($prestamo['estado'] === 'activo') {
                $stmt = $this->db->prepare("
                    UPDATE libros SET cantidad_disponible = cantidad_disponible + 1 WHERE id = ?
                ");
                $stmt->execute([$prestamo['libro_id']]);
            }

            $stmt = $this->db->prepare("DELETE FROM prestamos WHERE id = ?");
            $stmt->execute([$id]);

            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => '✅ Préstamo eliminado'
            ];

        } catch (PDOException $e) {
            $_SESSION['mensaje'] = [
                'tipo' => 'danger',
                'texto' => '❌ Error: ' . $e->getMessage()
            ];
        }

        header('Location: index.php?ruta=prestamos');
        exit;
    }
}