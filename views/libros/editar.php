<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro - Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php require_once 'views/layouts/navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0"><i class="fas fa-book-open"></i> Editar Libro</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="index.php?ruta=libros&accion=editar">
                            <input type="hidden" name="id" value="<?php echo $libroData['id']; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Título *</label>
                                    <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($libroData['titulo']); ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Autor *</label>
                                    <input type="text" name="autor" class="form-control" value="<?php echo htmlspecialchars($libroData['autor']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ISBN</label>
                                    <input type="text" name="isbn" class="form-control" value="<?php echo htmlspecialchars($libroData['isbn']); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Editorial</label>
                                    <input type="text" name="editorial" class="form-control" value="<?php echo htmlspecialchars($libroData['editorial']); ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Año de Publicación</label>
                                    <input type="number" name="anio_publicacion" class="form-control" value="<?php echo $libroData['anio_publicacion']; ?>" min="1900" max="<?php echo date('Y'); ?>">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Categoría</label>
                                    <select name="categoria" class="form-select">
                                        <option value="">Seleccione...</option>
                                        <option value="Derecho" <?php echo ($libroData['categoria'] == 'Derecho') ? 'selected' : ''; ?>>Derecho</option>
                                        <option value="Criminología" <?php echo ($libroData['categoria'] == 'Criminología') ? 'selected' : ''; ?>>Criminología</option>
                                        <option value="Procedimientos" <?php echo ($libroData['categoria'] == 'Procedimientos') ? 'selected' : ''; ?>>Procedimientos</option>
                                        <option value="Investigación" <?php echo ($libroData['categoria'] == 'Investigación') ? 'selected' : ''; ?>>Investigación</option>
                                        <option value="Otro" <?php echo ($libroData['categoria'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Cantidad Disponible</label>
                                    <input type="number" name="cantidad_disponible" class="form-control" value="<?php echo $libroData['cantidad_disponible']; ?>" min="0">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Ubicación</label>
                                <input type="text" name="ubicacion" class="form-control" value="<?php echo htmlspecialchars($libroData['ubicacion']); ?>" placeholder="Ej: Estante A1">
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Actualizar
                                </button>
                                <a href="index.php?ruta=libros" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>