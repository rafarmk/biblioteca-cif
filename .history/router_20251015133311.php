<?php
// Router para servidor PHP integrado
if (file_exists(__DIR__ . $_SERVER['REQUEST_URI'])) {
    return false; // Servir el archivo directamente
} else {
    include_once 'index.php'; // Redirigir todo a index.php
}