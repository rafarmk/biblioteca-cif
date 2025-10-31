<?php
// index.php - Front Controller

// ✅ Mostrar errores en pantalla
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DEBUG: Ver qué está recibiendo
echo "<pre>";
echo "REQUEST_URI original: " . $_SERVER['REQUEST_URI'] . "\n";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";

// Obtener la ruta solicitada
$uri = trim($_SERVER['REQUEST_URI'], '/');
echo "URI después de trim: " . $uri . "\n";

$base = 'biblioteca-cif'; // nombre de tu carpeta en Laragon

// Eliminar base del URI
if (strpos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base));
    $uri = trim($uri, '/');
}
echo "URI después de quitar base: " . $uri . "\n";

// Eliminar query string si existe
if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}
echo "URI después de quitar query: " . $uri . "\n";

// Si la URI está vacía, mostrar página de inicio
if (empty($uri)) {
    echo "URI vacía, redirigiendo a home\n";
    require_once 'controllers/BibliotecacifController.php';
    $controller = new BibliotecacifController();
    $controller->index();
    exit;
}

$segments = explode('/', $uri);
echo "Segments raw: " . print_r($segments, true) . "\n";

// Eliminar segmentos vacíos
$segments = array_filter($segments);
$segments = array_values($segments); // Reindexar
echo "Segments filtrados: " . print_r($segments, true) . "\n";

// Determinar controlador y método
$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'BibliotecacifController';
$method = $segments[1] ?? 'index';

echo "Controlador a cargar: " . $controllerName . "\n";
echo "Método a llamar: " . $method . "\n";
echo "</pre>";
die();