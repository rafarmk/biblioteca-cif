<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'bibloteca_cif';
    private $username = 'root';
    private $password = '';
    private $conn = null;

    public function getConnection() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            die();
        }
        return $this->conn;
    }
}
