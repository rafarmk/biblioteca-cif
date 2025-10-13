<?php
// index.php - Front Controller

// ✅ Mostrar errores en pantalla
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener la ruta solicitada
$uri = trim($_SERVER['REQUEST_URI'], '/');
$base = 'biblioteca-cif'; // nombre de tu carpeta en Laragon

// Eliminar base del URI
if (strpos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base));
    $uri = trim($uri, '/');
}

// Eliminar query string si existe
if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}

// Si la URI está vacía, mostrar página de inicio
if (empty($uri)) {
    require_once 'controllers/BibliotecacifController.php';
    $controller = new BibliotecacifController();
    $controller->index();
    exit;
}

$segments = explode('/', $uri);

// Eliminar segmentos vacíos
$segments = array_filter($segments);
$segments = array_values($segments); // Reindexar

// Determinar controlador y método
$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'BibliotecacifController';
$method = $segments[1] ?? 'index';

// Ruta al controlador
$controllerPath = "controllers/$controllerName.php";

if (file_exists($controllerPath)) {
    require_once $controllerPath;

    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        if (method_exists($controller, $method)) {
            $controller->$method();
        } else {
            echo "❌ Método '$method' no encontrado en $controllerName.";
        }
    } else {
        echo "❌ Clase '$controllerName' no definida en el archivo.";
    }
} else {
    echo "❌ Controlador '$controllerName' no encontrado.";
}