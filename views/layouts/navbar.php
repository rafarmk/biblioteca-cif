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
    --bg-primary: #ffffff;
    --bg-secondary: #f8f9fa;
    --bg-card: #ffffff;
    --text-primary: #2c3e50;
    --text-secondary: #5a6c7d;
    --border-color: #e1e8ed;
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
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
}

.nav-logout:hover {
    box-shadow: 0 6px 25px rgba(245, 87, 108, 0.7);
    border-color: rgba(255, 255, 255, 0.6);
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
        <a href="index.php?ruta=landing" class="navbar-logo">
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

            <a href="index.php?ruta=landing" class="nav-link">
                <i class="fas fa-home"></i>
                <span>Inicio</span>
            </a>

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

</body>
</html>