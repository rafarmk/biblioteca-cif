<?php
// router.php — Router inteligente para el servidor embebido de PHP

if (php_sapi_name() === 'cli-server') {
    // Obtiene la ruta solicitada sin parámetros (ej: /css/estilos.css)
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = __DIR__ . $uri;

    // Si el archivo existe físicamente (CSS, JS, imagen, etc.), se sirve directamente
    if (is_file($file)) {
        return false;
    }

    // De lo contrario, todo se enruta hacia index.php
    require __DIR__ . '/index.php';
}
