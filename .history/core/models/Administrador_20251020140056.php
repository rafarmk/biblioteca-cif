<?php
/**
 * Modelo: Administrador
 * 
 * Descripción: Maneja la autenticación y gestión de administradores
 * Autor: José Raphael Ernesto Pérez Hernández
 * Fecha: 19 de Octubre, 2025
 */

class Administrador {
    private $conexion;
    
    public $id;
    public $usuario;
    public $password;
    public $nombre;
    public $email;
    public $rol;
    public $estado;
    
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    
    /**
     * Verificar credenciales de login
     */
    public function login($usuario, $password) {
        $sql = "SELECT * FROM administradores 
                WHERE usuario = ? AND estado = 'activo' 
                LIMIT 1";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$usuario]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            // Actualizar último acceso
            $this->actualizarUltimoAcceso($admin['id']);
            
            // Retornar datos del administrador
            return $admin;
        }
        
        return false;
    }
    
    /**
     * Obtener administrador por ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM administradores WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener administrador por usuario
     */
    public function obtenerPorUsuario($usuario) {
        $sql = "SELECT * FROM administradores WHERE usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crear nuevo administrador
     */
    public function crear($datos) {
        // Verificar que el usuario no exista
        if ($this->obtenerPorUsuario($datos['usuario'])) {
            return false;
        }
        
        $sql = "INSERT INTO administradores (usuario, password, nombre, email, rol) 
                VALUES (?, ?, ?, ?, ?)";
        
        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);
        
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            $datos['usuario'],
            $passwordHash,
            $datos['nombre'],
            $datos['email'] ?? null,
            $datos['rol'] ?? 'bibliotecario'
        ]);
    }
    
    /**
     * Actualizar administrador
     */
    public function actualizar($id, $datos) {
        $sql = "UPDATE administradores 
                SET nombre = ?, email = ?, rol = ?";
        
        $params = [
            $datos['nombre'],
            $datos['email'] ?? null,
            $datos['rol'] ?? 'bibliotecario'
        ];
        
        // Si se proporciona nueva contraseña
        if (!empty($datos['password'])) {
            $sql .= ", password = ?";
            $params[] = password_hash($datos['password'], PASSWORD_DEFAULT);
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Cambiar estado del administrador
     */
    public function cambiarEstado($id, $estado) {
        $sql = "UPDATE administradores SET estado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$estado, $id]);
    }
    
    /**
     * Actualizar último acceso
     */
    private function actualizarUltimoAcceso($id) {
        $sql = "UPDATE administradores 
                SET ultimo_acceso = CURRENT_TIMESTAMP 
                WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Obtener todos los administradores
     */
    public function obtenerTodos() {
        $sql = "SELECT id, usuario, nombre, email, rol, estado, 
                       ultimo_acceso, fecha_creacion 
                FROM administradores 
                ORDER BY id DESC";
        
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verificar si es el último administrador activo
     */
    public function esUltimoAdminActivo($id) {
        $sql = "SELECT COUNT(*) as total 
                FROM administradores 
                WHERE rol = 'admin' AND estado = 'activo' AND id != ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $resultado['total'] == 0;
    }
    
    /**
     * Cambiar contraseña
     */
    public function cambiarPassword($id, $passwordActual, $passwordNueva) {
        // Verificar contraseña actual
        $admin = $this->obtenerPorId($id);
        
        if (!$admin || !password_verify($passwordActual, $admin['password'])) {
            return false;
        }
        
        // Actualizar con nueva contraseña
        $sql = "UPDATE administradores SET password = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $passwordHash = password_hash($passwordNueva, PASSWORD_DEFAULT);
        
        return $stmt->execute([$passwordHash, $id]);
    }
}
?>