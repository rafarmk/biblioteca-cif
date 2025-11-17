<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca CIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<style>
:root {
    --navbar-from: #1e3c72;
    --navbar-to: #2a5298;
    --primary: #2a5298;
    --secondary: #1e3c72;
    --accent: #3b82f6;
    --transition: all 0.3s ease;
    --bg-primary: #f0f4f8;
    --bg-secondary: #e2e8f0;
    --bg-card: #ffffff;
    --text-primary: #2c3e50;
    --text-secondary: #64748b;
    --border-color: #cbd5e1;
    --shadow: rgba(0, 0, 0, 0.1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg-primary);
    color: var(--text-primary);
    transition: var(--transition);
    min-height: 100vh;
    padding-top: 0;
}

.modern-navbar {
    background: linear-gradient(135deg, var(--navbar-from) 0%, var(--navbar-to) 100%);
    padding: 18px 0;
    box-shadow: 0 4px 20px var(--shadow);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    transition: var(--transition);
}

.navbar-container {
    max-width: 1800px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.navbar-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    color: white;
    text-decoration: none;
    font-size: 1.6rem;
    font-weight: 800;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.navbar-logo:hover {
    transform: scale(1.05);
    filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
}

.logo-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.25);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    transition: all 0.3s ease;
}

.navbar-logo:hover .logo-icon {
    transform: rotate(360deg);
}

.navbar-menu {
    display: flex;
    gap: 6px;
    align-items: center;
    overflow-x: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
    padding: 5px 0;
}

.navbar-menu::-webkit-scrollbar {
    display: none;
}

.nav-link {
    padding: 10px 16px;
    border-radius: 10px;
    text-decoration: none;
    color: white;
    font-weight: 600;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 7px;
    transition: all 0.3s ease;
    white-space: nowrap;
    position: relative;
    flex-shrink: 0;
    border: 2px solid transparent;
    background: none;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.3);
    border: 2px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    transform: scale(1.05);
}

.nav-link:active {
    transform: scale(0.98);
    background: rgba(255, 255, 255, 0.4);
}

.nav-link i {
    font-size: 18px;
    transition: all 0.3s ease;
}

.nav-link:hover i {
    transform: scale(1.15);
}

.badge-notification {
    position: absolute;
    top: 3px;
    right: 3px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
    animation: pulse-notification 2s infinite;
}

@keyframes pulse-notification {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.15); box-shadow: 0 0 10px rgba(239, 68, 68, 0.6); }
}

.nav-user {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 9px 15px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 18px;
    color: white;
    font-weight: 600;
    font-size: 14px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    flex-shrink: 0;
}

.nav-user i {
    font-size: 18px;
}

.nav-logout {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
    box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
}

.nav-logout:hover {
    box-shadow: 0 6px 25px rgba(245, 87, 108, 0.7);
    border-color: rgba(255, 255, 255, 0.6);
}

/* Theme Selector Dropdown */
.theme-dropdown {
    position: relative;
}

.theme-dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: var(--bg-card);
    border-radius: 12px;
    padding: 10px;
    box-shadow: 0 8px 25px var(--shadow);
    min-width: 180px;
    margin-top: 8px;
    z-index: 1001;
}

.theme-dropdown:hover .theme-dropdown-content,
.theme-dropdown-content.show {
    display: block;
}

.theme-option {
    padding: 10px 15px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 14px;
}

.theme-option:hover {
    background: var(--bg-secondary);
    transform: translateX(5px);
}

.theme-icon {
    font-size: 18px;
}

@media (max-width: 1400px) {
    .nav-link span {
        display: none;
    }
    .nav-link {
        padding: 10px 14px;
    }
}

@media (max-width: 768px) {
    .navbar-logo span {
        display: none;
    }
    .nav-user span {
        display: none;
    }
    .navbar-logo {
        font-size: 1.3rem;
    }
    .logo-icon {
        width: 45px;
        height: 45px;
        font-size: 22px;
    }
}
</style>

