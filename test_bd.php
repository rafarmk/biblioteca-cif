<?php
require_once 'config/conexion.php';

$db = new Conexion();
$conn = $db->conectar();

echo "<h2>Probando conexión...</h2>";

if($conn) {
    echo "✅ Conexión exitosa<br><br>";
    
    // Verificar que existe la base de datos
    $query = "SHOW TABLES";
    $stmt = $conn->query($query);
    $tablas = $stmt->fetchAll();
    
    echo "<h3>Tablas en la base de datos:</h3>";
    echo "<ul>";
    foreach($tablas as $tabla) {
        echo "<li>" . $tabla[0] . "</li>";
    }
    echo "</ul>";
    
} else {
    echo "❌ Error en la conexión";
}
?>