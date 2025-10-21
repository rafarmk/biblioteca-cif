<?php
/**
 * Modelo: Prestamo
 * 
 * Descripción: Gestiona todas las operaciones relacionadas con préstamos de libros
 * Autor: José Raphael Ernesto Pérez Hernández
 * Fecha: 12 de Octubre, 2025
 */

class Prestamo {
    private $conexion;
    
    /**
     * Constructor
     * Recibe la conexión a la base de datos
     */
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    
    /**
     * Obtener todos los préstamos con información completa
     * Incluye datos del libro y usuario mediante JOINs
     * 
     * @return array Lista de préstamos
     */
    public function obtenerTodos() {
        $sql = "SELECT 
                    p.id,
                    p.fecha_prestamo,
                    p.fecha_devolucion,
                    p.estado,
                    l.id as libro_id,
                    l.titulo as libro_titulo,
                    l.autor as libro_autor,
                    u.id as usuario_id,
                    u.nombre as usuario_nombre,
                    u.documento as usuario_documento,
                    u.tipo as usuario_tipo,
                    DATEDIFF(CURDATE(), p.fecha_prestamo) as dias_transcurridos
                FROM prestamos p
                INNER JOIN libros l ON p.libro_id = l.id
                INNER JOIN usuarios u ON p.usuario_id = u.id
                ORDER BY p.id DESC";
        
        $resultado = $this->conexion->query($sql);
        
        $prestamos = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($prestamo = $resultado->fetch_assoc()) {
                $prestamos[] = $prestamo;
            }
        }
        
