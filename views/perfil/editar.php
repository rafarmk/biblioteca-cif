<?php require_once 'views/layouts/navbar.php'; ?>

<style>
.container {
    max-width: 700px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 30px;
}

.help-text {
    font-size: 0.85rem;
    color: var(--text-secondary);
    margin-top: 5px;
}

.password-section {
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    margin-top: 30px;
}

.password-section h3 {
    font-size: 1.1rem;
    color: var(--text-primary);
    margin-bottom: 15px;
}
</style>

<div class="container">
    <div class="page-header">
        <h1 class="page-title"> Editar Perfil</h1>
        <a href="index.php?ruta=perfil" class="btn btn-info">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="alert-content"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        </div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" action="index.php?ruta=perfil&accion=editar">
            <div class="form-group">
                <label for="nombre">Nombre Completo *</label>
                <input type="text" id="nombre" name="nombre" required 
                       value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($usuario['email']); ?>">
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" 
                       value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>"
                       placeholder="Ejemplo: 7890-1234">
            </div>

            <div class="form-group">
                <label for="direccion">Dirección</label>
                <textarea id="direccion" name="direccion" 
                          placeholder="Ingresa tu dirección completa"><?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?></textarea>
            </div>

            <div class="password-section">
                <h3><i class="fas fa-lock"></i> Cambiar Contraseña</h3>
                <p class="help-text" style="margin-bottom: 15px;">
                    Deja este campo vacío si no deseas cambiar tu contraseña
                </p>

                <div class="form-group">
                    <label for="password">Nueva Contraseña</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Dejar vacío para mantener contraseña actual">
                    <div class="help-text">Mínimo 6 caracteres</div>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirmar Nueva Contraseña</label>
                    <input type="password" id="password_confirm" name="password_confirm" 
                           placeholder="Confirma la nueva contraseña">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="index.php?ruta=perfil" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Validar que las contraseñas coincidan
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirm').value;
    
    if (password && password !== confirm) {
        e.preventDefault();
        alert('Las contraseñas no coinciden');
        return false;
    }
    
    if (password && password.length < 6) {
        e.preventDefault();
        alert('La contraseña debe tener al menos 6 caracteres');
        return false;
    }
});
</script>

</body>
</html>
