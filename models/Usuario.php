<?php
require_once 'config/Database.php';

class Usuario {
    private $conn;
    private $table = 'usuarios';
    
    public $id;
    public $nombre;
    public $email;
    public $telefono;
    public $dui;
    public $direccion;
    public $tipo_usuario;
    public $password;
    public $estado;
    public $puede_prestar;
    public $dias_max_prestamo;
    public $max_libros_simultaneos;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function listar() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function listarActivos() {
        $query = "SELECT * FROM " . $this->table . " WHERE estado = 'activo' ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, email, telefono, dui, direccion, tipo_usuario, password, estado, puede_prestar, dias_max_prestamo, max_libros_simultaneos) 
                  VALUES 
                  (:nombre, :email, :telefono, :dui, :direccion, :tipo_usuario, :password, :estado, :puede_prestar, :dias_max, :max_libros)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':dui', $this->dui);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':tipo_usuario', $this->tipo_usuario);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':puede_prestar', $this->puede_prestar);
        $stmt->bindParam(':dias_max', $this->dias_max_prestamo);
        $stmt->bindParam(':max_libros', $this->max_libros_simultaneos);
        
        return $stmt->execute();
    }
    
    public function actualizar() {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, 
                      email = :email, 
                      telefono = :telefono, 
                      dui = :dui,
                      direccion = :direccion,
                      tipo_usuario = :tipo_usuario,
                      estado = :estado,
                      puede_prestar = :puede_prestar,
                      dias_max_prestamo = :dias_max,
                      max_libros_simultaneos = :max_libros
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':dui', $this->dui);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':tipo_usuario', $this->tipo_usuario);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':puede_prestar', $this->puede_prestar);
        $stmt->bindParam(':dias_max', $this->dias_max_prestamo);
        $stmt->bindParam(':max_libros', $this->max_libros_simultaneos);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }
    
    public function eliminar($id) {
        $query = "UPDATE " . $this->table . " SET estado = 'inactivo' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}