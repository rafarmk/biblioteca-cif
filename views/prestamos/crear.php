<?php
$page_title = "Nuevo Préstamo - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

// Obtener usuarios activos
$stmt = $conn->query("SELECT id, nombre, email, tipo_usuario FROM usuarios WHERE estado = 'activo' AND tipo_usuario != 'administrador' ORDER BY nombre");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener libros disponibles
$stmt = $conn->query("SELECT id, titulo, autor, isbn, cantidad_disponible FROM libros WHERE cantidad_disponible > 0 ORDER BY titulo");
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../layouts/navbar.php';
?>

<style>
.container {
    max-width: 800px;
    margin: 40px auto;
    padding: 0 20px;
}

.form-card {
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 15px 40px var(--shadow-color);
}

.form-header {
    text-align: center;
    margin-bottom: 35px;
}

.form-header h1 {
    color: var(--text-primary);
    font-size: 2rem;
    margin-bottom: 10px;
}

.form-header p {
    color: var(--text-secondary);
}

.form-group {
    margin-bottom: 25px;
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

select.form-control {
    cursor: pointer;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.info-box {
    background: rgba(59, 130, 246, 0.1);
    border: 2px solid var(--accent-primary);
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 25px;
}

.info-box p {
    color: var(--text-secondary);
    margin: 0;
}

.btn-group {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn {
    padding: 14px 30px;
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

.alert-warning {
    background: #fef3c7;
    color: #92400e;
    border: 2px solid #f59e0b;
}
</style>

<div class="container">
    <div class="form-card">
        <div class="form-header">
            <h1>📚 Nuevo Préstamo</h1>
            <p>Registra un nuevo préstamo de libro</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <?php 
            if (is_array($_SESSION['mensaje'])) {
                $mensaje = $_SESSION['mensaje']['texto'] ?? '';
                $tipo = $_SESSION['mensaje']['tipo'] ?? 'success';
            } else {
                $mensaje = $_SESSION['mensaje'];
                $tipo = 'success';
            }
            ?>
            <div class="alert alert-<?= $tipo ?>">
                <?= htmlspecialchars($mensaje) ?>
            </div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <div class="info-box">
            <p><strong>⏰ Tiempo de préstamo:</strong> 15 días automáticamente</p>
        </div>

        <form action="index.php?ruta=prestamos&accion=crear" method="POST">
            <div class="form-group">
                <label class="form-label required">Usuario</label>
                <select name="usuario_id" class="form-control" required>
                    <option value="">Seleccione un usuario...</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?= $usuario['id'] ?>">
                            <?= htmlspecialchars($usuario['nombre']) ?> - 
                            <?= htmlspecialchars($usuario['email']) ?>
                            (<?= htmlspecialchars($usuario['tipo_usuario']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label required">Libro</label>
                <select name="libro_id" class="form-control" required>
                    <option value="">Seleccione un libro...</option>
                    <?php foreach ($libros as $libro): ?>
                        <option value="<?= $libro['id'] ?>">
                            <?= htmlspecialchars($libro['titulo']) ?> - 
                            <?= htmlspecialchars($libro['autor']) ?>
                            (Disponibles: <?= $libro['cantidad_disponible'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Fecha de Préstamo</label>
                    <input type="date" name="fecha_prestamo" class="form-control" 
                           value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Fecha de Devolución</label>
                    <input type="date" name="fecha_devolucion" class="form-control" 
                           value="<?= date('Y-m-d', strtotime('+15 days')) ?>" required>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Registrar Préstamo
                </button>
                <a href="index.php?ruta=prestamos" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