        return $prestamos;
    }
    
    /**
     * Obtener un préstamo por su ID
     * 
     * @param int $id ID del préstamo
     * @return array|null Datos del préstamo o null si no existe
     */
    public function obtenerPorId($id) {
        $sql = "SELECT 
                    p.*,
                    l.titulo as libro_titulo,
                    l.autor as libro_autor,
                    u.nombre as usuario_nombre,
                    u.documento as usuario_documento,
                    u.tipo as usuario_tipo
                FROM prestamos p
                INNER JOIN libros l ON p.libro_id = l.id
                INNER JOIN usuarios u ON p.usuario_id = u.id
                WHERE p.id = ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
    
    /**
     * Crear un nuevo préstamo
     * Usa TRANSACCIÓN para garantizar la integridad de datos
     * 
     * @param array $datos Datos del préstamo (libro_id, usuario_id, fecha_prestamo)
     * @return array Array con 'exito' (bool) y 'mensaje' (string)
     */
    public function crear($datos) {
        // Iniciar transacción
        $this->conexion->begin_transaction();
        
        try {
            // 1. Verificar que el libro esté disponible
            $sqlVerificar = "SELECT disponible FROM libros WHERE id = ?";
            $stmtVerificar = $this->conexion->prepare($sqlVerificar);
            $stmtVerificar->bind_param("i", $datos['libro_id']);
            $stmtVerificar->execute();
            $libro = $stmtVerificar->get_result()->fetch_assoc();
            
            if (!$libro || !$libro['disponible']) {
                throw new Exception("El libro no está disponible");
            }
            
            // 2. Verificar límite de préstamos del usuario
            $sqlConteo = "SELECT COUNT(*) as total 
                         FROM prestamos 
                         WHERE usuario_id = ? AND estado = 'activo'";
            $stmtConteo = $this->conexion->prepare($sqlConteo);
            $stmtConteo->bind_param("i", $datos['usuario_id']);
            $stmtConteo->execute();
            $conteo = $stmtConteo->get_result()->fetch_assoc();
            
            if ($conteo['total'] >= 3) {
                throw new Exception("El usuario ya tiene 3 libros prestados (límite máximo)");
            }
            
            // 3. Crear el préstamo
            $sqlPrestamo = "INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, estado) 
                           VALUES (?, ?, ?, 'activo')";
            $stmtPrestamo = $this->conexion->prepare($sqlPrestamo);
            $stmtPrestamo->bind_param("iis",
                $datos['libro_id'],
                $datos['usuario_id'],
                $datos['fecha_prestamo']
            );
            
            if (!$stmtPrestamo->execute()) {
                throw new Exception("Error al crear el préstamo");
            }
            
            // 4. Marcar libro como no disponible
            $sqlActualizar = "UPDATE libros SET disponible = FALSE WHERE id = ?";
            $stmtActualizar = $this->conexion->prepare($sqlActualizar);
            $stmtActualizar->bind_param("i", $datos['libro_id']);
            
            if (!$stmtActualizar->execute()) {
                throw new Exception("Error al actualizar disponibilidad del libro");
            }
            
            // Confirmar transacción
            $this->conexion->commit();
            
            return [
                'exito' => true,
                'mensaje' => 'Préstamo registrado exitosamente',
                'prestamo_id' => $this->conexion->insert_id
            ];
            
        } catch (Exception $e) {
            // Revertir cambios si hay error
            $this->conexion->rollback();
            
            return [
                'exito' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Registrar devolución de un libro
     * Usa TRANSACCIÓN para garantizar la integridad de datos
     * 
     * @param int $id ID del préstamo
     * @param string $fecha_devolucion Fecha de devolución
     * @return array Array con 'exito' (bool) y 'mensaje' (string)
     */
    public function devolverLibro($id, $fecha_devolucion) {
        // Iniciar transacción
        $this->conexion->begin_transaction();
        
        try {
            // 1. Obtener información del préstamo
            $sqlPrestamo = "SELECT libro_id, estado FROM prestamos WHERE id = ?";
            $stmtPrestamo = $this->conexion->prepare($sqlPrestamo);
            $stmtPrestamo->bind_param("i", $id);
            $stmtPrestamo->execute();
            $prestamo = $stmtPrestamo->get_result()->fetch_assoc();
            
            if (!$prestamo) {
                throw new Exception("Préstamo no encontrado");
            }
            
            if ($prestamo['estado'] === 'devuelto') {
                throw new Exception("Este libro ya fue devuelto");
            }
            
            // 2. Actualizar préstamo como devuelto
            $sqlActualizarPrestamo = "UPDATE prestamos 
                                     SET fecha_devolucion = ?, estado = 'devuelto' 
                                     WHERE id = ?";
            $stmtActualizarPrestamo = $this->conexion->prepare($sqlActualizarPrestamo);
            $stmtActualizarPrestamo->bind_param("si", $fecha_devolucion, $id);
            
            if (!$stmtActualizarPrestamo->execute()) {
                throw new Exception("Error al actualizar el préstamo");
            }
            
            // 3. Marcar libro como disponible
            $sqlActualizarLibro = "UPDATE libros SET disponible = TRUE WHERE id = ?";
            $stmtActualizarLibro = $this->conexion->prepare($sqlActualizarLibro);
            $stmtActualizarLibro->bind_param("i", $prestamo['libro_id']);
            
            if (!$stmtActualizarLibro->execute()) {
                throw new Exception("Error al actualizar disponibilidad del libro");
            }
            
            // Confirmar transacción
            $this->conexion->commit();
            
            return [
                'exito' => true,
                'mensaje' => 'Devolución registrada exitosamente'
            ];
            
        } catch (Exception $e) {
            // Revertir cambios si hay error
            $this->conexion->rollback();
            
            return [
                'exito' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtener préstamos activos (no devueltos)
     * 
     * @return array Lista de préstamos activos
     */
    public function obtenerActivos() {
        $sql = "SELECT 
                    p.id,
                    p.fecha_prestamo,
                    l.titulo as libro_titulo,
                    l.autor as libro_autor,
                    u.nombre as usuario_nombre,
                    u.documento as usuario_documento,
                    u.tipo as usuario_tipo,
                    DATEDIFF(CURDATE(), p.fecha_prestamo) as dias_transcurridos
                FROM prestamos p
                INNER JOIN libros l ON p.libro_id = l.id
                INNER JOIN usuarios u ON p.usuario_id = u.id
                WHERE p.estado = 'activo'
                ORDER BY p.fecha_prestamo ASC";
        
        $resultado = $this->conexion->query($sql);
        
        $prestamos = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($prestamo = $resultado->fetch_assoc()) {
                $prestamos[] = $prestamo;
            }
        }
        
        return $prestamos;
    }
    
    /**
     * Obtener préstamos con retraso (más de 7 días)
     * 
     * @return array Lista de préstamos con retraso
     */
    public function obtenerConRetraso() {
        $sql = "SELECT 
                    p.id,
                    p.fecha_prestamo,
                    l.titulo as libro_titulo,
                    l.autor as libro_autor,
                    u.nombre as usuario_nombre,
                    u.documento as usuario_documento,
                    u.tipo as usuario_tipo,
                    DATEDIFF(CURDATE(), p.fecha_prestamo) as dias_transcurridos,
                    (DATEDIFF(CURDATE(), p.fecha_prestamo) - 7) as dias_retraso
                FROM prestamos p
                INNER JOIN libros l ON p.libro_id = l.id
                INNER JOIN usuarios u ON p.usuario_id = u.id
                WHERE p.estado = 'activo' 
                  AND DATEDIFF(CURDATE(), p.fecha_prestamo) > 7
                ORDER BY dias_transcurridos DESC";
        
        $resultado = $this->conexion->query($sql);
        
        $prestamos = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($prestamo = $resultado->fetch_assoc()) {
                $prestamos[] = $prestamo;
            }
        }
        
        return $prestamos;
    }
    
    /**
     * Contar total de préstamos
     * 
     * @return int Total de préstamos
     */
    public function contarTotal() {
        $sql = "SELECT COUNT(*) as total FROM prestamos";
        $resultado = $this->conexion->query($sql);
        $datos = $resultado->fetch_assoc();
        
        return $datos['total'];
    }
    
    /**
     * Contar préstamos activos
     * 
     * @return int Total de préstamos activos
     */
    public function contarActivos() {
        $sql = "SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'";
        $resultado = $this->conexion->query($sql);
        $datos = $resultado->fetch_assoc();
        
        return $datos['total'];
    }
    
    /**
     * Contar préstamos con retraso
     * 
     * @return int Total de préstamos con retraso
     */
    public function contarConRetraso() {
        $sql = "SELECT COUNT(*) as total 
                FROM prestamos 
                WHERE estado = 'activo' 
                  AND DATEDIFF(CURDATE(), fecha_prestamo) > 7";
        
        $resultado = $this->conexion->query($sql);
        $datos = $resultado->fetch_assoc();
        
        return $datos['total'];
    }
    
    /**
     * Obtener estadísticas de préstamos
     * 
     * @return array Array con estadísticas
     */
    public function obtenerEstadisticas() {
        return [
            'total' => $this->contarTotal(),
            'activos' => $this->contarActivos(),
            'devueltos' => $this->contarTotal() - $this->contarActivos(),
            'con_retraso' => $this->contarConRetraso()
        ];
    }
    
    /**
     * Obtener libros más prestados
     * 
     * @param int $limite Número de libros a mostrar (default: 10)
     * @return array Lista de libros más prestados
     */
    public function obtenerLibrosMasPrestados($limite = 10) {
        $sql = "SELECT 
                    l.id,
                    l.titulo,
                    l.autor,
                    l.categoria,
                    COUNT(p.id) as total_prestamos
                FROM libros l
                INNER JOIN prestamos p ON l.id = p.libro_id
                GROUP BY l.id
                ORDER BY total_prestamos DESC
                LIMIT ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $libros = [];
        
        while ($libro = $resultado->fetch_assoc()) {
            $libros[] = $libro;
        }
        
        return $libros;
    }
    
    /**
     * Obtener usuarios más activos
     * 
     * @param int $limite Número de usuarios a mostrar (default: 10)
     * @return array Lista de usuarios más activos
     */
    public function obtenerUsuariosMasActivos($limite = 10) {
        $sql = "SELECT 
                    u.id,
                    u.nombre,
                    u.documento,
                    u.tipo,
                    COUNT(p.id) as total_prestamos,
                    COUNT(CASE WHEN p.estado = 'activo' THEN 1 END) as prestamos_activos
                FROM usuarios u
                INNER JOIN prestamos p ON u.id = p.usuario_id
                GROUP BY u.id
                ORDER BY total_prestamos DESC
                LIMIT ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $usuarios = [];
        
        while ($usuario = $resultado->fetch_assoc()) {
            $usuarios[] = $usuario;
        }
        
        return $usuarios;
    }
    
    /**
     * Obtener préstamos por rango de fechas
     * 
     * @param string $fecha_inicio Fecha inicial
     * @param string $fecha_fin Fecha final
     * @return array Lista de préstamos en ese rango
     */
    public function obtenerPorRangoFechas($fecha_inicio, $fecha_fin) {
        $sql = "SELECT 
                    p.id,
                    p.fecha_prestamo,
                    p.fecha_devolucion,
                    p.estado,
                    l.titulo as libro_titulo,
                    l.autor as libro_autor,
                    u.nombre as usuario_nombre,
                    u.tipo as usuario_tipo
                FROM prestamos p
                INNER JOIN libros l ON p.libro_id = l.id
                INNER JOIN usuarios u ON p.usuario_id = u.id
                WHERE p.fecha_prestamo BETWEEN ? AND ?
                ORDER BY p.fecha_prestamo DESC";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $prestamos = [];
        
        while ($prestamo = $resultado->fetch_assoc()) {
            $prestamos[] = $prestamo;
        }
        
        return $prestamos;
    }
    
    /**
     * Calcular multa por retraso
     * Multa: $1.00 por día después de 7 días
     * 
     * @param int $prestamo_id ID del préstamo
     * @return array Array con dias_retraso y multa
     */
    public function calcularMulta($prestamo_id) {
        $sql = "SELECT 
                    DATEDIFF(CURDATE(), fecha_prestamo) as dias_totales
                FROM prestamos
                WHERE id = ? AND estado = 'activo'";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $prestamo_id);
        $stmt->execute();
        
        $resultado = $stmt->get_result()->fetch_assoc();
        
        if (!$resultado) {
            return ['dias_retraso' => 0, 'multa' => 0.00];
        }
        
        $dias_totales = $resultado['dias_totales'];
        $dias_permitidos = 7;
        $dias_retraso = max(0, $dias_totales - $dias_permitidos);
        $multa = $dias_retraso * 1.00; // $1 por día
        
        return [
            'dias_retraso' => $dias_retraso,
            'multa' => number_format($multa, 2)
        ];
    }
    
    /**
     * Validar datos de préstamo antes de guardar
     * 
     * @param array $datos Datos a validar
     * @return array Array con errores (vacío si todo está bien)
     */
    public function validar($datos) {
        $errores = [];
        
        // Validar libro_id
        if (empty($datos['libro_id']) || !is_numeric($datos['libro_id'])) {
            $errores[] = "Debe seleccionar un libro válido";
        }
        
        // Validar usuario_id
        if (empty($datos['usuario_id']) || !is_numeric($datos['usuario_id'])) {
            $errores[] = "Debe seleccionar un usuario válido";
        }
        
        // Validar fecha_prestamo
        if (empty($datos['fecha_prestamo'])) {
            $errores[] = "Debe especificar la fecha del préstamo";
        }
        
        return $errores;
    }
}