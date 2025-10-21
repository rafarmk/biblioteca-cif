<?php
/**
 * Modelo: Usuario
 * 
 * Descripción: Gestiona todas las operaciones relacionadas con usuarios
 * (estudiantes, docentes, administrativos)
 * Autor: José Raphael Ernesto Pérez Hernández
 * Fecha: 12 de Octubre, 2025
 */

class Usuario {
    private $conexion;
    
    /**
     * Constructor
     * Recibe la conexión a la base de datos
     */
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    
    /**
     * Obtener todos los usuarios
     * 
     * @return array Lista de usuarios
     */
    public function obtenerTodos() {
        $sql = "SELECT * FROM usuarios ORDER BY nombre ASC";
        $resultado = $this->conexion->query($sql);
        
        $usuarios = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($usuario = $resultado->fetch_assoc()) {
                $usuarios[] = $usuario;
            }
        }
        
        return $usuarios;
    }
    
    /**
     * Obtener un usuario por su ID
     * 
     * @param int $id ID del usuario
     * @return array|null Datos del usuario o null si no existe
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
    
    /**
     * Obtener un usuario por su documento
     * 
     * @param string $documento Documento del usuario
     * @return array|null Datos del usuario o null si no existe
     */
    public function obtenerPorDocumento($documento) {
        $sql = "SELECT * FROM usuarios WHERE documento = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $documento);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
    
    /**
     * Crear un nuevo usuario
     * 
     * @param array $datos Datos del usuario (nombre, documento, tipo)
     * @return bool|string True si se creó, mensaje de error si falló
     */
    public function crear($datos) {
        // Verificar que el documento no exista
        if ($this->existeDocumento($datos['documento'])) {
            return "El documento ya está registrado";
        }
        
        $sql = "INSERT INTO usuarios (nombre, documento, tipo) 
                VALUES (?, ?, ?)";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sss",
            $datos['nombre'],
            $datos['documento'],
            $datos['tipo']
        );
        
        if ($stmt->execute()) {
            return true;
        }
        
        return "Error al registrar el usuario";
    }
    
    /**
     * Actualizar un usuario existente
     * 
     * @param int $id ID del usuario a actualizar
     * @param array $datos Nuevos datos del usuario
     * @return bool|string True si se actualizó, mensaje de error si falló
     */
    public function actualizar($id, $datos) {
        // Verificar que el documento no esté usado por otro usuario
        $usuarioExistente = $this->obtenerPorDocumento($datos['documento']);
        if ($usuarioExistente && $usuarioExistente['id'] != $id) {
            return "El documento ya está registrado por otro usuario";
        }
        
        $sql = "UPDATE usuarios 
                SET nombre = ?, documento = ?, tipo = ? 
                WHERE id = ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssi",
            $datos['nombre'],
            $datos['documento'],
            $datos['tipo'],
            $id
        );
        
        if ($stmt->execute()) {
            return true;
        }
        
        return "Error al actualizar el usuario";
    }
    
    /**
     * Eliminar un usuario
     * NOTA: Solo se puede eliminar si no tiene préstamos activos
     * 
     * @param int $id ID del usuario a eliminar
     * @return bool|string True si se eliminó, mensaje de error si falló
     */
    public function eliminar($id) {
        // Verificar que no tenga préstamos activos
        if ($this->tienePrestamosActivos($id)) {
            return "No se puede eliminar. El usuario tiene préstamos activos.";
        }
        
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return "Error al eliminar el usuario";
    }
    
    /**
     * Verificar si un documento ya está registrado
     * 
     * @param string $documento Documento a verificar
     * @return bool True si existe, false si no
     */
    private function existeDocumento($documento) {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE documento = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $documento);
        $stmt->execute();
        
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado['total'] > 0;
    }
    
    /**
     * Verificar si un usuario tiene préstamos activos
     * 
     * @param int $usuario_id ID del usuario
     * @return bool True si tiene préstamos activos, false si no
     */
    private function tienePrestamosActivos($usuario_id) {
        $sql = "SELECT COUNT(*) as total 
                FROM prestamos 
                WHERE usuario_id = ? AND estado = 'activo'";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado['total'] > 0;
    }
    
    /**
     * Obtener usuarios por tipo
     * 
     * @param string $tipo Tipo de usuario (estudiante, docente, administrativo)
     * @return array Lista de usuarios de ese tipo
     */
    public function obtenerPorTipo($tipo) {
        $sql = "SELECT * FROM usuarios WHERE tipo = ? ORDER BY nombre";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $tipo);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $usuarios = [];
        
        while ($usuario = $resultado->fetch_assoc()) {
            $usuarios[] = $usuario;
        }
        
        return $usuarios;
    }
    
    /**
     * Buscar usuarios por nombre o documento
     * 
     * @param string $termino Término de búsqueda
     * @return array Lista de usuarios encontrados
     */
    public function buscar($termino) {
        $sql = "SELECT * FROM usuarios 
                WHERE nombre LIKE ? OR documento LIKE ?
                ORDER BY nombre";
        
        $busqueda = "%{$termino}%";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $busqueda, $busqueda);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $usuarios = [];
        
        while ($usuario = $resultado->fetch_assoc()) {
            $usuarios[] = $usuario;
        }
        
        return $usuarios;
    }
    
    /**
     * Contar total de usuarios
     * 
     * @return int Total de usuarios
     */
    public function contarTotal() {
        $sql = "SELECT COUNT(*) as total FROM usuarios";
        $resultado = $this->conexion->query($sql);
        $datos = $resultado->fetch_assoc();
        
        return $datos['total'];
    }
    
    /**
     * Contar usuarios por tipo
     * 
     * @return array Array asociativo con el conteo por tipo
     */
    public function contarPorTipo() {
        $sql = "SELECT tipo, COUNT(*) as total 
                FROM usuarios 
                GROUP BY tipo";
        
        $resultado = $this->conexion->query($sql);
        $conteo = [
            'estudiante' => 0,
            'docente' => 0,
            'administrativo' => 0
        ];
        
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $conteo[$fila['tipo']] = $fila['total'];
            }
        }
        
        return $conteo;
    }
    
    /**
     * Obtener historial de préstamos de un usuario
     * 
     * @param int $usuario_id ID del usuario
     * @return array Lista de préstamos del usuario
     */
    public function obtenerHistorialPrestamos($usuario_id) {
        $sql = "SELECT 
                    p.id,
                    l.titulo as libro,
                    l.autor,
                    p.fecha_prestamo,
                    p.fecha_devolucion,
                    p.estado
                FROM prestamos p
                INNER JOIN libros l ON p.libro_id = l.id
                WHERE p.usuario_id = ?
                ORDER BY p.fecha_prestamo DESC";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $prestamos = [];
        
        while ($prestamo = $resultado->fetch_assoc()) {
            $prestamos[] = $prestamo;
        }
        
        return $prestamos;
    }
    
    /**
     * Obtener préstamos activos de un usuario
     * 
     * @param int $usuario_id ID del usuario
     * @return array Lista de préstamos activos
     */
    public function obtenerPrestamosActivos($usuario_id) {
        $sql = "SELECT 
                    p.id,
                    l.titulo as libro,
                    l.autor,
                    p.fecha_prestamo,
                    DATEDIFF(CURDATE(), p.fecha_prestamo) as dias_transcurridos
                FROM prestamos p
                INNER JOIN libros l ON p.libro_id = l.id
                WHERE p.usuario_id = ? AND p.estado = 'activo'
                ORDER BY p.fecha_prestamo ASC";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $prestamos = [];
        
        while ($prestamo = $resultado->fetch_assoc()) {
            $prestamos[] = $prestamo;
        }
        
        return $prestamos;
    }
    
    /**
     * Verificar si un usuario puede pedir más libros prestados
     * Límite: 3 libros simultáneos
     * 
     * @param int $usuario_id ID del usuario
     * @return bool True si puede pedir más, false si alcanzó el límite
     */
    public function puedePedirPrestado($usuario_id) {
        $sql = "SELECT COUNT(*) as total 
                FROM prestamos 
                WHERE usuario_id = ? AND estado = 'activo'";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        
        $resultado = $stmt->get_result()->fetch_assoc();
        
        // Límite: 3 libros simultáneos
        return $resultado['total'] < 3;
    }
    
    /**
     * Validar datos de usuario antes de guardar
     * 
     * @param array $datos Datos a validar
     * @return array Array con errores (vacío si todo está bien)
     */
    public function validar($datos) {
        $errores = [];
        
        // Validar nombre
        if (empty($datos['nombre']) || strlen(trim($datos['nombre'])) < 3) {
            $errores[] = "El nombre debe tener al menos 3 caracteres";
        }
        
        // Validar documento
        if (empty($datos['documento']) || strlen(trim($datos['documento'])) < 5) {
            $errores[] = "El documento debe tener al menos 5 caracteres";
        }
        
        // Validar tipo
        $tipos_validos = ['estudiante', 'docente', 'administrativo'];
        if (empty($datos['tipo']) || !in_array($datos['tipo'], $tipos_validos)) {
            $errores[] = "El tipo de usuario no es válido";
        }
        
        return $errores;
    }
}