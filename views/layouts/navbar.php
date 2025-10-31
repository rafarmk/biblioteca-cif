<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determinar si es admin
$tipoUsuario = $_SESSION['tipo_usuario'] ?? '';
$esAdmin = in_array($tipoUsuario, ['administrador', 'personal_administrativo', 'personal_operativo']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca CIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/modern-style.css">
</head>
<body>

<style>
:root {
    --primary: #3498db;
    --bg-primary: #ffffff;
    --bg-secondary: #f8f9fa;
    --bg-card: #ffffff;
    --text-primary: #2c3e50;
    --text-secondary: #5a6c7d;
    --border-color: #e1e8ed;
    --shadow: rgba(0, 0, 0, 0.1);
}

[data-theme="dark"] {
    --bg-primary: #0a0f1e;
    --bg-secondary: #121829;
    --bg-card: #1a2332;
    --text-primary: #e5e7eb;
    --text-secondary: #9ca3af;
    --border-color: #2d3748;
    --shadow: rgba(0, 0, 0, 0.3);
    --primary: #3b82f6;
}

[data-theme="original"] {
    --bg-primary: #f0f4f8;
    --bg-secondary: #e3e8ef;
    --bg-card: #ffffff;
    --text-primary: #2c3e50;
    --text-secondary: #5a6c7d;
    --border-color: #d1dce5;
    --primary: #3498db;
}

[data-theme="premium"] {
    --bg-primary: #0f1419;
    --bg-secondary: #1a1f29;
    --bg-card: #1e2533;
    --text-primary: #c9d1d9;
    --text-secondary: #8b93a0;
    --border-color: #30363d;
    --primary: #38bdf8;
}

body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg-primary);
    color: var(--text-primary);
    padding-top: 75px;
    margin: 0;
}

.theme-toggle {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 10000;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    padding: 14px 26px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 8px 30px rgba(59, 130, 246, 0.4);
    transition: all 0.4s ease;
}

[data-theme="dark"] .theme-toggle {
    background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
    box-shadow: 0 8px 30px rgba(30, 58, 138, 0.5);
}

[data-theme="premium"] .theme-toggle {
    background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    box-shadow: 0 8px 30px rgba(56, 189, 248, 0.5);
}

.theme-toggle:hover {
    transform: translateY(-5px) scale(1.05);
}

.theme-toggle-icon {
    font-size: 22px;
}

.theme-toggle-text {
    font-weight: 700;
    color: white;
    font-size: 15px;
}

.modern-navbar {
    background: var(--bg-card);
    padding: 15px 0;
    box-shadow: 0 8px 32px var(--shadow);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    border-bottom: 1px solid var(--border-color);
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
    gap: 14px;
    color: var(--text-primary);
    text-decoration: none;
    font-size: 1.6rem;
    font-weight: 800;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.navbar-logo:hover {
    transform: scale(1.03);
}

.logo-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary) 0%, #2563eb 100%);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: white;
    transition: all 0.4s ease;
}

.navbar-logo:hover .logo-icon {
    transform: rotate(360deg);
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
}

.navbar-menu {
    display: flex;
    gap: 12px;
    align-items: center;
}

.nav-link {
    position: relative;
    overflow: visible;
    padding: 12px 22px;
    border-radius: 14px;
    text-decoration: none;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
}

.nav-link:hover {
    background: var(--bg-secondary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.nav-user {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 18px;
    background: rgba(59, 130, 246, 0.1);
    border-radius: 28px;
    color: var(--text-primary);
    font-weight: 600;
}

.nav-logout {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white !important;
    padding: 12px 26px;
    border-radius: 28px;
}

table td {
    vertical-align: middle !important;
}
</style>

<div class="theme-toggle" id="themeToggle">
    <span class="theme-toggle-icon"></span>
    <span class="theme-toggle-text">Modo Claro</span>
</div>

<nav class="modern-navbar">
    <div class="navbar-container">
        <a href="index.php?ruta=landing" class="navbar-logo">
            <div class="logo-icon"><i class="fas fa-book-reader"></i></div>
            <span>Biblioteca CIF</span>
        </a>
        <div class="navbar-menu">
            <?php if ($esAdmin): ?>
                <!-- Menú para ADMINISTRADORES -->
                <div class="nav-user"><i class="fas fa-user-shield"></i><span>Administrador</span></div>
                <a href="index.php?ruta=landing" class="nav-link"><i class="fas fa-home"></i><span>Inicio</span></a>
                <a href="index.php?ruta=home" class="nav-link"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
                <a href="index.php?ruta=libros" class="nav-link"><i class="fas fa-book"></i><span>Libros</span></a>
                <a href="index.php?ruta=importar" class="nav-link"><i class="fas fa-file-upload"></i><span>Importar</span></a>
                <a href="index.php?ruta=usuarios" class="nav-link"><i class="fas fa-users"></i><span>Usuarios</span></a>
                <a href="index.php?ruta=prestamos" class="nav-link"><i class="fas fa-handshake"></i><span>Préstamos</span></a>
            <?php else: ?>
                <!-- Menú para USUARIOS COMUNES -->
                <div class="nav-user"><i class="fas fa-user-circle"></i><span><?php echo $_SESSION['usuario_nombre'] ?? 'Usuario'; ?></span></div>
                <a href="index.php?ruta=landing" class="nav-link"><i class="fas fa-home"></i><span>Inicio</span></a>
                <a href="index.php?ruta=catalogo" class="nav-link"><i class="fas fa-book-open"></i><span>Catálogo</span></a>
                <a href="index.php?ruta=mis-prestamos" class="nav-link"><i class="fas fa-bookmark"></i><span>Mis Préstamos</span></a>
                <a href="index.php?ruta=perfil" class="nav-link"><i class="fas fa-user-cog"></i><span>Perfil</span></a>
            <?php endif; ?>
            <a href="index.php?ruta=logout" class="nav-link nav-logout" onclick="return confirm('¿Cerrar sesión?')"><i class="fas fa-sign-out-alt"></i><span>Salir</span></a>
        </div>
    </div>
</nav>

<script>
const themeToggle = document.getElementById('themeToggle');
const html = document.documentElement;
const themeIcon = themeToggle.querySelector('.theme-toggle-icon');
const themeText = themeToggle.querySelector('.theme-toggle-text');

const themes = {
    light: { icon: '', text: 'Modo Claro', next: 'dark' },
    dark: { icon: '', text: 'Modo Oscuro', next: 'original' },
    original: { icon: '', text: 'Modo Original', next: 'premium' },
    premium: { icon: '', text: 'Modo Premium', next: 'light' }
};

let currentTheme = localStorage.getItem('theme') || 'light';
html.setAttribute('data-theme', currentTheme);
themeIcon.textContent = themes[currentTheme].icon;
themeText.textContent = themes[currentTheme].text;

themeToggle.addEventListener('click', function() {
    currentTheme = themes[currentTheme].next;
    html.setAttribute('data-theme', currentTheme);
    localStorage.setItem('theme', currentTheme);
    themeIcon.textContent = themes[currentTheme].icon;
    themeText.textContent = themes[currentTheme].text;
});
</script>