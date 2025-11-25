<?php
$page_title = "Calificaciones de Usuarios - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['logueado']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

// ✅ CORREGIDO: Usar != 'administrador' en lugar de = 'comun'
$stmt = $conn->prepare("
    INSERT IGNORE INTO comportamiento_usuarios (usuario_id, puntos_totales, nivel)
    SELECT id, 100, 'bueno' 
    FROM usuarios 
    WHERE tipo_usuario != 'administrador'
");
$stmt->execute();

$filtro_nivel = $_GET['nivel'] ?? 'todos';

// ✅ CORREGIDO: WHERE tipo_usuario != 'administrador'
$query = "
    SELECT 
        u.id, 
        u.nombre, 
        u.apellido, 
        u.email,
        u.tipo_usuario,
        c.puntos_totales,
        c.nivel,
        c.total_prestamos_completados as total_prestamos,
        c.total_retrasos,
        c.prestamos_consecutivos_a_tiempo as consecutivos,
        (SELECT COUNT(*) FROM prestamos WHERE usuario_id = u.id AND estado = 'activo') as prestamos_activos
    FROM usuarios u
    INNER JOIN comportamiento_usuarios c ON u.id = c.usuario_id
    WHERE u.tipo_usuario != 'administrador'
";

if ($filtro_nivel !== 'todos') {
    $query .= " AND c.nivel = :nivel";
}

$query .= " ORDER BY c.puntos_totales DESC, u.apellido ASC";

$stmt = $conn->prepare($query);
if ($filtro_nivel !== 'todos') {
    $stmt->bindParam(':nivel', $filtro_nivel);
}
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN nivel = 'excelente' THEN 1 ELSE 0 END) as excelente,
        SUM(CASE WHEN nivel = 'bueno' THEN 1 ELSE 0 END) as bueno,
        SUM(CASE WHEN nivel = 'regular' THEN 1 ELSE 0 END) as regular,
        SUM(CASE WHEN nivel = 'bajo' THEN 1 ELSE 0 END) as bajo,
        SUM(CASE WHEN nivel = 'suspendido' THEN 1 ELSE 0 END) as suspendido,
        AVG(puntos_totales) as promedio_puntos
    FROM comportamiento_usuarios
");
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

$niveles = [
    'excelente' => ['nombre' => 'Excelente', 'color' => '#10b981', 'icono' => '🌟'],
    'bueno' => ['nombre' => 'Bueno', 'color' => '#3b82f6', 'icono' => '✅'],
    'regular' => ['nombre' => 'Regular', 'color' => '#f59e0b', 'icono' => '⚠️'],
    'bajo' => ['nombre' => 'Bajo', 'color' => '#ef4444', 'icono' => '🔴'],
    'suspendido' => ['nombre' => 'Suspendido', 'color' => '#991b1b', 'icono' => '🚫']
];

$tipo_usuario_labels = [
    'personal_administrativo' => '👔 Administrativo',
    'personal_operativo' => '⚙️ Operativo',
    'visitante' => '👤 Visitante'
];

require_once __DIR__ . '/../layouts/navbar.php';
?>

<style>
body { padding-top: 100px; padding-bottom: 50px; background: var(--bg-primary); }
.container { max-width: 1400px; margin: 0 auto; padding: 30px 20px; }
.header { margin-bottom: 30px; }
.header h1 { font-size: 2rem; color: var(--text-primary); margin-bottom: 10px; }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.stat-card {
    background: var(--bg-card);
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 2px 10px var(--shadow-color);
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent-primary);
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-top: 5px;
}

.filters {
    background: var(--bg-card);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
    box-shadow: 0 2px 10px var(--shadow-color);
}

.filter-label {
    font-weight: 600;
    color: var(--text-primary);
}

.filter-btn {
    padding: 8px 20px;
    border: 2px solid var(--border-color);
    background: var(--bg-secondary);
    color: var(--text-primary);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    font-weight: 500;
}

