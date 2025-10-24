<?php
// index.php - Página principal
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">📚 Biblioteca CIF</span>
        </div>
    </nav>
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="mb-4">Bienvenido al Sistema de Biblioteca</h2>
                <div class="list-group">
                    <a href="vistas/form-Libro.php" class="list-group-item list-group-item-action">
                        📖 Registrar Nuevo Libro
                    </a>
                    <a href="vistas/listar-libros.php" class="list-group-item list-group-item-action">
                        📋 Ver Todos los Libros
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

