<?php
$page_title = "Editar Libro - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../layouts/navbar.php';
?>

<style>
body {
    padding-top: 100px;
    padding-bottom: 50px;
}

.container {
    max-width: 900px;
    margin: 0 auto;
    padding: 30px 20px;
}

.card {
    background: var(--bg-card);
    border-radius: 15px;
    box-shadow: 0 8px 30px var(--shadow-color);
    padding: 40px;
    margin-bottom: 20px;
}

.card-header {
    border-bottom: 3px solid var(--accent-primary);
    padding-bottom: 20px;
    margin-bottom: 30px;
}

.card-header h2 {
    color: var(--text-primary);
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 0.95rem;
}

.form-control {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    background: var(--bg-input);
    color: var(--text-primary);
    font-size: 1rem;
    transition: all 0.3s;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px var(--accent-shadow);
}

.form-control:disabled,
.form-control[readonly] {
    background: var(--bg-disabled);
    color: var(--text-secondary);
    cursor: not-allowed;
    opacity: 0.7;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
}

select.form-control {
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid var(--border-color);
}

.btn {
    padding: 14px 35px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
    font-size: 1rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
    color: white;
    box-shadow: 0 4px 15px var(--shadow-color);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px var(--shadow-color);
}

.btn-secondary {
    background: #6b7280;
    color: white;
    box-shadow: 0 4px 15px var(--shadow-color);
}

.btn-secondary:hover {
    background: #4b5563;
    transform: translateY(-2px);
}

.alert {
    padding: 18px 25px;
    border-radius: 10px;
    margin-bottom: 25px;
    font-weight: 500;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 2px solid #fca5a5;
}

.required {
    color: #ef4444;
    font-weight: 700;
}

small {
    color: var(--text-secondary);
    font-size: 0.85rem;
    display: block;
    margin-top: 5px;
}

@media (max-width: 768px) {
    .container {
        padding: 15px;
    }
    
    .card {
        padding: 25px 20px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column-reverse;
    }
    
    .btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>✏️ Editar Libro</h2>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="id" value="<?= $libro['id'] ?? '' ?>">

            <div class="form-group">
                <label>ISBN <span class="required">*</span></label>
                <input type="text" name="isbn" class="form-control" 
                       value="<?= htmlspecialchars($libro['isbn'] ?? '') ?>" 
                       readonly>
                <small>El ISBN no se puede modificar</small>
            </div>

            <div class="form-group">
                <label>Título <span class="required">*</span></label>
                <input type="text" name="titulo" class="form-control" 
                       value="<?= htmlspecialchars($libro['titulo'] ?? '') ?>" 
                       required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Autor <span class="required">*</span></label>
                    <input type="text" name="autor" class="form-control" 
                           value="<?= htmlspecialchars($libro['autor'] ?? '') ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label>Editorial</label>
                    <input type="text" name="editorial" class="form-control" 
                           value="<?= htmlspecialchars($libro['editorial'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria_id" class="form-control">
                        <option value="">Seleccione...</option>
                        <?php if (isset($categorias) && is_array($categorias) && count($categorias) > 0): ?>
                            <?php foreach($categorias as $cat): ?>
                                <option value="<?= $cat['id'] ?>" 
                                        <?= (isset($libro['categoria_id']) && $libro['categoria_id'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Año de Publicación</label>
                    <input type="number" name="anio_publicacion" class="form-control" 
                           value="<?= $libro['anio_publicacion'] ?? '' ?>" 
                           min="1500" max="<?= date('Y') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Número de Páginas</label>
                    <input type="number" name="num_paginas" class="form-control" 
                           value="<?= $libro['num_paginas'] ?? '' ?>" 
                           min="1">
                </div>

                <div class="form-group">
                    <label>Idioma</label>
                    <select name="idioma" class="form-control">
                        <option value="Espanol" <?= (isset($libro['idioma']) && $libro['idioma'] == 'Espanol') ? 'selected' : '' ?>>Español</option>
                        <option value="Ingles" <?= (isset($libro['idioma']) && $libro['idioma'] == 'Ingles') ? 'selected' : '' ?>>Inglés</option>
                        <option value="Frances" <?= (isset($libro['idioma']) && $libro['idioma'] == 'Frances') ? 'selected' : '' ?>>Francés</option>
                        <option value="Otro" <?= (isset($libro['idioma']) && $libro['idioma'] == 'Otro') ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Cantidad Total <span class="required">*</span></label>
                    <input type="number" name="cantidad_total" class="form-control" 
                           value="<?= $libro['cantidad_total'] ?? 1 ?>" 
                           min="0" required>
                </div>

                <div class="form-group">
                    <label>Cantidad Disponible <span class="required">*</span></label>
                    <input type="number" name="cantidad_disponible" class="form-control" 
                           value="<?= $libro['cantidad_disponible'] ?? 1 ?>" 
                           min="0" required>
                </div>

                <div class="form-group">
                    <label>Ubicación</label>
                    <input type="text" name="ubicacion" class="form-control" 
                           value="<?= htmlspecialchars($libro['ubicacion'] ?? '') ?>" 
                           placeholder="Ej: ESTANTE A3">
                </div>
            </div>

            <div class="form-group">
                <label>Estado <span class="required">*</span></label>
                <select name="estado" class="form-control" required>
                    <option value="disponible" <?= (isset($libro['estado']) && $libro['estado'] == 'disponible') ? 'selected' : '' ?>>✅ Disponible</option>
                    <option value="no_disponible" <?= (isset($libro['estado']) && $libro['estado'] == 'no_disponible') ? 'selected' : '' ?>>❌ No Disponible</option>
                    <option value="en_reparacion" <?= (isset($libro['estado']) && $libro['estado'] == 'en_reparacion') ? 'selected' : '' ?>>🔧 En Reparación</option>
                    <option value="perdido" <?= (isset($libro['estado']) && $libro['estado'] == 'perdido') ? 'selected' : '' ?>>🔍 Perdido</option>
                </select>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="4" placeholder="Descripción del libro (opcional)"><?= htmlspecialchars($libro['descripcion'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <a href="index.php?ruta=libros" class="btn btn-secondary">
                    ❌ Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    💾 Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>