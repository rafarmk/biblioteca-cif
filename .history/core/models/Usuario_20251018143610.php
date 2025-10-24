<?php
// models/Usuario.php

class Usuario {
    private $conexion;
    private $tabla = "usuarios";
    
    // Propiedades
    public $id;
    public $nombre;
    public $documento;
    public $carnet_policial; // Para policía y personal administrativo
    public $token_temporal;   // Para estudiantes y visitantes
    public $tipo;
    public $fecha_registro;
    
    // Constructor
    public function __construct($db) {
        $this->conexion = $db;
    }
    
    /**
     * Obtener todos los usuarios
     * @return array
     */
    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->tabla . " ORDER BY fecha_registro DESC";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener usuario por ID
     * @param int $id
     * @return array|false
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verificar si un documento ya existe
     * @param string $documento
     * @param int $excluirId (opcional, para edición)
     * @return bool
     */
    public function documentoExiste($documento, $excluirId = null) {
        if ($excluirId) {
            $query = "SELECT id FROM " . $this->tabla . " WHERE documento = :documento AND id != :id LIMIT 1";
            $stmt = $this->conexion->prepare($query);
            $stmt->bindParam(':id', $excluirId, PDO::PARAM_INT);
        } else {
            $query = "SELECT id FROM " . $this->tabla . " WHERE documento = :documento LIMIT 1";
            $stmt = $this->conexion->prepare($query);
        }
        
        $stmt->bindParam(':documento', $documento);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Verificar si un carnet policial ya existe
     * @param string $carnet
     * @param int $excluirId (opcional, para edición)
     * @return bool
     */
    public function carnetExiste($carnet, $excluirId = null) {
        if (empty($carnet)) return false;
        
        if ($excluirId) {
            $query = "SELECT id FROM " . $this->tabla . " WHERE carnet_policial = :carnet AND id != :id LIMIT 1";
            $stmt = $this->conexion->prepare($query);
            $stmt->bindParam(':id', $excluirId, PDO::PARAM_INT);
        } else {
            $query = "SELECT id FROM " . $this->tabla . " WHERE carnet_policial = :carnet LIMIT 1";
            $stmt = $this->conexion->prepare($query);
        }
        
        $stmt->bindParam(':carnet', $carnet);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Generar token temporal único
     * @return string
     */
    public function generarTokenTemporal() {
        do {
            // Generar token formato: TEMP-YYYYMMDD-XXXX
            $fecha = date('Ymd');
            $aleatorio = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $token = "TEMP-{$fecha}-{$aleatorio}";
            
            // Verificar que no exista
            $query = "SELECT id FROM " . $this->tabla . " WHERE token_temporal = :token LIMIT 1";
            $stmt = $this->conexion->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            
        } while ($stmt->rowCount() > 0);
        
        return $token;
    }
    
    /**
     * Validar formato de carnet según tipo de usuario
     * @param string $carnet
     * @param string $tipo
     * @return bool
     */
    public function validarFormatoCarnet($carnet, $tipo) {
        if (empty($carnet)) return false;
        
        switch($tipo) {
            case 'policia':
                // Formato: POL-2024-001 o POL-YYYY-XXX
                return preg_match('/^POL-\d{4}-\d{3,4}$/', $carnet);
                
            case 'administrativo':
                // Formato: PH00221 (PH seguido de 5 dígitos)
                return preg_match('/^PH\d{5}$/', $carnet);
                
            default:
                return false;
        }
    }
    
    /**
     * Crear nuevo usuario
     * @return bool
     */
    public function crear() {
        // Si es policía o administrativo, verificar carnet
        if (in_array($this->tipo, ['policia', 'administrativo'])) {
            if (empty($this->carnet_policial)) {
                return false; // Carnet requerido
            }
            // Validar formato de carnet
            if (!$this->validarFormatoCarnet($this->carnet_policial, $this->tipo)) {
                return false; // Formato inválido
            }
            if ($this->carnetExiste($this->carnet_policial)) {
                return false; // Carnet ya existe
            }
            // Para policía y admin, documento no es necesario
            $this->documento = null;
        }
        
        // Si es estudiante/visitante
        if ($this->tipo == 'estudiante') {
            // Generar token temporal SIEMPRE
            $this->token_temporal = $this->generarTokenTemporal();
            
            // DUI es opcional, si viene vacío se pone NULL
            if (empty($this->documento)) {
                $this->documento = null;
            } else {
                // Si tiene documento, verificar que no exista
                if ($this->documentoExiste($this->documento)) {
                    return false;
                }
            }
        }
        
        $query = "INSERT INTO " . $this->tabla . " 
                  (nombre, documento, carnet_policial, token_temporal, tipo) 
                  VALUES (:nombre, :documento, :carnet_policial, :token_temporal, :tipo)";
        
        $stmt = $this->conexion->prepare($query);
        
        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->documento = !empty($this->documento) ? htmlspecialchars(strip_tags($this->documento)) : null;
        $this->carnet_policial = !empty($this->carnet_policial) ? htmlspecialchars(strip_tags($this->carnet_policial)) : null;
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));
        
        // Vincular parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':documento', $this->documento);
        $stmt->bindParam(':carnet_policial', $this->carnet_policial);
        $stmt->bindParam(':token_temporal', $this->token_temporal);
        $stmt->bindParam(':tipo', $this->tipo);
        
        if ($stmt->execute()) {
            $this->id = $this->conexion->lastInsertId();
            return true;
        }
        
        return false;
    }
    
