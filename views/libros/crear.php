<?php
$pageTitle = 'Agregar Nuevo Libro - Biblioteca CIF';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="page-container fade-in">
    <div class="content-wrapper">
        <h1 class="page-title">➕ Agregar Nuevo Libro</h1>
        <p class="page-subtitle">Completa los datos del nuevo libro para agregarlo al catálogo</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <form action="index.php?ruta=libros&accion=crear" method="POST" class="form-container">
            
            <div class="row">
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-label required">ISBN</label>
                        <input type="text" 
                               name="isbn" 
                               class="form-control" 
                               placeholder="978-3-16-148410-0"
                               required>
                        <small style="color: var(--text-light); font-size: 0.85rem;">Código único internacional del libro</small>
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label class="form-label required">Título</label>
                        <input type="text" 
                               name="titulo" 
                               class="form-control" 
                               placeholder="El nombre del libro"
                               required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <div class="form-group">
                        <label class="form-label required">Autor</label>
                        <input type="text" 
                               name="autor" 
                               class="form-control" 
                               placeholder="Nombre del autor"
                               required>
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label class="form-label required">Editorial</label>
                        <input type="text" 
                               name="editorial" 
                               class="form-control" 
                               placeholder="Editorial del libro"
                               required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label class="form-label">Año de Publicación</label>
                        <input type="number" 
                               name="anio_publicacion" 
                               class="form-control" 
                               min="1800" 
                               max="<?php echo date('Y'); ?>"
                               placeholder="<?php echo date('Y'); ?>">
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-group">
                        <label class="form-label required">Categoría</label>
                        <select name="categoria_id" class="form-select" required>
                            <option value="">Seleccione una categoría</option>
                            <?php if (isset($categorias) && is_array($categorias)): ?>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat['id']); ?>">
                                        <?php echo htmlspecialchars($cat['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-group">
                        <label class="form-label required">Cantidad Disponible</label>
                        <input type="number" 
                               name="cantidad_disponible" 
                               class="form-control" 
                               value="1" 
                               min="0"
                               required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" 
                          class="form-textarea" 
                          placeholder="Descripción breve del libro (opcional)..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Ubicación en Biblioteca</label>
                <input type="text" 
                       name="ubicacion" 
                       class="form-control" 
                       placeholder="Ej: Estante A, Sección 3">
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Guardar Libro
                </button>
                <a href="index.php?ruta=libros" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
