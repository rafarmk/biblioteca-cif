<?php
$page_title = "Dashboard - Biblioteca CIF";
require_once __DIR__ . '/layouts/header.php';

// Verificar que sea administrador
if (!isset($_SESSION['logueado']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header('Location: index.php?ruta=login');
    exit;
}

// Obtener estadísticas
require_once __DIR__ . '/../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Total de libros
$query = "SELECT COUNT(*) as total FROM libros";
$stmt = $db->prepare($query);
$stmt->execute();
$totalLibros = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Libros disponibles
$query = "SELECT COUNT(*) as total FROM libros WHERE estado = 'disponible'";
$stmt = $db->prepare($query);
$stmt->execute();
$librosDisponibles = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Total de usuarios activos
$query = "SELECT COUNT(*) as total FROM usuarios WHERE estado = 'activo'";
$stmt = $db->prepare($query);
$stmt->execute();
$totalUsuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Usuarios pendientes de aprobación
$query = "SELECT COUNT(*) as total FROM usuarios WHERE estado = 'pendiente'";
$stmt = $db->prepare($query);
$stmt->execute();
$usuariosPendientes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Préstamos activos
$query = "SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'";
$stmt = $db->prepare($query);
$stmt->execute();
$prestamosActivos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Préstamos atrasados
$query = "SELECT COUNT(*) as total FROM prestamos 
          WHERE estado = 'activo' 
          AND fecha_devolucion_esperada < CURDATE()";
$stmt = $db->prepare($query);
$stmt->execute();
$prestamosAtrasados = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Usuarios pendientes (últimos 5) - SIN FECHA porque no existe la columna
$query = "SELECT id, nombre, email 
          FROM usuarios 
          WHERE estado = 'pendiente' 
          ORDER BY id DESC 
          LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute();
$usuariosPendientesLista = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/layouts/navbar.php'; ?>

<style>
body {
    padding-top: 100px;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    font-family: 'Inter', sans-serif;
    margin: 0;
}

.dashboard-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 40px 30px;
}

.page-header {
    margin-bottom: 40px;
}

.page-title {
    font-size: 36px;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 16px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.stat-card {
    background: linear-gradient(145deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 30px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px var(--shadow-color);
}

.stat-icon {
    font-size: 48px;
    margin-bottom: 15px;
    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-number {
    font-size: 42px;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.stat-card.alert {
    border-color: #ef4444;
}

.stat-card.alert::before {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.stat-card.alert .stat-icon {
    animation: shake 0.5s infinite;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.alert-box {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
    border: 2px solid #ef4444;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
}

.alert-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.alert-icon {
    font-size: 32px;
    color: #ef4444;
}

.alert-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
}

.pending-users-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.pending-user-item {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.user-details {
    flex: 1;
}

.user-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 5px;
}

.user-email {
    font-size: 13px;
    color: var(--text-secondary);
}

.btn-approve {
    padding: 8px 16px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-approve:hover {
    transform: scale(1.05);
}

.actions-row {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.btn-primary {
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}
</style>

<div class="dashboard-container">
    <div class="page-header">
        <h1 class="page-title">📊 Dashboard Administrativo</h1>
        <p class="page-subtitle">Bienvenido de vuelta, <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Administrador'); ?></p>
    </div>

    <?php if ($usuariosPendientes > 0): ?>
    <div class="alert-box">
        <div class="alert-header">
            <i class="fas fa-exclamation-triangle alert-icon"></i>
            <div>
                <div class="alert-title">¡Tienes <?php echo $usuariosPendientes; ?> usuario(s) esperando aprobación!</div>
            </div>
        </div>

        <?php if (count($usuariosPendientesLista) > 0): ?>
        <div class="pending-users-list">
            <?php foreach ($usuariosPendientesLista as $usuario): ?>
            <div class="pending-user-item">
                <div class="user-details">
                    <div class="user-name"><?php echo htmlspecialchars($usuario['nombre']); ?></div>
                    <div class="user-email"><?php echo htmlspecialchars($usuario['email']); ?></div>
                </div>
                <a href="index.php?ruta=solicitudes&accion=aprobar&id=<?php echo $usuario['id']; ?>" class="btn-approve">
                    <i class="fas fa-check"></i> Revisar
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="actions-row">
            <a href="index.php?ruta=solicitudes" class="btn-primary">
                <i class="fas fa-users"></i> Ver Todas las Solicitudes
            </a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-number"><?php echo $totalLibros; ?></div>
            <div class="stat-label">Total de Libros</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="stat-number"><?php echo $librosDisponibles; ?></div>
            <div class="stat-label">Libros Disponibles</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number"><?php echo $totalUsuarios; ?></div>
            <div class="stat-label">Usuarios Activos</div>
        </div>

        <div class="stat-card <?php echo $usuariosPendientes > 0 ? 'alert' : ''; ?>">
            <div class="stat-icon">
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="stat-number"><?php echo $usuariosPendientes; ?></div>
            <div class="stat-label">Usuarios Pendientes</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="stat-number"><?php echo $prestamosActivos; ?></div>
            <div class="stat-label">Préstamos Activos</div>
        </div>

        <div class="stat-card <?php echo $prestamosAtrasados > 0 ? 'alert' : ''; ?>">
            <div class="stat-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-number"><?php echo $prestamosAtrasados; ?></div>
            <div class="stat-label">Préstamos Atrasados</div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>