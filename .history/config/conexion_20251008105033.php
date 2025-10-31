<?php
class Conexion {
    private $host = "localhost";
    private $usuario = "root";
    private $password = "";
    private $baseDatos = "biblioteca_cif"; // ✅ corregido aquí
    private $conexion;

    public function conectar() {
        try {
            $this->conexion = new mysqli(
                $this->host,
                $this->usuario,
                $this->password,
                $this->baseDatos
            );

            if ($this->conexion->connect_error) {
                throw new Exception("Error de conexión: " . $this->conexion->connect_error);
            }

            $this->conexion->set_charset("utf8mb4");
            return $this->conexion;
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function cerrar() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
