<?php
require_once 'config/Database.php';

class Administrador {
    private $conn;
    private $table = 'administradores';

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $rol;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        
        return false;
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table . " (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)";
        $stmt = $this->conn->prepare($query);
        
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':rol', $this->rol);
        
        return $stmt->execute();
    }

    public function existeEmail($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
}