<?php
// views/usuarios/editar.php
if (!isset($usuario)) {
    echo "<p>❌ Usuario no encontrado</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <h2>Editar Usuario: <?php echo htmlspecialchars($usuario['nombre']); ?></h2>
    <form action="?ruta=usuarios/editar&id=<?php echo $usuario['id']; ?>" method="POST">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Documento</label>
            <input type="text" name="documento" class="form-control" value="<?php echo htmlspecialchars($usuario['documento']); ?>">
        </div>
        <div class="mb-3">
            <label>Tipo</label>
            <select name="tipo" class="form-control">
                <option value="policia" <?php echo $usuario['tipo']=='policia'?'selected':''; ?>>Policía</option>
                <option value="administrativo" <?php echo $usuario['tipo']=='administrativo'?'selected':''; ?>>Administrativo</option>
                <option value="estudiante" <?php echo $usuario['tipo']=='estudiante'?'selected':''; ?>>Estudiante</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="?ruta=usuarios" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>
