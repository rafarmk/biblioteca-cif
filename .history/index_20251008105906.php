<?php
// index.php - Front Controller

// Obtener la ruta solicitada
$uri = trim($_SERVER['REQUEST_URI'], '/');
$base = 'biblioteca-cif'; // nombre de tu carpeta en Laragon
$uri = str_replace($base . '/', '', $uri); // eliminar base del URI

$segments = explode('/', $uri);

// ✅ Paso 1: Eliminar guiones del nombre del controlador
$segments[0] = str_replace('-', '', $segments[0]);

// Determinar controlador y método
$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'HomeController';

// Ruta al controlador
$controllerPath = "controllers/$controllerName.php";

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName();

    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        echo "❌ Método '$method' no encontrado en $controllerName.";
    }
} else {
    echo "❌ Controlador '$controllerName' no encontrado.";
}
