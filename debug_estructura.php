<?php
require_once 'config/database.php';
echo '<pre>';
try {
    $pdo = getConnection();
    
    echo "=== TABLA USUARIOS ===\n";
    $stmt = $pdo->query('DESCRIBE usuarios');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        echo $col['Field'] . "\n";
    }
    
    echo "\n=== TABLA LIBROS ===\n";
    $stmt = $pdo->query('DESCRIBE libros');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        echo $col['Field'] . "\n";
    }
    
    echo "\n=== TABLA PRESTAMOS ===\n";
    $stmt = $pdo->query('DESCRIBE prestamos');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        echo $col['Field'] . "\n";
    }
    
    echo "\n=== EJEMPLO USUARIO ===\n";
    $stmt = $pdo->query('SELECT * FROM usuarios LIMIT 1');
    print_r($stmt->fetch(PDO::FETCH_ASSOC));
    
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}
echo '</pre>';
?>
