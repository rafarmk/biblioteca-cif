<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1>Test Editar Usuario</h1>';

try {
    require_once 'config/Database.php';
    require_once 'models/Usuario.php';
    
    $database = new Database();
    $db = $database->getConnection();
    $usuario = new Usuario($db);
    
    $usuario->id = 1;
    echo '<p>Leyendo usuario ID: 1</p>';
    
    if ($usuario->leerUno()) {
        echo '<p style=\"color:green\">Usuario leido OK</p>';
        
        // Simular actualización
        $usuario->nombre = 'Test Edit ' . date('His');
        
        // Probar buscar por email
        echo '<p>Probando buscarPorEmail...</p>';
        $emailTest = $usuario->buscarPorEmail($usuario->email);
        
        if ($emailTest) {
            echo '<p style=\"color:green\">buscarPorEmail funciona OK</p>';
            echo '<pre>Email encontrado: ' . print_r($emailTest, true) . '</pre>';
        } else {
            echo '<p style=\"color:red\">buscarPorEmail NO funciona</p>';
        }
        
        echo '<p>Intentando actualizar...</p>';
        if ($usuario->actualizar()) {
            echo '<p style=\"color:green\">Actualización OK</p>';
        } else {
            echo '<p style=\"color:red\">Error al actualizar</p>';
        }
        
    } else {
        echo '<p style=\"color:red\">No se pudo leer usuario</p>';
    }
    
} catch (Exception $e) {
    echo '<p style=\"color:red\">ERROR: ' . $e->getMessage() . '</p>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
?>
