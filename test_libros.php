<?php
session_start();

echo "<h1>Test de Acceso a Libros</h1>";
echo "<h2>Estado de Sesión:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>Verificación:</h2>";
if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
    echo "<p style='color:green;'>✅ Sesión válida - DEBERÍA poder acceder a libros</p>";
    echo "<p><a href='index.php?ruta=libros'>Click aquí para ir a Libros</a></p>";
} else {
    echo "<p style='color:red;'>❌ Sesión inválida</p>";
}

echo "<hr>";
echo "<h2>Intento directo de cargar LibroController:</h2>";
try {
    require_once 'controllers/LibroController.php';
    $controller = new LibroController();
    echo "<p style='color:green;'>✅ LibroController existe y se puede instanciar</p>";
    echo "<p>Llamando a index()...</p>";
    $controller->index();
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>