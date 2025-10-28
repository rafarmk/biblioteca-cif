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
    --primary: #3498db;
    --secondary: #2c3e50;
    --accent: #e74c3c;
    --light: #ecf0f1;
    --dark: #34495e;
    --success: #27ae60;
    --transition: all 0.3s ease;

    /* Light mode colors */
    --bg-primary: #ffffff;
    --bg-secondary: #f8f9fa;
    --bg-card: #ffffff;
    --text-primary: #2c3e50;
    --text-secondary: #5a6c7d;
    --border-color: #e1e8ed;
    --shadow: rgba(0, 0, 0, 0.1);
}

/* Dark mode */
[data-theme="dark"] {
    --bg-primary: #0a0f1e;
    --bg-secondary: #121829;
    --bg-card: #1a2332;
    --text-primary: #e5e7eb;
    --text-secondary: #9ca3af;
    --border-color: #2d3748;
    --shadow: rgba(0, 0, 0, 0.3);
    --primary: #3b82f6;
    --accent: #ef4444;
}

/* Original mode */
[data-theme="original"] {
    --bg-primary: #f0f4f8;
    --bg-secondary: #e3e8ef;
    --bg-card: #ffffff;
    --text-primary: #2c3e50;
    --text-secondary: #5a6c7d;
    --border-color: #d1dce5;
    --shadow: rgba(52, 152, 219, 0.15);
    --primary: #3498db;
    --secondary: #2c3e50;
    --accent: #e74c3c;
}

/* Premium mode - SUPER VISTOSO */
[data-theme="premium"] {
    --bg-primary: #0f1419;
    --bg-secondary: #1a1f29;
    --bg-card: #1e2533;
    --text-primary: #c9d1d9;
    --text-secondary: #8b93a0;
    --border-color: #30363d;
    --shadow: rgba(56, 189, 248, 0.3);
    --primary: #38bdf8;
    --secondary: #0ea5e9;
    --accent: #60a5fa;
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
    position: relative;
    overflow-x: hidden;

    padding-top: 75px;
}

