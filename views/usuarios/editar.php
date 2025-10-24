<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Biblioteca CIF</title>
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
                        <h4 class="mb-0"><i class="fas fa-user-edit"></i> Editar Usuario</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="index.php?ruta=usuarios&accion=editar">
                            <input type="hidden" name="id" value="<?php echo $usuarioData['id']; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Nombre Completo *</label>
                                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($usuarioData['nombre']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($usuarioData['email']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($usuarioData['telefono']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Dirección</label>
                                <textarea name="direccion" class="form-control" rows="3"><?php echo htmlspecialchars($usuarioData['direccion']); ?></textarea>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Actualizar
                                </button>
                                <a href="index.php?ruta=usuarios" class="btn btn-secondary">
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