<?php
session_start();

echo "<h1>Test de Usuarios</h1>";

echo "<h3>1. Verificando sesión...</h3>";
echo "ID: " . ($_SESSION['admin_id'] ?? 'NO EXISTE') . "<br>";
echo "Nombre: " . ($_SESSION['admin_nombre'] ?? 'NO EXISTE') . "<br>";

echo "<h3>2. Probando conexión...</h3>";
require_once 'config/conexion.php';
$database = new Conexion();
$db = $database->conectar();
echo "Conexión: " . ($db ? "✅ OK" : "❌ FALLO") . "<br>";

echo "<h3>3. Probando modelo Usuario...</h3>";
require_once 'core/models/Usuario.php';
$usuario = new Usuario($db);
echo "Modelo Usuario: ✅ Cargado<br>";

echo "<h3>4. Obteniendo usuarios...</h3>";
try {
    $usuarios = $usuario->obtenerTodos();
    echo "Total usuarios: " . count($usuarios) . "<br>";
    echo "<pre>";
    print_r($usuarios);
    echo "</pre>";
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "<br>";
}

echo "<h3>5. Probando vista...</h3>";
if (file_exists('views/usuarios/index.php')) {
    echo "✅ La vista existe<br>";
} else {
    echo "❌ La vista NO existe<br>";
}
?>
```

Guarda (**Ctrl + S**) y luego abre en el navegador:
```
http://localhost:8080/test_usuarios.php