<nav class="modern-navbar">
    <div class="navbar-container">
        <?php
        $logoUrl = 'index.php?ruta=landing';
        if (isset($_SESSION['logueado'])) {
            if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'administrador') {
                $logoUrl = 'index.php?ruta=home';
            } else {
                $logoUrl = 'index.php?ruta=catalogo';
            }
        }
        ?>
        
        <a href="<?= $logoUrl ?>" class="navbar-logo">
            <div class="logo-icon">
                <i class="fas fa-book-reader"></i>
            </div>
            <span>Biblioteca CIF</span>
        </a>

        <div class="navbar-menu">
            <?php if (isset($_SESSION['usuario_nombre'])): ?>
                <div class="nav-user">
                    <i class="fas fa-user-circle"></i>
                    <span><?= htmlspecialchars($_SESSION['usuario_nombre']) ?></span>
                </div>
            <?php endif; ?>

            <div class="theme-dropdown">
                <button class="nav-link" onclick="toggleThemeDropdown()">
                    <i id="theme-icon" class="fas fa-palette"></i>
                    <span>Temas</span>
                </button>
                <div class="theme-dropdown-content" id="themeDropdown">
                    <div class="theme-option" onclick="applyTheme('light')">
                        <span class="theme-icon">☀️</span>
                        <span>Claro</span>
                    </div>
                    <div class="theme-option" onclick="applyTheme('dark')">
                        <span class="theme-icon">🌙</span>
                        <span>Oscuro</span>
                    </div>
                    <div class="theme-option" onclick="applyTheme('ocean')">
                        <span class="theme-icon">🌊</span>
                        <span>Océano</span>
                    </div>
                    <div class="theme-option" onclick="applyTheme('forest')">
                        <span class="theme-icon">🌲</span>
                        <span>Bosque</span>
                    </div>
                </div>
            </div>

            <?php
            $esAdmin = isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'administrador';
            ?>

            <?php if ($esAdmin): ?>
                <a href="index.php?ruta=home" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                <?php
                if (isset($_SESSION['logueado'])) {
                    require_once __DIR__ . '/../../config/Database.php';
                    $db = new Database();
                    $conn = $db->getConnection();
                    $stmt = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE estado = 'pendiente'");
                    $pendientes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                }
                ?>

                <a href="index.php?ruta=solicitudes" class="nav-link">
                    <i class="fas fa-bell"></i>
                    <span>Solicitudes</span>
                    <?php if (isset($pendientes) && $pendientes > 0): ?>
                        <span class="badge-notification"><?= $pendientes ?></span>
                    <?php endif; ?>
                </a>
                 
                <a href="index.php?ruta=libros" class="nav-link">
                    <i class="fas fa-book"></i>
                    <span>Libros</span>
                </a>

                <a href="index.php?ruta=libros&accion=importar" class="nav-link">
                    <i class="fas fa-file-excel"></i>
                    <span>Importar</span>
                </a>

                <a href="index.php?ruta=usuarios" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>

                <a href="index.php?ruta=prestamos" class="nav-link">
                    <i class="fas fa-handshake"></i>
                    <span>Préstamos</span>
                </a>

            <?php else: ?>
                <a href="index.php?ruta=catalogo" class="nav-link">
                    <i class="fas fa-book"></i>
                    <span>Catálogo</span>
                </a>

                <a href="index.php?ruta=mis_prestamos" class="nav-link">
                    <i class="fas fa-bookmark"></i>
                    <span>Mis Préstamos</span>
                </a>
            <?php endif; ?>

            <a href="index.php?ruta=logout" class="nav-link nav-logout" onclick="return confirm('¿Cerrar sesión?')">
                <i class="fas fa-sign-out-alt"></i>
                <span>Salir</span>
            </a>
        </div>
    </div>
</nav>

<script>
// TEMAS MEJORADOS
const themes = {
    light: {
        '--navbar-from': '#1e3c72',
        '--navbar-to': '#2a5298',
        '--primary': '#2a5298',
        '--secondary': '#1e3c72',
        '--accent': '#3b82f6',
        '--bg-primary': '#f0f4f8',
        '--bg-secondary': '#e2e8f0',
        '--bg-card': '#ffffff',
        '--text-primary': '#1e293b',
        '--text-secondary': '#64748b',
        '--border-color': '#cbd5e1',
        '--shadow': 'rgba(0, 0, 0, 0.1)'
    },
    dark: {
        '--navbar-from': '#1a1a2e',
        '--navbar-to': '#16213e',
        '--primary': '#0f3460',
        '--secondary': '#1a1a2e',
        '--accent': '#e94560',
        '--bg-primary': '#0f0f23',
        '--bg-secondary': '#16213e',
        '--bg-card': '#1a1a2e',
        '--text-primary': '#f1f5f9',
        '--text-secondary': '#94a3b8',
        '--border-color': '#2d2d44',
        '--shadow': 'rgba(0, 0, 0, 0.5)'
    },
    ocean: {
        '--navbar-from': '#006d77',
        '--navbar-to': '#83c5be',
        '--primary': '#006d77',
        '--secondary': '#83c5be',
        '--accent': '#e29578',
        '--bg-primary': '#edf6f9',
        '--bg-secondary': '#83c5be',
        '--bg-card': '#ffffff',
        '--text-primary': '#023047',
        '--text-secondary': '#126782',
        '--border-color': '#83c5be',
        '--shadow': 'rgba(0, 109, 119, 0.2)'
    },
    forest: {
        '--navbar-from': '#2d6a4f',
        '--navbar-to': '#52b788',
        '--primary': '#2d6a4f',
        '--secondary': '#1b4332',
        '--accent': '#95d5b2',
        '--bg-primary': '#d8f3dc',
        '--bg-secondary': '#b7e4c7',
        '--bg-card': '#ffffff',
        '--text-primary': '#1b4332',
        '--text-secondary': '#2d6a4f',
        '--border-color': '#95d5b2',
        '--shadow': 'rgba(45, 106, 79, 0.2)'
    }
};

function loadTheme() {
    const savedTheme = localStorage.getItem('bibliotecaTheme') || 'light';
    applyTheme(savedTheme);
}

function applyTheme(themeName) {
    const theme = themes[themeName] || themes.light;
    const root = document.documentElement;
    
    Object.keys(theme).forEach(property => {
        root.style.setProperty(property, theme[property]);
    });
    
    localStorage.setItem('bibliotecaTheme', themeName);
    
    // Cerrar dropdown
    const dropdown = document.getElementById('themeDropdown');
    if (dropdown) {
        dropdown.classList.remove('show');
    }
}

function toggleThemeDropdown() {
    const dropdown = document.getElementById('themeDropdown');
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
}

// Cerrar dropdown al hacer clic fuera
document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.theme-dropdown');
    if (dropdown && !dropdown.contains(event.target)) {
        document.getElementById('themeDropdown').classList.remove('show');
    }
});

document.addEventListener('DOMContentLoaded', loadTheme);
</script>

</body>
</html>