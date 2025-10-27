<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<style>
.main-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
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
    border: 2px solid var(--border-color);
}

.page-header h1 {
    font-size: 2rem;
    color: var(--text-primary);
    font-weight: 700;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
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

.btn-primary {
    background: var(--primary);
    color: white;
    width: 100%;
    padding: 15px;
    font-size: 16px;
}

.content-card {
    background: var(--bg-card);
    border-radius: 16px;
    box-shadow: 0 4px 20px var(--shadow);
    border: 2px solid var(--border-color);
    padding: 40px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-primary);
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 14px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    border: 2px solid #fecaca;
}
</style>

<div class="main-container">
    <div class="page-header">
        <h1> Editar Usuario</h1>
        <a href="index.php?ruta=usuarios" class="btn btn-secondary">
             Volver
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert-error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="content-card">
        <?php if (isset($usuario)): ?>
        <form action="index.php?ruta=usuarios&accion=editar&id=<?php echo $usuario['id']; ?>" method="POST">
            
            <div class="form-group">
                <label for="nombre"> Nombre Completo *</label>
                <input type="text" name="nombre" id="nombre" required
                       value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email"> Correo Electrónico</label>
                    <input type="email" name="email" id="email"
                           value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="telefono"> Teléfono</label>
                    <input type="tel" name="telefono" id="telefono"
                           value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="tipo_usuario"> Tipo de Usuario *</label>
                <select name="tipo_usuario" id="tipo_usuario" required>
                    <option value="estudiante_mayor" <?php echo ($usuario['tipo_usuario'] ?? '') == 'estudiante_mayor' ? 'selected' : ''; ?>>Estudiante Mayor</option>
                    <option value="estudiante" <?php echo ($usuario['tipo_usuario'] ?? '') == 'estudiante' ? 'selected' : ''; ?>>Estudiante</option>
                    <option value="docente" <?php echo ($usuario['tipo_usuario'] ?? '') == 'docente' ? 'selected' : ''; ?>>Docente</option>
                    <option value="personal" <?php echo ($usuario['tipo_usuario'] ?? '') == 'personal' ? 'selected' : ''; ?>>Personal</option>
                </select>
            </div>

            <div class="form-group">
                <label for="direccion"> Dirección</label>
                <input type="text" name="direccion" id="direccion"
                       value="<?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="oni"> ONI</label>
                    <input type="text" name="oni" id="oni"
                           value="<?php echo htmlspecialchars($usuario['oni'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="dui"> DUI</label>
                    <input type="text" name="dui" id="dui"
                           value="<?php echo htmlspecialchars($usuario['dui'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="estado"> Estado</label>
                <select name="estado" id="estado">
                    <option value="activo" <?php echo ($usuario['estado'] ?? 'activo') == 'activo' ? 'selected' : ''; ?>>Activo</option>
                    <option value="inactivo" <?php echo ($usuario['estado'] ?? '') == 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                 Actualizar Usuario
            </button>
        </form>
        <?php else: ?>
        <div style="text-align: center; padding: 60px 20px;">
            <h3> Usuario no encontrado</h3>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>