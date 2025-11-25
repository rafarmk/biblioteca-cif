<?php
$page_title = "Nuevo Usuario - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header('Location: index.php?ruta=login');
    exit();
}

require_once __DIR__ . '/../layouts/navbar.php';
?>

<style>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 30px 20px;
}

.card {
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px var(--shadow-color);
}

h1 {
    color: var(--text-primary);
    font-size: 2rem;
    margin-bottom: 10px;
    text-align: center;
}

.subtitle {
    color: var(--text-secondary);
    text-align: center;
    margin-bottom: 30px;
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

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
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
    <div class="card">
        <h1>➕ Nuevo Usuario</h1>
        <p class="subtitle">Completa los datos del nuevo usuario</p>

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

        <form action="index.php?ruta=usuarios&accion=crear" method="POST">
            <div class="form-group">
                <label class="form-label required">Nombre Completo</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label required">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Tipo de Usuario</label>
                    <select name="tipo_usuario" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <option value="visitante">👥 Visitante</option>
                        <option value="personal_operativo">👮 Personal Operativo</option>
                        <option value="personal_administrativo">📋 Personal Administrativo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">DUI / ONI / Código</label>
                    <input type="text" name="documento" class="form-control">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="tel" name="telefono" class="form-control" placeholder="0000-0000">
                </div>

                <div class="form-group">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Contraseña</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>

                <div class="form-group">
                    <label class="form-label required">Confirmar Contraseña</label>
                    <input type="password" name="password_confirm" class="form-control" required minlength="6">
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Usuario
                </button>
                <a href="index.php?ruta=usuarios" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
