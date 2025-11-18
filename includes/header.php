<!-- 
=============================================================================
HEADER MEJORADO PARA BIBLIOTECA CIF
Incluye selector de temas funcional y estructura correcta
=============================================================================
-->

<header class="main-header">
    <div class="header-container">
        <!-- Logo y nombre del sistema -->
        <div class="header-logo">
            <img src="assets/img/logo.png" alt="Biblioteca CIF" class="logo-img">
            <span class="system-name">Biblioteca CIF</span>
        </div>

        <!-- Navegación principal -->
        <nav class="main-navigation">
            <a href="dashboard.php" class="nav-link">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>

            <a href="admin_panel.php" class="nav-link">
                <i class="fas fa-cog"></i>
                <span>Administrador Sistema</span>
            </a>

            <!-- Selector de Temas - SOLUCIÓN MEJORADA -->
            <div class="theme-selector-wrapper">
                <button id="themeToggleBtn" class="theme-toggle-btn">
                    <span class="theme-icon">🌙</span>
                    <span>Temas</span>
                    <i class="fas fa-chevron-down"></i>
                </button>

                <!-- Dropdown de temas -->
                <div id="themeDropdown" class="theme-dropdown">
                    <div class="theme-dropdown-title">Seleccionar Tema</div>
                    
                    <!-- Tema Oscuro -->
                    <div class="theme-option" data-theme="dark">
                        <div class="theme-color-preview" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                        <span class="theme-name">🌙 Oscuro</span>
                        <i class="fas fa-check theme-check"></i>
                    </div>

                    <!-- Tema Claro -->
                    <div class="theme-option" data-theme="light">
                        <div class="theme-color-preview" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);"></div>
                        <span class="theme-name">☀️ Claro</span>
                        <i class="fas fa-check theme-check" style="display: none;"></i>
                    </div>

                    <!-- Tema Azul -->
                    <div class="theme-option" data-theme="blue">
                        <div class="theme-color-preview" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);"></div>
                        <span class="theme-name">🌊 Azul</span>
                        <i class="fas fa-check theme-check" style="display: none;"></i>
                    </div>

                    <!-- Tema Verde -->
                    <div class="theme-option" data-theme="green">
                        <div class="theme-color-preview" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);"></div>
                        <span class="theme-name">🌿 Verde</span>
                        <i class="fas fa-check theme-check" style="display: none;"></i>
                    </div>

                    <!-- Tema Morado -->
                    <div class="theme-option" data-theme="purple">
                        <div class="theme-color-preview" style="background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);"></div>
                        <span class="theme-name">🔮 Morado</span>
                        <i class="fas fa-check theme-check" style="display: none;"></i>
                    </div>
                </div>
            </div>

            <a href="libros.php" class="nav-link">
                <i class="fas fa-book"></i>
                <span>Libros</span>
            </a>

            <a href="usuarios.php" class="nav-link">
                <i class="fas fa-users"></i>
                <span>Usuarios</span>
            </a>

            <a href="prestamos.php" class="nav-link">
                <i class="fas fa-exchange-alt"></i>
                <span>Préstamos</span>
            </a>

            <?php if(isset($_SESSION['pending_requests']) && $_SESSION['pending_requests'] > 0): ?>
            <a href="solicitudes.php" class="nav-link has-notification">
                <i class="fas fa-bell"></i>
                <span>Solicitudes</span>
                <span class="notification-badge"><?php echo $_SESSION['pending_requests']; ?></span>
            </a>
            <?php else: ?>
            <a href="solicitudes.php" class="nav-link">
                <i class="fas fa-bell"></i>
                <span>Solicitudes</span>
            </a>
            <?php endif; ?>
        </nav>

        <!-- Usuario y sesión -->
        <div class="header-user">
            <div class="user-info">
                <span class="user-name"><?php echo $_SESSION['nombre_usuario']; ?></span>
                <span class="user-role"><?php echo $_SESSION['rol']; ?></span>
            </div>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Salir</span>
            </a>
        </div>
    </div>
</header>

<!-- Estilos adicionales del header -->
<style>
.main-header {
    background-color: var(--bg-secondary);
    border-bottom: 2px solid var(--border-color);
    padding: 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 10px var(--shadow-color);
}

.header-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 15px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
}

.header-logo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo-img {
    width: 40px;
    height: 40px;
    object-fit: contain;
}

.system-name {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
    white-space: nowrap;
}

.main-navigation {
    display: flex;
    align-items: center;
    gap: 5px;
    flex: 1;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 8px;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link:hover {
    background-color: rgba(102, 126, 234, 0.1);
    color: var(--accent-primary);
    transform: translateY(-2px);
}

.nav-link i {
    font-size: 16px;
}

.nav-link.has-notification {
    position: relative;
}

.notification-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    min-width: 20px;
    text-align: center;
}

.header-user {
    display: flex;
    align-items: center;
    gap: 20px;
}

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
}

.logout-btn {
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

.logout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4);
}

/* Responsive */
@media (max-width: 1200px) {
    .nav-link span {
        display: none;
    }
    
    .nav-link {
        padding: 10px;
    }
}

@media (max-width: 768px) {
    .header-container {
        flex-wrap: wrap;
    }
    
    .main-navigation {
        order: 3;
        width: 100%;
        justify-content: space-around;
    }
}
</style>
