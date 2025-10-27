<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>
<style id="input-fix">
input[type="text"],
input[type="number"],
textarea,
select {
    background-color: #ffffff !important;
    color: #000000 !important;
    border: 2px solid #d1d5db !important;
    padding: 12px 16px !important;
    border-radius: 8px !important;
    font-size: 16px !important;
}

::placeholder {
    color: #9ca3af !important;
}

label {
    color: #374151 !important;
    font-weight: 600 !important;
}

[data-theme="dark"] input,
[data-theme="dark"] textarea,
[data-theme="dark"] select {
    background-color: #374151 !important;
    color: #ffffff !important;
}

[data-theme="dark"] label {
    color: #f3f4f6 !important;
}
</style>
<style>
/* FORZAR VISIBILIDAD DE INPUTS */
input[type="text"],
input[type="email"], 
input[type="tel"],
select,
textarea {
    background-color: #ffffff !important;
    color: #000000 !important;
    border: 2px solid #cbd5e0 !important;
}

input::placeholder {
    color: #a0aec0 !important;
}

label {
    color: #2d3748 !important;
}

/* Modo Oscuro */
[data-theme="dark"] input[type="text"],
[data-theme="dark"] input[type="email"],
[data-theme="dark"] input[type="tel"],
[data-theme="dark"] select,
[data-theme="dark"] textarea {
    background-color: #2d3748 !important;
    color: #ffffff !important;
    border-color: #4a5568 !important;
}

[data-theme="dark"] label {
    color: #e2e8f0 !important;
}

/* Modo Premium */
[data-theme="premium"] input[type="text"],
[data-theme="premium"] input[type="email"],
[data-theme="premium"] input[type="tel"],
[data-theme="premium"] select,
[data-theme="premium"] textarea {
    background-color: #1a202c !important;
    color: #e2e8f0 !important;
    border-color: rgba(56, 189, 248, 0.4) !important;
}

[data-theme="premium"] label {
    color: #c9d1d9 !important;
}
</style>

<style>
.main-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px 40px 20px;
    position: relative;
    z-index: 1;
}

.page-header {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px var(--shadow);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    border: 2px solid var(--border-color);
}

[data-theme="premium"] .page-header {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: rgba(56, 189, 248, 0.2);
}

.page-header h1 {
    font-size: 2rem;
    color: var(--text-primary);
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 15px;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-secondary {
    background: var(--secondary);
    color: white;
}

.btn-secondary:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.btn-primary {
    background: var(--primary);
    color: white;
    width: 100%;
    justify-content: center;
    padding: 15px;
    font-size: 16px;
}

.btn-primary:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.content-card {
    background: var(--bg-card);
    border-radius: 16px;
    box-shadow: 0 4px 20px var(--shadow);
    border: 2px solid var(--border-color);
    padding: 40px;
}

[data-theme="premium"] .content-card {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: rgba(56, 189, 248, 0.2);
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 14px;
}

.form-group select,
.form-group input[type="text"],
.form-group input[type="number"],
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-group select:focus,
.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    font-weight: 500;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 2px solid #fecaca;
}

.info-badge {
    display: inline-block;
    padding: 8px 16px;
    background: var(--bg-secondary);
    border-radius: 8px;
    font-size: 13px;
    color: var(--text-secondary);
    margin-top: 5px;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .content-card {
        padding: 25px;
    }
}
</style>

<div class="main-container">
    <div class="page-header">
        <h1> Editar Libro</h1>
        <a href="index.php?ruta=libros" class="btn btn-secondary">
             Volver
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <div class="content-card">
        <?php if (isset($libro)): ?>
        <form action="index.php?ruta=libros&accion=editar" method="POST" id="formLibro">
            <input type="hidden" name="id" value="<?php echo $libro['id']; ?>">
            
            <div class="form-group">
                <label for="titulo"> Título del Libro *</label>
                <input type="text" name="titulo" id="titulo" required 
                       value="<?php echo htmlspecialchars($libro['titulo']); ?>"
                       placeholder="Ingrese el título del libro">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="autor"> Autor *</label>
                    <input type="text" name="autor" id="autor" required 
                           value="<?php echo htmlspecialchars($libro['autor']); ?>"
                           placeholder="Nombre del autor">
                </div>

                <div class="form-group">
                    <label for="isbn"> ISBN</label>
                    <input type="text" name="isbn" id="isbn" 
                           value="<?php echo htmlspecialchars($libro['isbn'] ?? ''); ?>"
                           placeholder="978-0-00-000000-0">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="editorial"> Editorial</label>
                    <input type="text" name="editorial" id="editorial" 
                           value="<?php echo htmlspecialchars($libro['editorial'] ?? ''); ?>"
                           placeholder="Nombre de la editorial">
                </div>

                <div class="form-group">
                    <label for="anio_publicacion"> Año de Publicación</label>
                    <input type="number" name="anio_publicacion" id="anio_publicacion" 
                           value="<?php echo htmlspecialchars($libro['anio_publicacion'] ?? ''); ?>"
                           min="1800" max="<?php echo date('Y'); ?>" 
                           placeholder="<?php echo date('Y'); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="categoria"> Categoría</label>
                    <input type="text" name="categoria" id="categoria" 
                           value="<?php echo htmlspecialchars($libro['categoria'] ?? ''); ?>"
                           placeholder="Ej: Ficción, Historia, Ciencia">
                </div>

                <div class="form-group">
                    <label for="cantidad_total"> Cantidad Total *</label>
                    <input type="number" name="cantidad_total" id="cantidad_total" required 
                           value="<?php echo htmlspecialchars($libro['cantidad_total'] ?? ''); ?>"
                           min="1" placeholder="Número de ejemplares">
                    <?php if (isset($libro['cantidad_disponible'])): ?>
                    <span class="info-badge">
                         Disponibles: <?php echo $libro['cantidad_disponible']; ?>
                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="ubicacion"> Ubicación en Biblioteca</label>
                <input type="text" name="ubicacion" id="ubicacion" 
                       value="<?php echo htmlspecialchars($libro['ubicacion'] ?? ''); ?>"
                       placeholder="Ej: Estante A - Nivel 2">
            </div>

            <div class="form-group">
                <label for="descripcion"> Descripción</label>
                <textarea name="descripcion" id="descripcion" 
                          placeholder="Breve descripción del libro (opcional)"><?php echo htmlspecialchars($libro['descripcion'] ?? ''); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                 Actualizar Libro
            </button>
        </form>
        <?php else: ?>
        <div style="text-align: center; padding: 60px 20px; color: var(--text-secondary);">
            <div style="font-size: 4rem; margin-bottom: 20px; opacity: 0.5;"></div>
            <h3 style="color: var(--text-primary);">Libro no encontrado</h3>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('formLibro')?.addEventListener('submit', function(e) {
    if (!confirm('¿Confirmar actualización del libro?')) {
        e.preventDefault();
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

