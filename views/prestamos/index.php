<?php
$page_title = "Gestión de Préstamos - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

// Obtener filtro
$filtro = $_GET['filtro'] ?? 'todos';

// Calcular estadísticas
$stmt = $conn->query("SELECT COUNT(*) as total FROM prestamos");
$totalPrestamos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'");
$prestamosActivos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("
    SELECT COUNT(*) as total
    FROM prestamos
    WHERE estado = 'activo'
    AND fecha_devolucion_esperada < CURDATE()
");
$prestamosAtrasados = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Obtener lista de préstamos según filtro
$query = "
    SELECT
        p.*,
        u.nombre as usuario_nombre,
        u.email as usuario_email,
        l.titulo as libro_titulo,
        l.autor as libro_autor,
        l.isbn,
        DATEDIFF(p.fecha_devolucion_esperada, CURDATE()) as dias_restantes
    FROM prestamos p
    INNER JOIN usuarios u ON p.usuario_id = u.id
    INNER JOIN libros l ON p.libro_id = l.id
";

if ($filtro == 'activos') {
    $query .= " WHERE p.estado = 'activo' AND p.fecha_devolucion_esperada >= CURDATE()";
} elseif ($filtro == 'atrasados') {
    $query .= " WHERE p.estado = 'activo' AND p.fecha_devolucion_esperada < CURDATE()";
}

$query .= " ORDER BY p.fecha_prestamo DESC";

$stmt = $conn->query($query);
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../layouts/navbar.php';
?>

<style>
.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px 20px;
}

.header {
    text-align: center;
    margin-bottom: 40px;
}

.header h1 {
    font-size: 2.5rem;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.header p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 30px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 15px var(--shadow-color);
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
    color: white;
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-secondary {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    border: 2px solid var(--border-color);
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 25px var(--shadow-color);
}

.btn.active {
    box-shadow: 0 0 0 3px var(--accent-primary);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.stat-card {
    background: var(--bg-secondary);
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 8px 20px var(--shadow-color);
    transition: all 0.3s;
    border: 2px solid var(--border-color);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px var(--shadow-color);
}

.stat-icon {
    font-size: 3rem;
    margin-bottom: 15px;
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--text-primary);
}

.stat-label {
    color: var(--text-secondary);
    font-size: 1.1rem;
    font-weight: 600;
}

.table-container {
    background: var(--bg-secondary);
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 8px 20px var(--shadow-color);
    overflow-x: auto;
}

.table-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 25px;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

thead {
    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
    color: white;
}

th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    font-size: 0.95rem;
}

td {
    padding: 15px;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-primary);
}

tbody tr {
    transition: all 0.3s;
}

tbody tr:hover {
    background: var(--bg-tertiary);
}

.badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
}

.badge-activo {
    background: #d1fae5;
    color: #065f46;
}

.badge-devuelto {
    background: #e0e7ff;
    color: #3730a3;
}

.badge-atrasado {
    background: #fee2e2;
    color: #991b1b;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.btn-small {
    padding: 6px 14px;
    font-size: 0.85rem;
    border-radius: 8px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-secondary);
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
</style>

<div class="container">
    <div class="header">
        <h1>📚 Gestión de Préstamos</h1>
        <p>Administra todos los préstamos de libros del sistema</p>
    </div>

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

    <div class="actions">
        <a href="index.php?ruta=prestamos/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Préstamo
        </a>
        <a href="index.php?ruta=prestamos" class="btn btn-secondary <?= $filtro == 'todos' ? 'active' : '' ?>">
            <i class="fas fa-list"></i> Todos
        </a>
        <a href="index.php?ruta=prestamos&filtro=activos" class="btn btn-success <?= $filtro == 'activos' ? 'active' : '' ?>">
            <i class="fas fa-check-circle"></i> Préstamos Activos
        </a>
        <a href="index.php?ruta=prestamos&filtro=atrasados" class="btn btn-danger <?= $filtro == 'atrasados' ? 'active' : '' ?>">
            <i class="fas fa-exclamation-triangle"></i> Atrasados
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-number" style="color: var(--accent-primary);"><?= $totalPrestamos ?></div>
            <div class="stat-label">Total de Préstamos</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-number" style="color: #10b981;"><?= $prestamosActivos ?></div>
            <div class="stat-label">Préstamos Activos</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⚠️</div>
            <div class="stat-number" style="color: #ef4444;"><?= $prestamosAtrasados ?></div>
            <div class="stat-label">Préstamos Atrasados</div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-title">📋 Lista de Préstamos 
            <?php if ($filtro == 'activos'): ?>
                <span style="color: #10b981;">(Activos)</span>
            <?php elseif ($filtro == 'atrasados'): ?>
                <span style="color: #ef4444;">(Atrasados)</span>
            <?php else: ?>
                <span style="color: var(--text-secondary);">(Todos)</span>
            <?php endif; ?>
        </div>

        <?php if (empty($prestamos)): ?>
            <div class="empty-state">
                <div style="font-size: 4rem; margin-bottom: 20px;">📚</div>
                <h3>No hay préstamos registrados</h3>
                <p>Comienza creando un nuevo préstamo</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Libro</th>
                        <th>ISBN</th>
                        <th>Fecha Préstamo</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prestamos as $prestamo): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($prestamo['usuario_nombre'] ?? '') ?></strong><br>
                                <small style="color: var(--text-secondary);"><?= htmlspecialchars($prestamo['usuario_email'] ?? '') ?></small>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($prestamo['libro_titulo'] ?? 'Sin título') ?></strong><br>
                                <small style="color: var(--text-secondary);"><?= htmlspecialchars($prestamo['libro_autor'] ?? 'Sin autor') ?></small>
                            </td>
                            <td><?= htmlspecialchars($prestamo['isbn'] ?? 'N/A') ?></td>
                            <td><?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?></td>
                            <td><?= $prestamo['fecha_devolucion_real'] ? date('d/m/Y', strtotime($prestamo['fecha_devolucion_real'])) : date('d/m/Y', strtotime($prestamo['fecha_devolucion_esperada'])) ?></td>
                            <td>
                                <?php if ($prestamo['estado'] == 'activo'): ?>
                                    <?php if (isset($prestamo['dias_restantes']) && $prestamo['dias_restantes'] < 0): ?>
                                        <span class="badge badge-atrasado">
                                            ⚠️ Atrasado (<?= abs($prestamo['dias_restantes']) ?> días)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-activo">
                                            ✅ Activo (<?= $prestamo['dias_restantes'] ?? 0 ?> días)
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-devuelto">📥 Devuelto</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($prestamo['estado'] == 'activo'): ?>
                                    <a href="index.php?ruta=prestamos/devolver&id=<?= $prestamo['id'] ?>"
                                       class="btn btn-success btn-small"
                                       onclick="return confirm('¿Marcar este préstamo como devuelto?')">
                                        <i class="fas fa-check"></i> Devolver
                                    </a>
                                <?php else: ?>
                                    <span style="color: #10b981; font-weight: 600;">
                                        <i class="fas fa-check-circle"></i> Completado
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
