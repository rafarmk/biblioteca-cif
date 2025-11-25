<?php
$page_title = "Editar Usuario - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header('Location: index.php?ruta=login');
    exit();
}

require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php?ruta=usuarios');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header('Location: index.php?ruta=usuarios');
    exit;
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

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 20px 0;
}

.checkbox-group input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.checkbox-group label {
    color: var(--text-primary);
    cursor: pointer;
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

.info-box {
    background: rgba(59, 130, 246, 0.1);
    border: 2px solid var(--accent-primary);
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
}

.info-box p {
    color: var(--text-secondary);
    margin: 5px 0;
}
</style>

<div class="container">
    <div class="card">
        <h1>✏️ Editar Usuario</h1>
        <p class="subtitle">Modifica los datos del usuario</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="info-box">
            <p><strong>Usuario:</strong> <?= htmlspecialchars($usuario['nombre'] ?? '') ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email'] ?? '') ?></p>
        </div>

        <form action="index.php?ruta=usuarios&accion=editar" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">

            <div class="form-group">
                <label class="form-label required">Nombre Completo</label>
                <input type="text" name="nombre" class="form-control" 
                       value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="tel" name="telefono" class="form-control" 
                           value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>" 
                           placeholder="0000-0000">
                </div>

                <div class="form-group">
                    <label class="form-label">DUI</label>
                    <input type="text" name="documento" class="form-control" 
                           value="<?= htmlspecialchars($usuario['documento'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Tipo de Usuario</label>
                    <select name="tipo_usuario" class="form-control" required>
                        <option value="visitante" <?= ($usuario['tipo_usuario'] ?? '') == 'visitante' ? 'selected' : '' ?>>
                            👥 Visitante
                        </option>
                        <option value="personal_operativo" <?= ($usuario['tipo_usuario'] ?? '') == 'personal_operativo' ? 'selected' : '' ?>>
                            👮 Personal Operativo
                        </option>
                        <option value="personal_administrativo" <?= ($usuario['tipo_usuario'] ?? '') == 'personal_administrativo' ? 'selected' : '' ?>>
                            📋 Personal Administrativo
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label required">Estado</label>
                    <select name="estado" class="form-control" required>
                        <option value="activo" <?= ($usuario['estado'] ?? '') == 'activo' ? 'selected' : '' ?>>
                            ✅ Activo
                        </option>
                        <option value="inactivo" <?= ($usuario['estado'] ?? '') == 'inactivo' ? 'selected' : '' ?>>
                            ❌ Inactivo
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" 
                       value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Días Máximos de Préstamo</label>
                    <input type="number" name="dias_prestamo" class="form-control" 
                           value="<?= htmlspecialchars($usuario['dias_prestamo'] ?? '7') ?>" 
                           min="1" max="90">
                </div>

                <div class="form-group">
                    <label class="form-label">Máximo de Libros Simultáneos</label>
                    <input type="number" name="max_libros" class="form-control" 
                           value="<?= htmlspecialchars($usuario['max_libros'] ?? '3') ?>" 
                           min="1" max="10">
                </div>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="puede_prestar" name="puede_prestar" value="1" 
                       <?= (isset($usuario['puede_prestar']) && $usuario['puede_prestar']) ? 'checked' : '' ?>>
                <label for="puede_prestar">Puede realizar préstamos</label>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="index.php?ruta=usuarios" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