    /**
     * Actualizar usuario existente
     * @return bool
     */
    public function actualizar() {
        // Si es policía o administrativo, verificar carnet
        if (in_array($this->tipo, ['policia', 'administrativo'])) {
            if (empty($this->carnet_policial)) {
                return false; // Carnet requerido
            }
            // Validar formato de carnet
            if (!$this->validarFormatoCarnet($this->carnet_policial, $this->tipo)) {
                return false; // Formato inválido
            }
            if ($this->carnetExiste($this->carnet_policial, $this->id)) {
                return false; // Carnet ya existe
            }
            // Para policía y admin, documento no es necesario
            $this->documento = null;
        }
        
        // Si es estudiante/visitante
        if ($this->tipo == 'estudiante') {
            // DUI es opcional, si viene vacío se pone NULL
            if (empty($this->documento)) {
                $this->documento = null;
            } else {
                // Si tiene documento, verificar que no exista en otro usuario
                if ($this->documentoExiste($this->documento, $this->id)) {
                    return false;
                }
            }
        }
        
        $query = "UPDATE " . $this->tabla . " 
                  SET nombre = :nombre,
                      documento = :documento,
                      carnet_policial = :carnet_policial,
                      tipo = :tipo
                  WHERE id = :id";
        
        $stmt = $this->conexion->prepare($query);
        
        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->documento = !empty($this->documento) ? htmlspecialchars(strip_tags($this->documento)) : null;
        $this->carnet_policial = !empty($this->carnet_policial) ? htmlspecialchars(strip_tags($this->carnet_policial)) : null;
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Vincular parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':documento', $this->documento);
        $stmt->bindParam(':carnet_policial', $this->carnet_policial);
        $stmt->bindParam(':tipo', $this->tipo);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }
    
    /**
     * Verificar si un usuario tiene préstamos activos
     * @return bool
     */
    public function tienePrestamosActivos() {
        $query = "SELECT COUNT(*) as total FROM prestamos 
                  WHERE usuario_id = :id AND estado = 'activo'";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] > 0;
    }
    
    /**
     * Eliminar usuario
     * @return bool
     */
    public function eliminar() {
        // No permitir eliminar si tiene préstamos activos
        if ($this->tienePrestamosActivos()) {
            return false;
        }
        
        $query = "DELETE FROM " . $this->tabla . " WHERE id = :id";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Buscar usuarios por nombre o documento
     * @param string $termino
     * @return array
     */
    public function buscar($termino) {
        $query = "SELECT * FROM " . $this->tabla . " 
                  WHERE nombre LIKE :termino OR documento LIKE :termino 
                  ORDER BY nombre ASC";
        
        $stmt = $this->conexion->prepare($query);
        $terminoBusqueda = "%{$termino}%";
        $stmt->bindParam(':termino', $terminoBusqueda);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener estadísticas de préstamos del usuario
     * @return array
     */
    public function obtenerEstadisticas() {
        $query = "SELECT 
                    COUNT(*) as total_prestamos,
                    SUM(CASE WHEN estado = 'activo' THEN 1 ELSE 0 END) as prestamos_activos,
                    SUM(CASE WHEN estado = 'devuelto' THEN 1 ELSE 0 END) as prestamos_devueltos
                  FROM prestamos 
                  WHERE usuario_id = :id";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener tipos de usuario disponibles
     * @return array
     */
    public static function obtenerTiposUsuario() {
        return [
            'policia' => 'Policía',
            'administrativo' => 'Personal Administrativo',
            'estudiante' => 'Estudiante'
        ];
    }
}
?>