.filter-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px var(--shadow-color);
}

.filter-btn.active {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
}

.table-container {
    background: var(--bg-card);
    border-radius: 12px;
    overflow-x: auto;
    box-shadow: 0 2px 10px var(--shadow-color);
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: var(--bg-secondary);
}

th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: var(--text-primary);
    border-bottom: 2px solid var(--border-color);
    white-space: nowrap;
}

td {
    padding: 15px;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-primary);
}

tbody tr:hover {
    background: var(--bg-secondary);
}

.nivel-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    white-space: nowrap;
}

.tipo-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 500;
    background: var(--bg-secondary);
    color: var(--text-secondary);
    margin-top: 3px;
}

.puntos {
    font-size: 1.3rem;
    font-weight: 700;
}

.btn-sm {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.85rem;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    font-weight: 500;
    white-space: nowrap;
}

.btn-warning {
    background: #f59e0b;
    color: white;
}

.btn-warning:hover {
    background: #d97706;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
}

.actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background: var(--bg-card);
    margin: 5% auto;
    padding: 30px;
    border-radius: 15px;
    max-width: 500px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
    animation: slideDown 0.3s;
}

@keyframes slideDown {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.modal-header h2 {
    color: var(--text-primary);
    font-size: 1.5rem;
}

.close {
    font-size: 2rem;
    cursor: pointer;
    color: var(--text-secondary);
    border: none;
    background: none;
}

.close:hover {
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
}

.form-group select,
.form-group textarea,
.form-group input[type="text"] {
    width: 100%;
    padding: 12px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    background: var(--bg-input);
    color: var(--text-primary);
    font-size: 1rem;
    box-sizing: border-box;
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    width: 100%;
}

.btn-primary {
    background: var(--accent-primary);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px var(--accent-shadow);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-secondary);
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filters {
        flex-direction: column;
        align-items: stretch;
    }
    
    table {
        font-size: 0.85rem;
    }
    
    th, td {
        padding: 10px;
    }
    
    .actions {
        flex-direction: column;
    }
}
</style>

