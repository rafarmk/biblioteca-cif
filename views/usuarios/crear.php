<?php require_once 'views/layouts/navbar.php'; ?>

<style>
/* Formulario Moderno */
.modern-form-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 0 20px;
}

.form-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 40px;
    border: 2px solid var(--border-color);
    box-shadow: 0 8px 32px var(--shadow);
}

.form-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border-color);
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
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #95a5a6;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid var(--border-color);
}

.btn-primary-modern {
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
    gap: 10px;
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
}

.btn-secondary-modern {
    padding: 14px 32px;
    background: var(--bg-secondary);
    color: var(--text-primary);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-secondary-modern:hover {
    background: var(--bg-card);
    border-color: var(--primary);
}

/* Modo Oscuro */
[data-theme="dark"] .form-group input,
[data-theme="dark"] .form-group textarea {
    background: #2d3748;
    color: #ffffff;
    border-color: #4a5568;
}

[data-theme="dark"] .form-group input:focus,
[data-theme="dark"] .form-group textarea:focus {
    border-color: #3b82f6;
}
</style>

<div class="modern-form-container">
    <div class="form-card">
        <div class="form-header">
            <i class="fas fa-user-plus"></i>
            <h2>Crear Nuevo Usuario</h2>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?ruta=usuarios&accion=crear">
            <div class="form-group">
                <label>Nombre Completo *</label>
                <input type="text" name="nombre" placeholder="Ingrese el nombre completo" required>
            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" placeholder="correo@ejemplo.com" required>
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" placeholder="(503) 0000-0000">
            </div>

            <div class="form-group">
                <label>Dirección</label>
                <textarea name="direccion" rows="4" placeholder="Dirección completa"></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary-modern">
                    <i class="fas fa-save"></i>
                    Guardar
                </button>
                <a href="index.php?ruta=usuarios" class="btn-secondary-modern">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
