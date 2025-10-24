<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

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
.form-group input[type="email"],
.form-group input[type="tel"],
.form-group input[type="number"] {
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
.form-group input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
        <h1> Editar Usuario</h1>
        <a href="index.php?ruta=usuarios" class="btn btn-secondary">
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
        <?php if (isset($usuario)): ?>
        <form action="index.php?ruta=usuarios&accion=editar" method="POST" id="formUsuario">
            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
            
            <div class="form-group">
                <label for="nombre"> Nombre Completo *</label>
                <input type="text" name="nombre" id="nombre" required 
                       value="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                       placeholder="Ingrese el nombre completo">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="correo"> Correo Electrónico</label>
                    <input type="email" name="correo" id="correo" 
                           value="<?php echo htmlspecialchars($usuario['correo'] ?? ''); ?>"
                           placeholder="ejemplo@correo.com">
                </div>

                <div class="form-group">
                    <label for="telefono"> Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" 
                           value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>"
                           placeholder="0000-0000">
                </div>
            </div>

            <div class="form-group">
                <label for="tipo_usuario"> Tipo de Usuario *</label>
                <select name="tipo_usuario" id="tipo_usuario" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="estudiante" <?php echo ($usuario['tipo_usuario'] ?? '') == 'estudiante' ? 'selected' : ''; ?>>
                        Estudiante
                    </option>
                    <option value="docente" <?php echo ($usuario['tipo_usuario'] ?? '') == 'docente' ? 'selected' : ''; ?>>
                        Docente
                    </option>
                    <option value="personal" <?php echo ($usuario['tipo_usuario'] ?? '') == 'personal' ? 'selected' : ''; ?>>
                        Personal
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="direccion"> Dirección</label>
                <input type="text" name="direccion" id="direccion" 
                       value="<?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?>"
                       placeholder="Ingrese la dirección">
            </div>

            <button type="submit" class="btn btn-primary">
                 Actualizar Usuario
            </button>
        </form>
        <?php else: ?>
        <div style="text-align: center; padding: 60px 20px; color: var(--text-secondary);">
            <div style="font-size: 4rem; margin-bottom: 20px; opacity: 0.5;"></div>
            <h3 style="color: var(--text-primary);">Usuario no encontrado</h3>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('formUsuario')?.addEventListener('submit', function(e) {
    if (!confirm('¿Confirmar actualización del usuario?')) {
        e.preventDefault();
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
