<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro - Sistema Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px;
            margin-top: 20px;
        }
        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 12px;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-guardar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-guardar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-cancelar {
            background: #6c757d;
            border: none;
            padding: 12px 40px;
            border-radius: 10px;
            color: white;
            font-weight: 600;
        }
        .required {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="form-header">
            <h1 class="mb-2">
                <i class="bi bi-pencil-square"></i> Editar Libro
            </h1>
            <p class="mb-0">Actualiza la información del libro en el catálogo</p>
        </div>

        <!-- Mensajes de Error -->
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong>Error:</strong> <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Formulario -->
        <div class="form-card">
            <form action="index.php?ruta=libros/actualizar&id=<?= $libro['id'] ?>" method="POST" enctype="multipart/form-data">
                
                <!-- ID Oculto -->
                <input type="hidden" name="id" value="<?= $libro['id'] ?>">

                <div class="row g-3">
                    <!-- Título -->
                    <div class="col-md-8">
                        <label class="form-label">
                            <i class="bi bi-book"></i> Título del Libro <span class="required">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="titulo" 
                               value="<?= htmlspecialchars($libro['titulo']) ?>"
                               placeholder="Ej: Cien años de soledad"
                               required>
                    </div>

                    <!-- ISBN -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-upc-scan"></i> ISBN
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="isbn" 
                               value="<?= htmlspecialchars($libro['isbn'] ?? '') ?>"
                               placeholder="978-3-16-148410-0">
                    </div>

                    <!-- Autor -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-person-fill"></i> Autor <span class="required">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="autor" 
                               value="<?= htmlspecialchars($libro['autor']) ?>"
                               placeholder="Ej: Gabriel García Márquez"
                               required>
                    </div>

                    <!-- Editorial -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-building"></i> Editorial
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="editorial" 
                               value="<?= htmlspecialchars($libro['editorial'] ?? '') ?>"
                               placeholder="Ej: Penguin Random House">
                    </div>

                    <!-- Categoría -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-tag-fill"></i> Categoría
                        </label>
                        <select class="form-select" name="categoria">
                            <option value="">Seleccionar...</option>
                            <option value="Ficción" <?= ($libro['categoria'] ?? '') == 'Ficción' ? 'selected' : '' ?>>Ficción</option>
                            <option value="No Ficción" <?= ($libro['categoria'] ?? '') == 'No Ficción' ? 'selected' : '' ?>>No Ficción</option>
                            <option value="Ciencia" <?= ($libro['categoria'] ?? '') == 'Ciencia' ? 'selected' : '' ?>>Ciencia</option>
                            <option value="Historia" <?= ($libro['categoria'] ?? '') == 'Historia' ? 'selected' : '' ?>>Historia</option>
                            <option value="Tecnología" <?= ($libro['categoria'] ?? '') == 'Tecnología' ? 'selected' : '' ?>>Tecnología</option>
                            <option value="Literatura" <?= ($libro['categoria'] ?? '') == 'Literatura' ? 'selected' : '' ?>>Literatura</option>
                            <option value="Infantil" <?= ($libro['categoria'] ?? '') == 'Infantil' ? 'selected' : '' ?>>Infantil</option>
                            <option value="Educación" <?= ($libro['categoria'] ?? '') == 'Educación' ? 'selected' : '' ?>>Educación</option>
                            <option value="Referencia" <?= ($libro['categoria'] ?? '') == 'Referencia' ? 'selected' : '' ?>>Referencia</option>
                            <option value="Otro" <?= ($libro['categoria'] ?? '') == 'Otro' ? 'selected' : '' ?>>Otro</option>
                        </select>
                    </div>

                    <!-- Año de Publicación -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-calendar-event"></i> Año de Publicación
                        </label>
                        <input type="number" 
                               class="form-control" 
                               name="anio_publicacion" 
                               value="<?= htmlspecialchars($libro['anio_publicacion'] ?? '') ?>"
                               min="1000" 
                               max="<?= date('Y') ?>"
                               placeholder="<?= date('Y') ?>">
                    </div>

                    <!-- Stock/Cantidad -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-boxes"></i> Stock Disponible <span class="required">*</span>
                        </label>
                        <input type="number" 
                               class="form-control" 
                               name="stock" 
                               value="<?= htmlspecialchars($libro['stock'] ?? 1) ?>"
                               min="0" 
                               placeholder="Ej: 5"
                               required>
                    </div>

                    <!-- Descripción -->
                    <div class="col-12">
                        <label class="form-label">
                            <i class="bi bi-journal-text"></i> Descripción / Sinopsis
                        </label>
                        <textarea class="form-control" 
                                  name="descripcion" 
                                  rows="4" 
                                  placeholder="Breve descripción del contenido del libro..."><?= htmlspecialchars($libro['descripcion'] ?? '') ?></textarea>
                    </div>

                    <!-- Imagen Actual -->
                    <?php if(!empty($libro['imagen'])): ?>
                    <div class="col-12">
                        <label class="form-label">
                            <i class="bi bi-image"></i> Imagen Actual
                        </label>
                        <div class="mb-2">
                            <img src="<?= $libro['imagen'] ?>" 
                                 alt="<?= htmlspecialchars($libro['titulo']) ?>" 
                                 style="max-width: 200px; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.2);">
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Nueva Imagen -->
                    <div class="col-12">
                        <label class="form-label">
                            <i class="bi bi-cloud-upload"></i> Cambiar Imagen (opcional)
                        </label>
                        <input type="file" 
                               class="form-control" 
                               name="imagen" 
                               accept="image/*">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Formatos: JPG, PNG, GIF. Máximo 5MB.
                        </small>
                    </div>

                    <!-- Botones -->
                    <div class="col-12 mt-4">
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="index.php?ruta=libros" class="btn btn-cancelar">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-guardar">
                                <i class="bi bi-check-circle"></i> Actualizar Libro
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Nota informativa -->
        <div class="alert alert-info mt-4">
            <i class="bi bi-lightbulb-fill"></i>
            <strong>Nota:</strong> Los campos marcados con <span class="required">*</span> son obligatorios.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para preview de imagen -->
    <script>
        document.querySelector('input[name="imagen"]').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    // Crear preview
                    const preview = document.createElement('img');
                    preview.src = event.target.result;
                    preview.style.maxWidth = '200px';
                    preview.style.marginTop = '10px';
                    preview.style.borderRadius = '10px';
                    preview.className = 'img-thumbnail';
                    
                    // Remover preview anterior si existe
                    const oldPreview = e.target.parentElement.querySelector('.img-thumbnail');
                    if (oldPreview) oldPreview.remove();
                    
                    // Agregar nuevo preview
                    e.target.parentElement.appendChild(preview);
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
</body>
</html>