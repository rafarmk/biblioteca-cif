<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Préstamo - Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .page-header {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            padding: 40px 0;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .page-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .form-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        .form-section {
            margin-bottom: 30px;
        }
        .form-section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        .btn-modern {
            padding: 15px 30px;
            border-radius: 15px;
            border: none;
            font-weight: 700;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-success-modern {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            color: white;
        }
        .btn-secondary-modern {
            background: linear-gradient(135deg, #bbb 0%, #999 100%);
            color: white;
            text-decoration: none;
        }
        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }
        .alert-modern {
            border-radius: 15px;
            border: none;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .libro-info, .usuario-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 10px;
            display: none;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: 600;
            color: #666;
        }
    </style>
</head>
<body>
    <?php require_once 'views/layouts/navbar.php'; ?>

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-plus-circle"></i> Registrar Nuevo Préstamo
        </h1>
    </div>

    <div class="container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-modern alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" action="index.php?ruta=prestamos/guardar" id="prestamoForm">
                
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-user"></i> Información del Usuario
                    </h3>
                    
                    <div class="mb-3">
                        <label for="usuario_id" class="form-label">
                            <i class="fas fa-user-circle"></i> Seleccionar Usuario *
                        </label>
                        <select class="form-select" id="usuario_id" name="usuario_id" required>
                            <option value="">-- Seleccione un usuario --</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?php echo $usuario['id']; ?>" 
                                        data-nombre="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                                        data-email="<?php echo htmlspecialchars($usuario['email']); ?>"
                                        data-telefono="<?php echo htmlspecialchars($usuario['telefono'] ?? 'N/A'); ?>">
                                    <?php echo htmlspecialchars($usuario['nombre']); ?> - <?php echo htmlspecialchars($usuario['email']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="usuario-info" id="usuario-info">
                        <div class="info-item">
                            <span class="info-label">Nombre:</span>
                            <span id="info-usuario-nombre"></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span id="info-usuario-email"></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Teléfono:</span>
                            <span id="info-usuario-telefono"></span>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-book"></i> Información del Libro
                    </h3>
                    
                    <div class="mb-3">
                        <label for="libro_id" class="form-label">
                            <i class="fas fa-book-open"></i> Seleccionar Libro *
                        </label>
                        <select class="form-select" id="libro_id" name="libro_id" required>
                            <option value="">-- Seleccione un libro --</option>
                            <?php foreach ($libros as $libro): ?>
                                <?php if ($libro['cantidad_disponible'] > 0): ?>
                                    <option value="<?php echo $libro['id']; ?>"
                                            data-titulo="<?php echo htmlspecialchars($libro['titulo']); ?>"
                                            data-autor="<?php echo htmlspecialchars($libro['autor']); ?>"
                                            data-isbn="<?php echo htmlspecialchars($libro['isbn'] ?? 'N/A'); ?>"
                                            data-disponible="<?php echo $libro['cantidad_disponible']; ?>">
                                        <?php echo htmlspecialchars($libro['titulo']); ?> - <?php echo htmlspecialchars($libro['autor']); ?> 
                                        (Disponibles: <?php echo $libro['cantidad_disponible']; ?>)
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="libro-info" id="libro-info">
                        <div class="info-item">
                            <span class="info-label">Título:</span>
                            <span id="info-libro-titulo"></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Autor:</span>
                            <span id="info-libro-autor"></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">ISBN:</span>
                            <span id="info-libro-isbn"></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Disponibles:</span>
                            <span id="info-libro-disponible" style="color: #56ab2f; font-weight: 700;"></span>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-calendar"></i> Fechas del Préstamo
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_prestamo" class="form-label">
                                <i class="fas fa-calendar-plus"></i> Fecha de Préstamo *
                            </label>
                            <input type="date" class="form-control" id="fecha_prestamo" 
                                   name="fecha_prestamo" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fecha_devolucion_esperada" class="form-label">
                                <i class="fas fa-calendar-check"></i> Fecha de Devolución Esperada *
                            </label>
                            <input type="date" class="form-control" id="fecha_devolucion_esperada" 
                                   name="fecha_devolucion_esperada" 
                                   value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-sticky-note"></i> Notas Adicionales
                    </h3>
                    
                    <div class="mb-3">
                        <label for="notas" class="form-label">
                            <i class="fas fa-comment-alt"></i> Notas (Opcional)
                        </label>
                        <textarea class="form-control" id="notas" name="notas" rows="3" 
                                  placeholder="Ej: Condición del libro, observaciones, etc."></textarea>
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-end">
                    <a href="index.php?ruta=prestamos" class="btn-modern btn-secondary-modern">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn-modern btn-success-modern">
                        <i class="fas fa-save"></i>
                        Registrar Préstamo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('usuario_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const infoDiv = document.getElementById('usuario-info');
            
            if (this.value) {
                document.getElementById('info-usuario-nombre').textContent = selected.dataset.nombre;
                document.getElementById('info-usuario-email').textContent = selected.dataset.email;
                document.getElementById('info-usuario-telefono').textContent = selected.dataset.telefono;
                infoDiv.style.display = 'block';
            } else {
                infoDiv.style.display = 'none';
            }
        });

        document.getElementById('libro_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const infoDiv = document.getElementById('libro-info');
            
            if (this.value) {
                document.getElementById('info-libro-titulo').textContent = selected.dataset.titulo;
                document.getElementById('info-libro-autor').textContent = selected.dataset.autor;
                document.getElementById('info-libro-isbn').textContent = selected.dataset.isbn;
                document.getElementById('info-libro-disponible').textContent = selected.dataset.disponible + ' copias';
                infoDiv.style.display = 'block';
            } else {
                infoDiv.style.display = 'none';
            }
        });

        document.getElementById('prestamoForm').addEventListener('submit', function(e) {
            const fechaPrestamo = new Date(document.getElementById('fecha_prestamo').value);
            const fechaDevolucion = new Date(document.getElementById('fecha_devolucion_esperada').value);
            
            if (fechaDevolucion <= fechaPrestamo) {
                e.preventDefault();
                alert('La fecha de devolución debe ser posterior a la fecha de préstamo');
                return false;
            }
        });
    </script>
</body>
</html>