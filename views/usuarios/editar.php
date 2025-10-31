<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<style>
.modern-form-container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 0 20px;
}

.form-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 50px;
    border: 2px solid var(--border-color);
    box-shadow: 0 8px 32px var(--shadow);
}

[data-theme="premium"] .form-card {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: rgba(56, 189, 248, 0.2);
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border-color);
}

.form-header-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.form-header h2 {
    color: var(--text-primary);
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
}

.form-header i {
    font-size: 2rem;
    color: var(--primary);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 14px 18px;
    background: #ffffff;
    color: #000000;
    border: 2px solid #d1dce5;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid var(--border-color);
}

.btn-primary-modern {
    flex: 1;
    padding: 14px 32px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
}

.btn-secondary-modern {
    padding: 12px 24px;
    background: var(--bg-secondary);
    color: var(--text-primary);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-secondary-modern:hover {
    background: var(--bg-card);
    border-color: var(--primary);
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    border: 2px solid #fecaca;
    display: flex;
    align-items: center;
    gap: 10px;
}

[data-theme="dark"] .form-group input,
[data-theme="dark"] .form-group textarea,
[data-theme="dark"] .form-group select {
    background: #2d3748;
    color: #ffffff;
    border-color: #4a5568;
}

[data-theme="dark"] .form-group input:focus,
[data-theme="dark"] .form-group textarea:focus,
[data-theme="dark"] .form-group select:focus {
    border-color: #3b82f6;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .form-card {
        padding: 30px;
    }
}

html, body { height: 100%; margin: 0; }
body { display: flex; flex-direction: column; min-height: 100vh; }
.modern-form-container { flex: 1 0 auto; }
footer { flex-shrink: 0; margin-top: auto !important; }
</style>

<div class="modern-form-container">
    <div class="form-card">
        <div class="form-header">
            <div class="form-header-left">
                <i class="fas fa-user-edit"></i>
                <h2>Editar Usuario</h2>
            </div>
            <a href="index.php?ruta=usuarios" class="btn-secondary-modern">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($usuario)): ?>
        <form action="index.php?ruta=usuarios&accion=editar&id=<?php echo $usuario['id']; ?>" method="POST">

            <div class="form-group">
                <label>Nombre Completo *</label>
                <input type="text" name="nombre" id="nombre" required
                       value="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                       placeholder="Nombre completo">
            </div>

            <div class="form-group">
                <label>Tipo de Usuario *</label>
                <select name="tipo_usuario" id="tipo_usuario" required>
                    <option value="personal_administrativo" <?php echo ($usuario['tipo_usuario'] ?? '') == 'personal_administrativo' ? 'selected' : ''; ?>>Personal Administrativo</option>
                    <option value="personal_operativo" <?php echo ($usuario['tipo_usuario'] ?? '') == 'personal_operativo' ? 'selected' : ''; ?>>Personal Operativo</option>
                    <option value="visitas" <?php echo ($usuario['tipo_usuario'] ?? '') == 'visitas' ? 'selected' : ''; ?>>Visitas</option>
                    <option value="estudiante_mayor" <?php echo ($usuario['tipo_usuario'] ?? '') == 'estudiante_mayor' ? 'selected' : ''; ?>>Estudiante (Mayor de Edad)</option>
                    <option value="estudiante_menor" <?php echo ($usuario['tipo_usuario'] ?? '') == 'estudiante_menor' ? 'selected' : ''; ?>>Estudiante (Menor de Edad)</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" id="email"
                           value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>"
                           placeholder="correo@ejemplo.com">
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="tel" name="telefono" id="telefono"
                           value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>"
                           placeholder="(503) 0000-0000">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>ONI</label>
                    <input type="text" name="oni" id="oni"
                           value="<?php echo htmlspecialchars($usuario['oni'] ?? ''); ?>"
                           placeholder="Número ONI">
                </div>

                <div class="form-group">
                    <label>DUI</label>
                    <input type="text" name="dui" id="dui"
                           value="<?php echo htmlspecialchars($usuario['dui'] ?? ''); ?>"
                           placeholder="00000000-0">
                </div>
            </div>

            <div class="form-group">
                <label>Dirección</label>
                <textarea name="direccion" id="direccion" rows="3" placeholder="Dirección completa"><?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado" id="estado">
                    <option value="activo" <?php echo ($usuario['estado'] ?? 'activo') == 'activo' ? 'selected' : ''; ?>>Activo</option>
                    <option value="inactivo" <?php echo ($usuario['estado'] ?? '') == 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary-modern">
                    <i class="fas fa-save"></i>
                    Guardar Usuario
                </button>
                <a href="index.php?ruta=usuarios" class="btn-secondary-modern">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
            </div>
        </form>
        <?php else: ?>
        <div style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 4rem; margin-bottom: 20px; opacity: 0.5;">
                <i class="fas fa-user-times"></i>
            </div>
            <h3 style="color: var(--text-primary);">Usuario no encontrado</h3>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