/* Premium mode - EFECTOS VISUALES INCREÍBLES */
[data-theme="premium"] body {
    background: linear-gradient(135deg, #0f1419 0%, #1a1f29 50%, #0f1419 100%);

    padding-top: 75px;
}

[data-theme="premium"] body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background:
        radial-gradient(circle at 20% 50%, rgba(56, 189, 248, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(96, 165, 250, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 40% 20%, rgba(14, 165, 233, 0.15) 0%, transparent 50%);
    animation: premium-glow 15s ease-in-out infinite alternate;
    pointer-events: none;
    z-index: 0;
}

@keyframes premium-glow {
    0% {
        opacity: 0.8;
        transform: scale(1) rotate(0deg);
    }
    50% {
        opacity: 1;
        transform: scale(1.1) rotate(5deg);
    }
    100% {
        opacity: 0.8;
        transform: scale(1) rotate(0deg);
    }
}

[data-theme="premium"] body::after {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, 
        transparent 0%, 
        rgba(56, 189, 248, 0.05) 50%, 
        transparent 100%);
    animation: premium-scan 8s linear infinite;
    pointer-events: none;
    z-index: 0;
}

@keyframes premium-scan {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Partículas flotantes para Premium */
[data-theme="premium"] body {
    overflow-x: hidden; overflow-y: auto;

    padding-top: 75px;
}

[data-theme="premium"]::before {
    content: '';
    position: fixed;
    width: 300%;
    height: 300%;
    background-image: 
        radial-gradient(2px 2px at 20% 30%, rgba(56, 189, 248, 0.4), transparent),
        radial-gradient(2px 2px at 60% 70%, rgba(96, 165, 250, 0.4), transparent),
        radial-gradient(2px 2px at 50% 50%, rgba(14, 165, 233, 0.4), transparent),
        radial-gradient(2px 2px at 80% 10%, rgba(56, 189, 248, 0.4), transparent),
        radial-gradient(2px 2px at 90% 60%, rgba(96, 165, 250, 0.4), transparent);
    background-size: 200px 200px, 250px 250px, 300px 300px, 220px 220px, 270px 270px;
    background-position: 0 0, 40px 60px, 130px 270px, 70px 100px, 150px 50px;
    animation: premium-stars 120s linear infinite;
    z-index: 0;
    pointer-events: none;
}

@keyframes premium-stars {
    0% { transform: translate(0, 0); }
    100% { transform: translate(-100px, -100px); }
}

/* Theme Toggle - MÁS VISTOSO */
.theme-toggle {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 10000;
    background: var(--bg-card);
    border: 2px solid var(--border-color);
    border-radius: 50px;
    padding: 14px 26px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 8px 30px var(--shadow);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

[data-theme="premium"] .theme-toggle {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: #38bdf8;
    box-shadow: 
        0 8px 30px rgba(56, 189, 248, 0.4),
        0 0 60px rgba(56, 189, 248, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    animation: premium-pulse 3s ease-in-out infinite;
}

@keyframes premium-pulse {
    0%, 100% {
        box-shadow: 
            0 8px 30px rgba(56, 189, 248, 0.4),
            0 0 60px rgba(56, 189, 248, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }
    50% {
        box-shadow: 
            0 8px 40px rgba(56, 189, 248, 0.6),
            0 0 80px rgba(56, 189, 248, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.15);
    }
}

.theme-toggle:hover {
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 12px 40px var(--shadow);
}

[data-theme="premium"] .theme-toggle:hover {
    box-shadow: 
        0 12px 50px rgba(56, 189, 248, 0.6),
        0 0 100px rgba(56, 189, 248, 0.4);
}

.theme-toggle-icon {
    font-size: 22px;
    transition: transform 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.theme-toggle:hover .theme-toggle-icon {
    transform: rotate(360deg) scale(1.2);
}

.theme-toggle-text {
    font-weight: 700;
    color: var(--text-primary);
    font-size: 15px;
    letter-spacing: 0.5px;
}

/* Particle effect */
.particle {
    position: absolute;
    width: 8px;
    height: 8px;
    background: var(--primary);
    border-radius: 50%;
    pointer-events: none;
    animation: particle-explosion 1.2s ease-out forwards;
    box-shadow: 0 0 10px var(--primary);
}

@keyframes particle-explosion {
    to {
        transform: translate(var(--tx), var(--ty)) scale(0);
        opacity: 0;
    }
}

/* Navbar con efectos Premium */
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

[data-theme="dark"] .modern-navbar {
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

[data-theme="original"] .modern-navbar {
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

[data-theme="premium"] .modern-navbar {
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
    color: white;
    text-decoration: none;
    font-size: 1.6rem;
    font-weight: 800;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.navbar-logo:hover {
    transform: scale(1.08);
    filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.5));
}

.logo-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.25);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    transition: all 0.4s ease;
}

[data-theme="premium"] .logo-icon {
    background: linear-gradient(135deg, rgba(56, 189, 248, 0.3) 0%, rgba(96, 165, 250, 0.3) 100%);
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
}

.nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.25);
    transition: left 0.4s ease;
}

.nav-link:hover::before {
    left: 0;
}

.nav-link:hover {
    transform: translateY(-3px);
    background: var(--bg-secondary);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

[data-theme="premium"] .nav-link:hover {
    box-shadow: 0 6px 25px rgba(56, 189, 248, 0.5);
    background: rgba(56, 189, 248, 0.15);
}

.nav-link i {
    font-size: 19px;
}

.nav-user {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 18px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 28px;
    color: white;
    font-weight: 600;
    border: 2px solid rgba(255, 255, 255, 0.35);
}

[data-theme="premium"] .nav-user {
    background: rgba(56, 189, 248, 0.15);
    border-color: rgba(56, 189, 248, 0.4);
    box-shadow: 0 4px 15px rgba(56, 189, 248, 0.3);
}

.nav-user i {
    font-size: 22px;
}

.nav-logout {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

    .theme-toggle-text {
        display: none;
    }
}

/* MEJORA DE CONTRASTE DE LETRAS */
h1, h2, h3, h4, h5, h6, label, .page-title, .card-title {
    color: var(--text-primary) !important;
    font-weight: 700 !important;
}

table th, table td {
    color: var(--text-primary) !important;
}

input, select, textarea {
    color: var(--text-primary) !important;
}

[data-theme="dark"] h1, [data-theme="dark"] h2, [data-theme="dark"] h3,
[data-theme="dark"] label, [data-theme="dark"] th, [data-theme="dark"] td {
    color: #e5e7eb !important;
}

[data-theme="dark"] input, [data-theme="dark"] select, [data-theme="dark"] textarea {
    background: #0a0f1e !important;
    color: #e5e7eb !important;
    border-color: #2d3748 !important;
}

[data-theme="premium"] h1, [data-theme="premium"] h2, [data-theme="premium"] h3,
[data-theme="premium"] label, [data-theme="premium"] th, [data-theme="premium"] td {
    color: #c9d1d9 !important;
}

[data-theme="premium"] input, [data-theme="premium"] select, [data-theme="premium"] textarea {
    background: #0f1419 !important;
    color: #c9d1d9 !important;
    border-color: #30363d !important;
}

/* COLORES LEGIBLES PARA CADA TEMA */

/* MODO CLARO - Letras oscuras */
[data-theme="light"] {
    --text-primary: #2c3e50;
    --text-secondary: #5a6c7d;
}

[data-theme="light"] h1,
[data-theme="light"] h2,
[data-theme="light"] h3,
[data-theme="light"] h4,
[data-theme="light"] label,
[data-theme="light"] th,
[data-theme="light"] td,
[data-theme="light"] p {
    color: #2c3e50 !important;
}

/* MODO OSCURO - Letras claras */
[data-theme="dark"] {
    --text-primary: #e5e7eb;
    --text-secondary: #9ca3af;
}

[data-theme="dark"] h1,
[data-theme="dark"] h2,
[data-theme="dark"] h3,
[data-theme="dark"] h4,
[data-theme="dark"] label,
[data-theme="dark"] th,
[data-theme="dark"] td,
[data-theme="dark"] p {
    color: #e5e7eb !important;
}

[data-theme="dark"] input,
[data-theme="dark"] select,
[data-theme="dark"] textarea {
    background: #0a0f1e !important;
    color: #e5e7eb !important;
    border-color: #2d3748 !important;
}

/* MODO ORIGINAL - Letras oscuras */
[data-theme="original"] {
    --text-primary: #2c3e50;
    --text-secondary: #5a6c7d;
}

[data-theme="original"] h1,
[data-theme="original"] h2,
[data-theme="original"] h3,
[data-theme="original"] h4,
[data-theme="original"] label,
[data-theme="original"] th,
[data-theme="original"] td,
[data-theme="original"] p {
    color: #2c3e50 !important;
}

/* MODO PREMIUM - Letras claras brillantes */
[data-theme="premium"] {
    --text-primary: #c9d1d9;
    --text-secondary: #8b93a0;
}

[data-theme="premium"] h1,
[data-theme="premium"] h2,
[data-theme="premium"] h3,
[data-theme="premium"] h4,
[data-theme="premium"] label,
[data-theme="premium"] th,
[data-theme="premium"] td,
[data-theme="premium"] p {
    color: #c9d1d9 !important;
}

[data-theme="premium"] input,
[data-theme="premium"] select,
[data-theme="premium"] textarea {
    background: #0f1419 !important;
    color: #c9d1d9 !important;
    border-color: #30363d !important;
}

/* Títulos destacados */
h1, h2 {
    font-weight: 700 !important;
}

h3, h4, label {
    font-weight: 600 !important;
}

/* INPUTS VISIBLES EN TODOS LOS FORMULARIOS - MÁXIMA PRIORIDAD */
body input[type="text"],
body input[type="email"],
body input[type="tel"],
body input[type="number"],
body select,
body textarea {
    background-color: #ffffff !important;
    color: #1a202c !important;
    border: 2px solid #cbd5e0 !important;
    padding: 12px 15px !important;
}

body input::placeholder,
body textarea::placeholder {
    color: #a0aec0 !important;
    opacity: 1 !important;
}

body label {
    color: #2d3748 !important;
}

/* MODO OSCURO */
[data-theme="dark"] input[type="text"],
[data-theme="dark"] input[type="email"],
[data-theme="dark"] input[type="tel"],
[data-theme="dark"] input[type="number"],
[data-theme="dark"] select,
[data-theme="dark"] textarea {
    background-color: #2d3748 !important;
    color: #f7fafc !important;
    border-color: #4a5568 !important;
}

[data-theme="dark"] input::placeholder {
    color: #718096 !important;
}

[data-theme="dark"] label {
    color: #e2e8f0 !important;
}

/* MODO PREMIUM */
[data-theme="premium"] input[type="text"],
[data-theme="premium"] input[type="email"],
[data-theme="premium"] input[type="tel"],
[data-theme="premium"] input[type="number"],
[data-theme="premium"] select,
[data-theme="premium"] textarea {
    background-color: #1a202c !important;
    color: #e2e8f0 !important;
    border-color: rgba(56, 189, 248, 0.5) !important;
}

[data-theme="premium"] label {
    color: #c9d1d9 !important;
}
</style>

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
            <?php if (isset($_SESSION['admin_nombre'])): ?>
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

<script>
const themeToggle = document.getElementById('themeToggle');
const html = document.documentElement;
const themeIcon = themeToggle.querySelector('.theme-toggle-icon');
const themeText = themeToggle.querySelector('.theme-toggle-text');

const themes = {
    light: {
        icon: '',
        text: 'Modo Claro',
        next: 'dark'
    },
    dark: {
        icon: '',
        text: 'Modo Oscuro',
        next: 'original'
    },
    original: {
        icon: '',
        text: 'Modo Original',
        next: 'premium'
    },
    premium: {
        icon: '',
        text: 'Modo Premium',
        next: 'light'
    }
};

let currentTheme = localStorage.getItem('theme') || 'light';
html.setAttribute('data-theme', currentTheme);
updateThemeToggle(currentTheme);

themeToggle.addEventListener('click', function(e) {
    const rect = themeToggle.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    // Crear partículas
    for (let i = 0; i < 15; i++) {
        createParticle(x, y);
    }

    const nextTheme = themes[currentTheme].next;
    currentTheme = nextTheme;
    html.setAttribute('data-theme', nextTheme);
    localStorage.setItem('theme', nextTheme);
    updateThemeToggle(nextTheme);
});

function updateThemeToggle(theme) {
    const themeConfig = themes[theme];
    themeIcon.textContent = themeConfig.icon;
    themeText.textContent = themeConfig.text;
}

function createParticle(x, y) {
    const particle = document.createElement('div');
    particle.className = 'particle';

    const angle = Math.random() * Math.PI * 2;
    const velocity = 60 + Math.random() * 60;
    const tx = Math.cos(angle) * velocity;
    const ty = Math.sin(angle) * velocity;

    particle.style.left = x + 'px';
    particle.style.top = y + 'px';
    particle.style.setProperty('--tx', tx + 'px');
    particle.style.setProperty('--ty', ty + 'px');

    themeToggle.appendChild(particle);
    setTimeout(() => particle.remove(), 1200);
}
</script>




