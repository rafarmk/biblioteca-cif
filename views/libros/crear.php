<?php
$page_title = "Agregar Nuevo Libro - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../layouts/navbar.php';
?>

<style>
.container {
    max-width: 900px;
    margin: 0 auto;
    padding: 30px 20px;
}

.page-header {
    text-align: center;
    margin-bottom: 30px;
}

.page-title {
    color: var(--text-primary);
    font-size: 2rem;
    margin-bottom: 10px;
}

.page-subtitle {
    color: var(--text-secondary);
}

.form-card {
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px var(--shadow-color);
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 8px;
}

.form-label.required::after {
    content: ' *';
    color: #ef4444;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 1rem;
    background: var(--bg-tertiary);
    color: var(--text-primary);
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

textarea.form-control {
    min-height: 100px;
    resize: vertical;
}

.btn-group {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
    color: white;
    flex: 1;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--shadow-color);
}

.btn-secondary {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    border: 2px solid var(--border-color);
}

.btn-secondary:hover {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-weight: 600;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 2px solid #10b981;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 2px solid #ef4444;
}
</style>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">➕ Agregar Nuevo Libro</h1>
        <p class="page-subtitle">Completa los datos del nuevo libro para agregarlo al catálogo</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="form-card">
        <form action="index.php?ruta=libros&accion=crear" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">ISBN</label>
                    <input type="text" name="isbn" class="form-control" 
                           placeholder="978-3-16-148410-0" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Título</label>
                    <input type="text" name="titulo" class="form-control" 
                           placeholder="El nombre del libro" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Autor</label>
                    <input type="text" name="autor" class="form-control" 
                           placeholder="Nombre del autor" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Editorial</label>
                    <input type="text" name="editorial" class="form-control" 
                           placeholder="Editorial del libro">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Año de Publicación</label>
                    <input type="number" name="anio_publicacion" class="form-control" 
                           placeholder="2025" min="1800" max="2025">
                </div>

                <div class="form-group">
                    <label class="form-label">Categoría</label>
                    <select name="categoria" class="form-control">
                        <option value="">Seleccione una categoría</option>
                        <option value="Ciencia">Ciencia</option>
                        <option value="Tecnología">Tecnología</option>
                        <option value="Historia">Historia</option>
                        <option value="Literatura">Literatura</option>
                        <option value="Arte">Arte</option>
                        <option value="Derecho">Derecho</option>
                        <option value="Medicina">Medicina</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Cantidad Total</label>
                    <input type="number" name="cantidad_total" class="form-control" 
                           placeholder="1" min="1" required value="1">
                </div>

                <div class="form-group">
                    <label class="form-label">Ubicación en Biblioteca</label>
                    <input type="text" name="ubicacion" class="form-control" 
                           placeholder="Ej: Estante A, Sección 3">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Descripción breve del libro</label>
                <textarea name="descripcion" class="form-control" 
                          placeholder="Descripción breve del contenido..."></textarea>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Libro
                </button>
                <a href="index.php?ruta=libros" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Libros
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
