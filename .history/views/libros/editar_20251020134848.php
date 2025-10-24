<?php
/**
 * Vista: Formulario Editar Libro
 * Archivo: views/libros/editar.php
 */

// Obtener categorías
require_once 'config/conexion.php';
$conexionObj = new Conexion();
$conn = $conexionObj->conectar();
$categorias_query = $conn->query("SELECT * FROM categorias ORDER BY nombre");
$categorias = $categorias_query->fetchAll(PDO::FETCH_ASSOC);

// Verificar que existe la variable $libro pasada desde el controlador
if (!isset($libro) || empty($libro)) {
    header('Location: index.php?ruta=libros');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro - Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 30px 0; }
        .form-container { max-width: 900px; margin: 0 auto; background: white; border-radius: 20px; box-shadow: 0 15px 50px rgba(0,0,0,0.3); }
        .form-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; text-align: center; border-radius: 20px 20px 0 0; }
        .form-header h2 { margin: 0; font-size: 2.2rem; font-weight: 700; }
        .form-body { padding: 40px; }
        .section-title { font-size: 1.3rem; font-weight: 700; color: #495057; margin: 30px 0 20px; padding-bottom: 10px; border-bottom: 3px solid #667eea; }
        .form-label.required::after { content: " *"; color: #dc3545; }
        .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15); }
        .help-text { font-size: 0.875rem; color: #6c757d; margin-top: 5px; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 40px; font-weight: 600; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5); }
        .info-badge { background: #e3f2fd; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #2196f3; }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        
        <div class="form-header">
            <h2><i class="fas fa-edit"></i> Editar Libro</h2>
            <p>Modifica la información del libro</p>
        </div>
        
        <div class="form-body">
            
            <div class="info-badge">
                <strong><i class="fas fa-info-circle"></i> Información:</strong>
                Editando libro ID: <strong>#<?= htmlspecialchars($libro['id']) ?></strong> 
                | Registrado: <?= date('d/m/Y', strtotime($libro['fecha_registro'])) ?>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle"></i> <strong>Error:</strong> <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <form action="index.php?ruta=libros/actualizar&id=<?= $libro['id'] ?>" method="POST" id="formLibro">
                
                <input type="hidden" name="id" value="<?= htmlspecialchars($libro['id']) ?>">

                <!-- SECCIÓN 1: INFORMACIÓN BÁSICA -->
                <div class="section-title"><i class="fas fa-info-circle"></i> Información Básica</div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" 
                               placeholder="978-3-16-148410-0" maxlength="20"
                               value="<?= htmlspecialchars($libro['isbn'] ?? '') ?>">
                        <div class="help-text"><i class="fas fa-barcode"></i> Código único (opcional)</div>
                    </div>
                    
                    <div class="col-md-8 mb-3">
                        <label for="titulo" class="form-label required">Título</label>
                        <input type="text" class="form-control form-control-lg" id="titulo" name="titulo" 
                               placeholder="Ej: Cien Años de Soledad" required maxlength="255"
                               value="<?= htmlspecialchars($libro['titulo']) ?>">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="autor" class="form-label required">Autor</label>
                    <input type="text" class="form-control form-control-lg" id="autor" name="autor" 
                           placeholder="Ej: Gabriel García Márquez" required maxlength="255"
                           value="<?= htmlspecialchars($libro['autor']) ?>">
                    <div class="help-text"><i class="fas fa-user-edit"></i> Si son varios autores, sepárelos con comas</div>
                </div>
                
                <!-- SECCIÓN 2: DETALLES DE PUBLICACIÓN -->
                <div class="section-title"><i class="fas fa-newspaper"></i> Detalles de Publicación</div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="editorial" class="form-label">Editorial</label>
                        <input type="text" class="form-control" id="editorial" name="editorial" 
                               placeholder="Ej: Editorial Sudamericana" maxlength="100"
                               value="<?= htmlspecialchars($libro['editorial'] ?? '') ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="anio_publicacion" class="form-label">Año de Publicación</label>
                        <input type="number" class="form-control" id="anio_publicacion" name="anio_publicacion" 
                               placeholder="<?= date('Y') ?>" min="1000" max="<?= date('Y') ?>"
                               value="<?= htmlspecialchars($libro['anio_publicacion'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria" name="categoria">
                            <option value="">-- Seleccionar Categoría --</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['nombre']) ?>"
                                    <?= ($libro['categoria'] == $cat['nombre']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="idioma" class="form-label">Idioma</label>
                        <select class="form-select" id="idioma" name="idioma">
                            <option value="Español" <?= ($libro['idioma'] ?? 'Español') == 'Español' ? 'selected' : '' ?>>Español</option>
                            <option value="Inglés" <?= ($libro['idioma'] ?? '') == 'Inglés' ? 'selected' : '' ?>>Inglés</option>
                            <option value="Francés" <?= ($libro['idioma'] ?? '') == 'Francés' ? 'selected' : '' ?>>Francés</option>
                            <option value="Alemán" <?= ($libro['idioma'] ?? '') == 'Alemán' ? 'selected' : '' ?>>Alemán</option>
                            <option value="Otro" <?= ($libro['idioma'] ?? '') == 'Otro' ? 'selected' : '' ?>>Otro</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="num_paginas" class="form-label">Número de Páginas</label>
                    <input type="number" class="form-control" id="num_paginas" name="num_paginas" 
                           placeholder="Ej: 350" min="1"
                           value="<?= htmlspecialchars($libro['num_paginas'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción / Sinopsis</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4" 
                              placeholder="Breve descripción del contenido del libro..."><?= htmlspecialchars($libro['descripcion'] ?? '') ?></textarea>
                </div>
                
                <!-- SECCIÓN 3: INVENTARIO -->
                <div class="section-title"><i class="fas fa-boxes"></i> Información de Inventario</div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cantidad_total" class="form-label">Total de Copias</label>
                        <input type="number" class="form-control" id="cantidad_total" name="cantidad_total" 
                               value="<?= htmlspecialchars($libro['cantidad_total'] ?? 1) ?>" 
                               min="<?= htmlspecialchars($libro['cantidad_total'] - $libro['cantidad_disponible']) ?>" max="100">
                        <div class="help-text">
                            <i class="fas fa-info-circle"></i> 
                            Disponibles actualmente: <strong><?= $libro['cantidad_disponible'] ?></strong>
                            | Prestados: <strong><?= ($libro['cantidad_total'] - $libro['cantidad_disponible']) ?></strong>
                        </div>
                    </div>
                </div>
                
                <!-- BOTONES -->
                <div class="d-flex gap-3 justify-content-center mt-5">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Actualizar Libro
                    </button>
                    <a href="index.php?ruta=libros" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-muted"><i class="fas fa-asterisk text-danger"></i> Los campos con * son obligatorios</small>
                </div>
                
            </form>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('formLibro').addEventListener('submit', function(e) {
    const titulo = document.getElementById('titulo').value.trim();
    const autor = document.getElementById('autor').value.trim();
    
    if (!titulo || !autor) {
        e.preventDefault();
        alert('❌ Título y Autor son obligatorios');
        return false;
    }
    
    const btnSubmit = this.querySelector('button[type="submit"]');
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
});

// Auto-cerrar alertas después de 5 segundos
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>

</body>
</html>