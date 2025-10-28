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
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Sistema de Temas Centralizado -->
    <link rel="stylesheet" href="assets/css/themes.css">
    
    <style>
        /* Navbar Styles */
        .modern-navbar {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            box-shadow: 0 8px 32px var(--shadow);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1000;
            border-bottom: 1px solid var(--border-color);
            margin: 0;
        }

        [data-theme="premium"] .modern-navbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                transparent,
                rgba(56, 189, 248, 0.1),
                transparent);
            animation: navbar-shine 3s infinite;
        }

        @keyframes navbar-shine {
            0% { left: -100%; }
            50%, 100% { left: 100%; }
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
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
            transform: scale(1.08);
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: var(--primary);
            opacity: 0.85;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: white;
            transition: all 0.4s ease;
        }

        [data-theme="premium"] .logo-icon {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.8) 0%, rgba(96, 165, 250, 0.8) 100%);
            box-shadow: 0 4px 20px rgba(56, 189, 248, 0.4);
            animation: logo-float 3s ease-in-out infinite;
        }

        @keyframes logo-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .navbar-logo:hover .logo-icon {
            transform: rotate(360deg);
        }

        .navbar-menu {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-link {
            padding: 12px 22px;
            border-radius: 14px;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            background: rgba(52, 152, 219, 0.1);
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--primary);
            opacity: 0.2;
            transition: left 0.4s ease;
        }

        .nav-link:hover::before {
            left: 0;
        }

        .nav-link:hover {
            transform: translateY(-3px);
            background: var(--primary);
            color: white;
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        [data-theme="premium"] .nav-link:hover {
            box-shadow: 0 6px 25px rgba(56, 189, 248, 0.5);
            background: var(--primary);
        }

        .nav-link i {
            font-size: 19px;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 18px;
            background: var(--primary);
            opacity: 0.85;
            border-radius: 28px;
            color: white;
            font-weight: 600;
            border: 2px solid var(--primary);
        }

        [data-theme="premium"] .nav-user {
            background: rgba(56, 189, 248, 0.25);
            border-color: rgba(56, 189, 248, 0.4);
            box-shadow: 0 4px 15px rgba(56, 189, 248, 0.3);
            color: var(--text-primary);
        }

        .nav-user i {
            font-size: 22px;
        }

        .nav-logout {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white !important;
            padding: 12px 26px;
            border-radius: 28px;
            border: none;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(245, 87, 108, 0.4);
        }

        .nav-logout:hover {
            transform: translateY(-4px) scale(1.08);
            box-shadow: 0 10px 35px rgba(245, 87, 108, 0.6);
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
                padding: 10px 16px;
            }
        }
    </style>
</head>
<body>

<!-- Theme Toggle Button -->
<div class="theme-toggle" id="themeToggle">
    <span class="theme-toggle-icon"></span>
    <span class="theme-toggle-text">Modo Claro</span>
</div>

<nav class="modern-navbar">
    <div class="navbar-container">
        <a href="index.php?ruta=landing" class="navbar-logo">
            <div class="logo-icon">
                <i class="fas fa-book-reader"></i>
            </div>
            <span>Biblioteca CIF</span>
        </a>

        <div class="navbar-menu">
            <?php if (isset(\['admin_nombre'])): ?>
                <div class="nav-user">
                    <i class="fas fa-user-circle"></i>
                    <span>Administrador</span>
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

            <a href="index.php?ruta=prestamos" class="nav-link">
                <i class="fas fa-handshake"></i>
                <span>Préstamos</span>
            </a>

            <a href="index.php?ruta=logout" class="nav-link nav-logout" onclick="return confirm('¿Cerrar sesión?')">
                <i class="fas fa-sign-out-alt"></i>
                <span>Salir</span>
            </a>
        </div>
    </div>
</nav>

<!-- Cargar JavaScript de Temas -->
<script src="assets/js/themes.js"></script>
