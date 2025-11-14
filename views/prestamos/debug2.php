# Crear debug correcto con la clase Database
@"
<?php
require_once 'config/Database.php';

echo '<pre>';
try {
    `$database = new Database();
    `$pdo = `$database->getConnection();
    
    echo "=== TABLA USUARIOS ===\n";
    `$stmt = `$pdo->query('DESCRIBE usuarios');
    foreach (`$stmt->fetchAll(PDO::FETCH_ASSOC) as `$col) {
        echo `$col['Field'] . "\n";
    }
    
    echo "\n=== TABLA LIBROS ===\n";
    `$stmt = `$pdo->query('DESCRIBE libros');
    foreach (`$stmt->fetchAll(PDO::FETCH_ASSOC) as `$col) {
        echo `$col['Field'] . "\n";
    }
    
    echo "\n=== TABLA PRESTAMOS ===\n";
    `$stmt = `$pdo->query('DESCRIBE prestamos');
    foreach (`$stmt->fetchAll(PDO::FETCH_ASSOC) as `$col) {
        echo `$col['Field'] . "\n";
    }
    
    echo "\n=== EJEMPLO USUARIO ===\n";
    `$stmt = `$pdo->query('SELECT * FROM usuarios LIMIT 1');
    print_r(`$stmt->fetch(PDO::FETCH_ASSOC));
    
    echo "\n=== EJEMPLO LIBRO ===\n";
    `$stmt = `$pdo->query('SELECT * FROM libros LIMIT 1');
    print_r(`$stmt->fetch(PDO::FETCH_ASSOC));
    
    echo '</pre>';
} catch (Exception `$e) {
    echo 'ERROR: ' . `$e->getMessage();
}
?>
"@ | Out-File -FilePath "debug2.php" -Encoding UTF8

# Abrir
start http://localhost/biblioteca-cif-limpio/debug2.php