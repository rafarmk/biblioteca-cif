<?php
// Archivo: config/conexion.php

$host = 'localhost';
$usuario = 'root';
$contrasena = ''; // o la que uses en Laragon/XAMPP
$base = 'bibloteca_cif';
// Crear conexión
$conn = new mysqli($host, $usuario, $contrasena, $base);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
