<?php
if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

$ruta_actual = $_GET['ruta'] ?? 'home';

$usuariosPendientes = 0;
if ($_SESSION['tipo_usuario'] === 'administrador') {
    require_once __DIR__ . '/../../config/database.php';
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT COUNT(*) as total FROM usuarios WHERE estado = 'pendiente'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $usuariosPendientes = $result['total'];
}
?>

<nav class="main-navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <i class="fas fa-book"></i>
            <span class="brand-name">Biblioteca CIF</span>
        </div>

        <div class="navbar-menu">
            <?php if ($_SESSION['tipo_usuario'] === 'administrador'): ?>
                <a href="index.php?ruta=home" class="nav-item <?php echo $ruta_actual === 'home' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>

                <a href="index.php?ruta=libros" class="nav-item <?php echo $ruta_actual === 'libros' ? 'active' : ''; ?>">
                    <i class="fas fa-book"></i>
                    <span>Libros</span>
                </a>

                <a href="index.php?ruta=categorias" class="nav-item <?php echo $ruta_actual === 'categorias' ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i>
                    <span>Categorías</span>
                </a>

                <a href="index.php?ruta=usuarios" class="nav-item <?php echo $ruta_actual === 'usuarios' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>

                <a href="index.php?ruta=prestamos" class="nav-item <?php echo $ruta_actual === 'prestamos' ? 'active' : ''; ?>">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Préstamos</span>
                </a>

                <a href="index.php?ruta=calificaciones_usuarios" class="nav-item <?php echo $ruta_actual === 'calificaciones_usuarios' ? 'active' : ''; ?>">
                    <i class="fas fa-star"></i>
                    <span>Calificaciones</span>
                </a>

                <a href="index.php?ruta=solicitudes" class="nav-item <?php echo $ruta_actual === 'solicitudes' ? 'active' : ''; ?>">
                    <i class="fas fa-user-clock"></i>
                    <span>Solicitudes</span>
                    <?php if ($usuariosPendientes > 0): ?>
                        <span class="badge-alert"><?php echo $usuariosPendientes; ?></span>
                    <?php endif; ?>
                </a>

            <?php else: ?>
                <a href="index.php?ruta=catalogo" class="nav-item <?php echo $ruta_actual === 'catalogo' ? 'active' : ''; ?>">
                    <i class="fas fa-book-open"></i>
                    <span>Catálogo</span>
                </a>

                <a href="index.php?ruta=mis_prestamos" class="nav-item <?php echo $ruta_actual === 'mis_prestamos' ? 'active' : ''; ?>">
                    <i class="fas fa-bookmark"></i>
                    <span>Mis Préstamos</span>
                </a>
                
                <a href="index.php?ruta=mi_calificacion" class="nav-item <?php echo $ruta_actual === 'mi_calificacion' ? 'active' : ''; ?>">
                    <i class="fas fa-star"></i>
                    <span>Mi Calificación</span>
                </a>
            <?php endif; ?>

            <div class="theme-selector-wrapper">
                <button id="themeToggleBtn" class="theme-toggle-btn">
                    <span class="theme-icon">🎨</span>
                    <span>Temas</span>
                    <i class="fas fa-chevron-down"></i>
                </button>

                <div id="themeDropdown" class="theme-dropdown">
                    <div class="theme-dropdown-title">Temas Profesionales</div>

                    <div class="theme-option" data-theme="dark">
                        <div class="theme-color-preview" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);"></div>
                        <span class="theme-name">🌙 Dark Professional</span>
                        <i class="fas fa-check theme-check"></i>
                    </div>

                    <div class="theme-option" data-theme="midnight">
                        <div class="theme-color-preview" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);"></div>
                        <span class="theme-name">🌌 Midnight Blue</span>
                        <i class="fas fa-check theme-check" style="display: none;"></i>
                    </div>

                    <div class="theme-option" data-theme="carbon">
                        <div class="theme-color-preview" style="background: linear-gradient(135deg, #00d4ff 0%, #00b8d4 100%);"></div>
                        <span class="theme-name">⚫ Carbon</span>
                        <i class="fas fa-check theme-check" style="display: none;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="navbar-user">
            <div class="user-info">
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?></span>
                <span class="user-role"><?php echo htmlspecialchars($_SESSION['tipo_usuario'] ?? 'Usuario'); ?></span>
            </div>
            <a href="index.php?ruta=logout" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Salir</span>
            </a>
        </div>
    </div>
</nav>

<style>
.user-info {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.user-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
}

.user-role {
    font-size: 12px;
    color: var(--text-secondary);
    text-transform: capitalize;
}

.btn-logout {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4);
}
</style>