<?php
// Mostrar errores para depuración (desactivar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener controlador y acción desde la URL con valores por defecto
$controlador = $_GET['c'] ?? 'libro';
$accion = $_GET['a'] ?? 'form';

// Sanitizar entradas para evitar inyección de código
$controlador = preg_replace('/[^a-zA-Z0-9_-]/', '', $controlador);
$accion = preg_replace('/[^a-zA-Z0-9_-]/', '', $accion);

// Lista blanca de controladores permitidos (seguridad)
$controladoresPermitidos = ['libro'];

if (!in_array($controlador, $controladoresPermitidos)) {
    die("<p>✗ Controlador no permitido.</p>");
}

// Construir nombre de clase y archivo
$archivoControlador = "controladores/" . ucfirst($controlador) . "Controller.php";
$nombreClase = ucfirst($controlador) . "Controller";

// Verificar que el archivo exista
if (file_exists($archivoControlador)) {
    require_once $archivoControlador;

    // Verificar que la clase exista
    if (class_exists($nombreClase)) {
        $objeto = new $nombreClase();

        // Verificar que el método exista
        if (method_exists($objeto, $accion)) {
            $objeto->$accion();
        } else {
            echo "<p>✗ Método '$accion' no encontrado en el controlador '$nombreClase'.</p>";
        }
    } else {
        echo "<p>✗ Clase '$nombreClase' no encontrada.</p>";
    }
} else {
    echo "<p>✗ Controlador '$archivoControlador' no encontrado.</p>";
}
?>