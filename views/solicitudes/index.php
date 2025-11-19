<?php
$page_title = "Solicitudes de Acceso - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

// Obtener filtro
$filtro = $_GET['filtro'] ?? 'pendiente';

// Obtener solicitudes (usuarios pendientes)
require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

$query = "SELECT * FROM usuarios WHERE tipo_usuario != 'administrador'";

if ($filtro === 'pendiente') {
    $query .= " AND estado = 'pendiente'";
} elseif ($filtro === 'activo') {
    $query .= " AND estado = 'activo'";
} elseif ($filtro === 'inactivo') {
    $query .= " AND estado = 'inactivo'";
}

$query .= " ORDER BY id DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
.main-container {
    max-width: 1400px;
    margin: 40px auto;
    padding: 0 20px;
}

.page-header {
    background: var(--bg-secondary);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px var(--shadow-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    border: 2px solid var(--border-color);
}

.page-title {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 32px;
    font-weight: 700;
    color: var(--text-primary);
}

.page-title i {
    color: var(--accent-primary);
}

.filter-buttons {
    display: flex;
    gap: 10px;
}

.filter-btn {
    padding: 10px 20px;
    border: 2px solid var(--border-color);
    background: var(--bg-secondary);
    color: var(--text-secondary);
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
}

.filter-btn:hover {
    background: var(--bg-tertiary);
    border-color: var(--accent-primary);
    color: var(--accent-primary);
    transform: translateY(-2px);
}

.filter-btn.active {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    border-color: var(--accent-primary);
    color: white;
    box-shadow: 0 0 20px var(--glow-color);
}

.solicitudes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
}

.solicitud-card {
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 25px;
    transition: all 0.3s ease;
}

.solicitud-card:hover {
    transform: translateY(-5px);
    border-color: var(--accent-primary);
    box-shadow: 0 10px 30px var(--shadow-color);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 20px;
}

.user-info h3 {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 5px;
}

.badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-pendiente {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: white;
}

.badge-activo {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.badge-inactivo {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
}

.card-details {
    margin: 15px 0;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    color: var(--text-secondary);
    font-size: 14px;
}

.detail-item i {
    color: var(--accent-primary);
    width: 20px;
}

.card-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn {
    flex: 1;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    text-decoration: none;
    display: inline-block;
}

.btn-aprobar {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-aprobar:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(16, 185, 129, 0.4);
}

.btn-rechazar {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.btn-rechazar:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(239, 68, 68, 0.4);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-secondary);
}

.empty-state i {
    font-size: 64px;
    color: var(--accent-primary);
    margin-bottom: 20px;
}

/* Alerta de éxito/error */
.alert {
    position: fixed;
    top: 100px;
    right: 20px;
    padding: 15px 25px;
    border-radius: 8px;
    font-weight: 600;
    z-index: 9999;
    animation: slideIn 0.3s ease;
}

.alert-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 5px 20px rgba(16, 185, 129, 0.4);
}

.alert-error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 5px 20px rgba(239, 68, 68, 0.4);
}

@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>

<div class="main-container">
    <!-- Mensajes de alerta -->
    <?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-success" id="alertMessage">
        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['mensaje']; ?>
    </div>
    <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error" id="alertMessage">
        <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-user-clock"></i>
            Solicitudes de Acceso
        </h1>
        
        <div class="filter-buttons">
            <a href="index.php?ruta=solicitudes&filtro=pendiente" class="filter-btn <?php echo $filtro === 'pendiente' ? 'active' : ''; ?>">
                <i class="fas fa-clock"></i> Pendientes
            </a>
            <a href="index.php?ruta=solicitudes&filtro=activo" class="filter-btn <?php echo $filtro === 'activo' ? 'active' : ''; ?>">
                <i class="fas fa-check"></i> Aprobadas
            </a>
            <a href="index.php?ruta=solicitudes&filtro=inactivo" class="filter-btn <?php echo $filtro === 'inactivo' ? 'active' : ''; ?>">
                <i class="fas fa-times"></i> Rechazadas
            </a>
            <a href="index.php?ruta=solicitudes&filtro=todas" class="filter-btn <?php echo $filtro === 'todas' ? 'active' : ''; ?>">
                <i class="fas fa-list"></i> Todas
            </a>
        </div>
    </div>

    <!-- Grid de solicitudes -->
    <?php if (count($solicitudes) > 0): ?>
    <div class="solicitudes-grid">
        <?php foreach ($solicitudes as $solicitud): ?>
        <div class="solicitud-card">
            <div class="card-header">
                <div class="user-info">
                    <h3><?php echo htmlspecialchars($solicitud['nombre']); ?></h3>
                    <span class="badge badge-<?php echo $solicitud['estado']; ?>">
                        <?php echo strtoupper($solicitud['estado']); ?>
                    </span>
                </div>
            </div>

            <div class="card-details">
                <div class="detail-item">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo htmlspecialchars($solicitud['email']); ?></span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-phone"></i>
                    <span><?php echo htmlspecialchars($solicitud['telefono'] ?? 'No especificado'); ?></span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-user-tag"></i>
                    <span><?php echo htmlspecialchars($solicitud['tipo_usuario']); ?></span>
                </div>
            </div>

            <?php if ($solicitud['estado'] === 'pendiente'): ?>
            <div class="card-actions">
                <a href="index.php?ruta=solicitudes/aprobar&id=<?php echo $solicitud['id']; ?>" 
                   class="btn btn-aprobar"
                   onclick="return confirm('¿Aprobar acceso a este usuario?')">
                    <i class="fas fa-check"></i> Aprobar
                </a>
                <a href="index.php?ruta=solicitudes/rechazar&id=<?php echo $solicitud['id']; ?>" 
                   class="btn btn-rechazar"
                   onclick="return confirm('¿Rechazar esta solicitud?')">
                    <i class="fas fa-times"></i> Rechazar
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h2>No hay solicitudes <?php echo $filtro !== 'todas' ? $filtro . 's' : ''; ?></h2>
        <p>No se encontraron solicitudes con el filtro seleccionado.</p>
    </div>
    <?php endif; ?>
</div>

<script>
// Ocultar alerta después de 3 segundos
setTimeout(function() {
    const alert = document.getElementById('alertMessage');
    if (alert) {
        alert.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => alert.remove(), 300);
    }
}, 3000);
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>