<div class="container">
    <div class="header">
        <h1>📊 Calificaciones de Usuarios</h1>
        <p>Monitorea el comportamiento de los lectores</p>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?>" style="padding: 15px; border-radius: 8px; margin-bottom: 20px; background: <?= $_SESSION['mensaje']['tipo'] === 'success' ? '#d1fae5' : '#fee2e2' ?>; color: <?= $_SESSION['mensaje']['tipo'] === 'success' ? '#065f46' : '#991b1b' ?>;">
            <?= $_SESSION['mensaje']['texto'] ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?= $stats['total'] ?? 0 ?></div>
            <div class="stat-label">Total Usuarios</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['excelente'] ?? 0 ?></div>
            <div class="stat-label">🌟 Excelentes</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['bueno'] ?? 0 ?></div>
            <div class="stat-label">✅ Buenos</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['regular'] ?? 0 ?></div>
            <div class="stat-label">⚠️ Regulares</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['bajo'] ?? 0 ?></div>
            <div class="stat-label">🔴 Bajos</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['suspendido'] ?? 0 ?></div>
            <div class="stat-label">🚫 Suspendidos</div>
        </div>
    </div>

    <div class="filters">
        <span class="filter-label">Filtrar por nivel:</span>
        <a href="index.php?ruta=calificaciones_usuarios" class="filter-btn <?= $filtro_nivel === 'todos' ? 'active' : '' ?>">Todos</a>
        <a href="index.php?ruta=calificaciones_usuarios&nivel=excelente" class="filter-btn <?= $filtro_nivel === 'excelente' ? 'active' : '' ?>">🌟 Excelente</a>
        <a href="index.php?ruta=calificaciones_usuarios&nivel=bueno" class="filter-btn <?= $filtro_nivel === 'bueno' ? 'active' : '' ?>">✅ Bueno</a>
        <a href="index.php?ruta=calificaciones_usuarios&nivel=regular" class="filter-btn <?= $filtro_nivel === 'regular' ? 'active' : '' ?>">⚠️ Regular</a>
        <a href="index.php?ruta=calificaciones_usuarios&nivel=bajo" class="filter-btn <?= $filtro_nivel === 'bajo' ? 'active' : '' ?>">🔴 Bajo</a>
        <a href="index.php?ruta=calificaciones_usuarios&nivel=suspendido" class="filter-btn <?= $filtro_nivel === 'suspendido' ? 'active' : '' ?>">🚫 Suspendido</a>
    </div>

    <div class="table-container">
        <?php if (empty($usuarios)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <p>No hay usuarios registrados<?= $filtro_nivel !== 'todos' ? ' con este nivel' : '' ?></p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Nivel</th>
                        <th>Puntos</th>
                        <th>Préstamos</th>
                        <th>Retrasos</th>
                        <th>Activos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <?php $nivel = $niveles[$u['nivel']]; ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($u['nombre'] . ' ' . ($u['apellido'] ?? '')) ?></strong><br>
                                <small style="color: var(--text-secondary);"><?= htmlspecialchars($u['email']) ?></small><br>
                                <span class="tipo-badge"><?= $tipo_usuario_labels[$u['tipo_usuario']] ?? $u['tipo_usuario'] ?></span>
                            </td>
                            <td>
                                <span class="nivel-badge" style="background: <?= $nivel['color'] ?>20; color: <?= $nivel['color'] ?>;">
                                    <?= $nivel['icono'] ?> <?= $nivel['nombre'] ?>
                                </span>
                            </td>
                            <td>
                                <span class="puntos" style="color: <?= $nivel['color'] ?>;">
                                    <?= $u['puntos_totales'] ?>
                                </span>
                            </td>
                            <td><?= $u['total_prestamos'] ?></td>
                            <td><?= $u['total_retrasos'] ?></td>
                            <td><?= $u['prestamos_activos'] ?></td>
                            <td>
                                <div class="actions">
                                    <button class="btn-sm btn-warning" onclick="abrirModal(<?= $u['id'] ?>, '<?= htmlspecialchars($u['nombre'] . ' ' . ($u['apellido'] ?? '')) ?>')">
                                        ⚠️ Registrar
                                    </button>
                                    <?php if ($u['nivel'] === 'suspendido'): ?>
                                        <a href="index.php?ruta=reactivar_usuario&usuario_id=<?= $u['id'] ?>" class="btn-sm btn-success" onclick="return confirm('¿Reactivar a <?= htmlspecialchars($u['nombre']) ?>?')">
                                            ✅ Reactivar
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Registrar Incidente -->
<div id="modalIncidente" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Registrar Incidente</h2>
            <button class="close" onclick="cerrarModal()">&times;</button>
        </div>
        <form method="POST" action="index.php?ruta=registrar_incidente">
            <input type="hidden" name="usuario_id" id="usuario_id">
            
            <div class="form-group">
                <label>Usuario:</label>
                <input type="text" id="usuario_nombre" readonly>
            </div>
            
            <div class="form-group">
                <label>Tipo de Incidente:</label>
                <select name="tipo_incidente" required>
                    <option value="libro_danado">📕 Libro Dañado (-30 pts)</option>
                    <option value="libro_perdido">❌ Libro Perdido (-100 pts)</option>
                    <option value="advertencia">⚠️ Advertencia General (-10 pts)</option>
                    <option value="recompensa">🎁 Recompensa (+50 pts)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Descripción:</label>
                <textarea name="descripcion" required placeholder="Describe el incidente..."></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">✅ Registrar Incidente</button>
        </form>
    </div>
</div>

<script>
function abrirModal(userId, userName) {
    document.getElementById('usuario_id').value = userId;
    document.getElementById('usuario_nombre').value = userName;
    document.getElementById('modalIncidente').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modalIncidente').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('modalIncidente');
    if (event.target == modal) {
        cerrarModal();
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>