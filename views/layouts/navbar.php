<?php
// Verificar sesión
if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

// Obtener ruta actual
$ruta_actual = $_GET['ruta'] ?? 'home';

// Obtener alertas de usuarios pendientes (solo para admin)
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

<!DOCTYPE html>
<html lang="es" data-theme="cyberpunk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/temas_tecnologicos.css">
</head>
<body>

<nav class="main-navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <div class="navbar-brand">
            <i class="fas fa-book"></i>
            <span class="brand-name">Biblioteca CIF</span>
        </div>

        <!-- Menu Principal - A LA IZQUIERDA -->
        <div class="navbar-menu">
            <?php if ($_SESSION['tipo_usuario'] === 'administrador'): ?>
                <!-- MENU ADMINISTRADOR -->
                <a href="index.php?ruta=home" class="nav-item <?php echo $ruta_actual === 'home' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>

                <a href="index.php?ruta=libros" class="nav-item <?php echo $ruta_actual === 'libros' ? 'active' : ''; ?>">
                    <i class="fas fa-book"></i>
                    <span>Libros</span>
                </a>

                <a href="index.php?ruta=usuarios" class="nav-item <?php echo $ruta_actual === 'usuarios' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>

                <a href="index.php?ruta=prestamos" class="nav-item <?php echo $ruta_actual === 'prestamos' ? 'active' : ''; ?>">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Préstamos</span>
                </a>

                <a href="index.php?ruta=solicitudes" class="nav-item <?php echo $ruta_actual === 'solicitudes' ? 'active' : ''; ?>">
                    <i class="fas fa-user-clock"></i>
                    <span>Solicitudes</span>
                    <?php if ($usuariosPendientes > 0): ?>
                        <span class="badge-alert"><?php echo $usuariosPendientes; ?></span>
                    <?php endif; ?>
                </a>

            <?php else: ?>
                <!-- MENU USUARIO -->
                <a href="index.php?ruta=catalogo" class="nav-item <?php echo $ruta_actual === 'catalogo' ? 'active' : ''; ?>">
                    <i class="fas fa-book-open"></i>
                    <span>Catálogo</span>
                </a>

                <a href="index.php?ruta=mis_prestamos" class="nav-item <?php echo $ruta_actual === 'mis_prestamos' ? 'active' : ''; ?>">
                    <i class="fas fa-bookmark"></i>
                    <span>Mis Préstamos</span>
                </a>
            <?php endif; ?>

            <!-- Selector de Temas - TEMAS TECNOLÓGICOS -->
            <div class="theme-selector-wrapper">
                <button id="themeToggleBtn" class="theme-toggle-btn">
                    <span class="theme-icon">🎨</span>
                    <span>Temas</span>
                    <i class="fas fa-chevron-down"></i>
                </button>

                <div id="themeDropdown" class="theme-dropdown">
                    <div class="theme-dropdown-title">Temas Tecnológicos</div>
                    
                    <!-- Tema Cyberpunk -->
                    <div class="theme-option" data-theme="cyberpunk">
                        <div class="theme-color-preview" style="background: linear-gradient(135deg, #00d9ff 0%, #ff00aa 100%);"></div>
                        <span class="theme-name">⚡ Cyberpunk</span>
                        <i class="fas fa-check theme-check"></i>
                    </div>

                    <!-- Tema Matrix -->
                    <div class="theme-option" data-theme="matrix">
                        <div class="theme-color-preview" style="background: linear-gradient(135deg, #00ff41 0%, #39ff14 100%);"></div>
                        <span class="theme-name">💻 Matrix</span>
                        <i class="fas fa-check theme-check" style="display: none;"></i>
                    </div>

                    <!-- Tema Neon City -->
                    <div class="theme-option" data-theme="neon">
                        <div class="theme-color-preview" style="background: linear-gradient(135deg, #ff00ff 0%, #ff1493 100%);"></div>
                        <span class="theme-name">🌃 Neon City</span>
                        <i class="fas fa-check theme-check" style="display: none;"></i>
                    </div>

                    <!-- Tema Deep Ocean -->
                    <div class="theme-option" data-theme="ocean">
                        <div class="theme-color-preview" style="background: linear-gradient(135deg, #1890ff 0%, #0050b3 100%);"></div>
                        <span class="theme-name">🌊 Deep Ocean</span>
                        <i class="fas fa-check theme-check" style="display: none;"></i>
                    </div>

                    <!-- Tema Sunset -->
                    <div class="theme-option" data-theme="sunset">
                        <div class="theme-color-preview" style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);"></div>
                        <span class="theme-name">🌅 Sunset</span>
                        <i class="fas fa-check theme-check" style="display: none;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usuario y Logout - A LA DERECHA -->
        <div class="navbar-user">
            <div class="user-info">
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></span>
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

<script src="assets/js/theme_system.js"></script>
</body>
</html>