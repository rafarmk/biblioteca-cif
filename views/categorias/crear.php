<?php
$page_title = "Nueva Categoría - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['logueado']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../layouts/navbar.php';
?>

<style>
body { padding-top: 100px; padding-bottom: 50px; background: var(--bg-primary); }
.container { max-width: 800px; margin: 0 auto; padding: 30px 20px; }

.form-card {
    background: var(--bg-card);
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 10px var(--shadow-color);
}

.form-header {
    margin-bottom: 30px;
}

.form-header h1 {
    font-size: 1.8rem;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-primary);
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    background: var(--bg-input);
    color: var(--text-primary);
    font-size: 1rem;
    box-sizing: border-box;
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.iconos-grid {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 10px;
    margin-top: 10px;
}

.icono-option {
    font-size: 2rem;
    text-align: center;
    padding: 10px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    background: var(--bg-card);
}

.icono-option:hover {
    transform: scale(1.1);
    background: var(--bg-secondary);
}

.icono-option.selected {
    border-color: var(--accent-primary);
    background: var(--accent-primary)20;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
}

.btn-primary {
    background: var(--accent-primary);
    color: white;
    flex: 1;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px var(--accent-shadow);
}

.btn-secondary {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.btn-secondary:hover {
    background: var(--border-color);
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .iconos-grid {
        grid-template-columns: repeat(6, 1fr);
    }
}
</style>

<div class="container">
    <div class="form-card">
        <div class="form-header">
            <h1>📚 Nueva Categoría</h1>
            <p style="color: var(--text-secondary);">Crea una nueva categoría para organizar los libros</p>
        </div>

        <form method="POST" action="index.php?ruta=categorias&accion=guardar">
            <div class="form-group">
                <label>Nombre de la Categoría *</label>
                <input type="text" name="nombre" required placeholder="Ej: Derecho Penal">
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" placeholder="Describe brevemente esta categoría..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Color</label>
                    <input type="color" name="color" value="#3b82f6">
                </div>

                <div class="form-group">
                    <label>Ícono Seleccionado</label>
                    <input type="text" name="icono" id="icono_input" value="📚" readonly style="font-size: 2rem; text-align: center;">
                </div>
            </div>

            <div class="form-group">
                <label>Selecciona un Ícono</label>
                <div class="iconos-grid">
                    <?php 
                    $iconos = ['📚', '⚖️', '📜', '🏛️', '👔', '🌍', '🔍', '⚕️', '🎯', '🕵️', '📖', '📕', '📗', '📘', '📙', '🔬', '💼', '🎓', '📝', '⚡', '🔐', '🛡️', '🎭', '🔔'];
                    foreach ($iconos as $icono): 
                    ?>
                        <div class="icono-option" onclick="seleccionarIcono(this, '<?= $icono ?>')"><?= $icono ?></div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">✅ Crear Categoría</button>
                <a href="index.php?ruta=categorias" class="btn btn-secondary">❌ Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
function seleccionarIcono(element, icono) {
    document.querySelectorAll('.icono-option').forEach(el => el.classList.remove('selected'));
    element.classList.add('selected');
    document.getElementById('icono_input').value = icono;
}

// Seleccionar el primer ícono por defecto
document.querySelector('.icono-option').classList.add('selected');
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>