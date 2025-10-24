<?php
// config/conexion.php

class Conexion {
    private $host = "localhost";
    private $usuario = "root";
    private $password = "";
    private $baseDatos = "biblioteca_cif_usuarios";
    private $conexion;

    public function conectar() {
        $this->conexion = null;
        
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->baseDatos . ";charset=utf8mb4";
            $this->conexion = new PDO($dsn, $this->usuario, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
        
        return $this->conexion;
    }

    public function cerrar() {
        $this->conexion = null;
    }
}
?>