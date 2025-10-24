<?php
// Archivo: config/conexion.example.php
// INSTRUCCIONES: Copia este archivo como 'conexion.php' y configura tus credenciales

$host = 'localhost';
$usuario = 'root';
$contrasena = ''; // Ingresa tu contraseña de MySQL aquí
$base = 'bibloteca_cif';

// Crear conexión
$conn = new mysqli($host, $usuario, $contrasena, $base);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
