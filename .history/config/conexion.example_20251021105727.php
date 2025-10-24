<?php
// Configuración de la base de datos
// INSTRUCCIONES: Copia este archivo como 'conexion.php' y configura tus credenciales

define('DB_HOST', 'localhost');
define('DB_NAME', 'biblioteca_cif_usuarios');
define('DB_USER', 'root');              // Cambia por tu usuario de MySQL
define('DB_PASS', '');                  // Cambia por tu contraseña de MySQL
define('DB_CHARSET', 'utf8mb4');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>