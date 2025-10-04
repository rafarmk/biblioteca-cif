<?php
// vistas/listar-libros.php
require_once __DIR__ . '/../config/conexion.php';

// Consultar todos los libros
$sql = "SELECT * FROM libros ORDER BY fecha_registro DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Libros - Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a href="../index.php" class="navbar-brand">📚 Biblioteca CIF</a>
        </div>
    </nav>
    
    <div class="container mt-5">
        <h2 class="mb-4">📋 Lista de Libros Registrados</h2>
        
        <a href="form-Libro.php" class="btn btn-primary mb-3">➕ Agregar Nuevo Libro</a>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Categoría</th>
                            <th>Año</th>
                            <th>Disponible</th>
                            <th>Fecha Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($libro = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $libro['id'] ?></td>
                            <td><?= htmlspecialchars($libro['titulo']) ?></td>
                            <td><?= htmlspecialchars($libro['autor']) ?></td>
                            <td><?= htmlspecialchars($libro['categoria']) ?></td>
                            <td><?= $libro['anio'] ?></td>
                            <td>
                                <?php if($libro['disponible']): ?>
                                    <span class="badge bg-success">Sí</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">No</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($libro['fecha_registro'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                No hay libros registrados aún. <a href="form-Libro.php">Registra el primero</a>.
            </div>
        <?php endif; ?>
        
        <?php $conn->close(); ?>
    </div>
</body>
</html>
