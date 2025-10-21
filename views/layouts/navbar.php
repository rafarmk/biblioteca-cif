<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.modern-navbar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    backdrop-filter: blur(10px);
    padding: 15px 0;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
    position: sticky;
    top: 0;
    z-index: 1000;
    border-bottom: 1px solid rgba(255, 255, 255, 0.18);
}

.navbar-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    color: white;
    text-decoration: none;
    font-size: 1.5rem;
    font-weight: 800;
    transition: transform 0.3s ease;
}

.navbar-logo:hover {
    transform: scale(1.05);
}

.logo-icon {
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.navbar-menu {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.nav-link {
    padding: 10px 20px;
    border-radius: 12px;
    text-decoration: none;
    color: white;
    font-weight: 600;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    transition: left 0.3s ease;
}

.nav-link:hover::before {
    left: 0;
}

.nav-link:hover {
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.nav-link i {
    font-size: 18px;
}

.nav-user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 16px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 25px;
    color: white;
    font-weight: 600;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.nav-user i {
    font-size: 20px;
}

.nav-logout {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    padding: 10px 24px;
    border-radius: 25px;
    border: none;
    font-weight: 700;
    transition: all 0.3s ease;
}

.nav-logout:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(245, 87, 108, 0.4);
}

@media (max-width: 768px) {
    .navbar-container {
        flex-direction: column;
        gap: 15px;
    }
    
    .navbar-menu {
        width: 100%;
        justify-content: center;
    }
    
    .nav-link {
        font-size: 13px;
        padding: 8px 15px;
    }
}
</style>

<nav class="modern-navbar">
    <div class="navbar-container">
        <a href="index.php?ruta=landing" class="navbar-logo">
            <div class="logo-icon">
                <i class="fas fa-book-reader"></i>
            </div>
            <span>Biblioteca CIF</span>
        </a>
        
        <div class="navbar-menu">
            <?php if (isset($_SESSION['admin_nombre'])): ?>
                <div class="nav-user">
                    <i class="fas fa-user-circle"></i>
                    <span><?php echo htmlspecialchars($_SESSION['admin_nombre']); ?></span>
                </div>
            <?php endif; ?>
            
            <a href="index.php?ruta=landing" class="nav-link">
                <i class="fas fa-home"></i>
                <span>Inicio</span>
            </a>
            
            <a href="index.php?ruta=home" class="nav-link">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="index.php?ruta=libros" class="nav-link">
                <i class="fas fa-book"></i>
                <span>Libros</span>
            </a>
            
            <a href="index.php?ruta=usuarios" class="nav-link">
                <i class="fas fa-users"></i>
                <span>Usuarios</span>
            </a>
            
            <a href="index.php?ruta=logout" class="nav-link nav-logout" onclick="return confirm('¿Cerrar sesión?')">
                <i class="fas fa-sign-out-alt"></i>
                <span>Salir</span>
            </a>
        </div>
    </div>
</nav>