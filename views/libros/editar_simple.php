<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Test Editar Libro</title>
</head>
<body>
    <h1>Formulario de Edición</h1>
    
    <?php if (isset($libro)): ?>
        <form action="index.php?ruta=libros&accion=editar" method="POST">
            <input type="hidden" name="id" value="<?php echo $libro['id']; ?>">
            
            <label>Título:</label>
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($libro['titulo']); ?>" required><br><br>
            
            <label>Autor:</label>
            <input type="text" name="autor" value="<?php echo htmlspecialchars($libro['autor']); ?>" required><br><br>
            
            <label>ISBN:</label>
            <input type="text" name="isbn" value="<?php echo htmlspecialchars($libro['isbn'] ?? ''); ?>"><br><br>
            
            <label>Editorial:</label>
            <input type="text" name="editorial" value="<?php echo htmlspecialchars($libro['editorial'] ?? ''); ?>"><br><br>
            
            <label>Cantidad Total:</label>
            <input type="number" name="cantidad_total" value="<?php echo htmlspecialchars($libro['cantidad_total'] ?? ''); ?>" required><br><br>
            
            <label>Descripción:</label>
            <textarea name="descripcion"><?php echo htmlspecialchars($libro['descripcion'] ?? ''); ?></textarea><br><br>
            
            <button type="submit">Actualizar Libro</button>
        </form>
    <?php else: ?>
        <p>Libro no encontrado</p>
    <?php endif; ?>
</body>
</html>
