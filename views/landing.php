<?php
// No llamar session_start() porque ya se inició en index.php
$isLoggedIn = isset($_SESSION['logueado']) && $_SESSION['logueado'] === true;

// Si está logueado, incluir el navbar
if ($isLoggedIn) {
    require_once __DIR__ . '/layouts/navbar.php';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Biblioteca CIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

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

body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg-primary);
    color: var(--text-primary);
    transition: var(--transition);
    min-height: 100vh;
    overflow-x: hidden;
}

/* Premium mode effects */
[data-theme="premium"] body {
    background: linear-gradient(135deg, #0f1419 0%, #1a1f29 50%, #0f1419 100%);
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
    0%, 100% { opacity: 0.8; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.1); }
}

[data-theme="premium"] body::after {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent 0%, rgba(56, 189, 248, 0.05) 50%, transparent 100%);
    animation: premium-scan 8s linear infinite;
    pointer-events: none;
    z-index: 0;
}

@keyframes premium-scan {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Hero Section */
.hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    min-height: <?php echo $isLoggedIn ? '80vh' : '100vh'; ?>;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 80px 20px;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

[data-theme="dark"] .hero {
    background: linear-gradient(135deg, #1e3a8a 0%, #0a0f1e 100%);
}

[data-theme="original"] .hero {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
}

[data-theme="premium"] .hero {
    background: linear-gradient(135deg, #0c4a6e 0%, #1e293b 50%, #0f172a 100%);
    box-shadow: 0 20px 60px rgba(56, 189, 248, 0.3);
}

.hero::before {
    content: '';
    position: absolute;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 50px 50px;
    animation: grid-move 20s linear infinite;
    opacity: 0.3;
}

@keyframes grid-move {
    0% { transform: translate(0, 0); }
    100% { transform: translate(50px, 50px); }
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
}

.hero-icon {
    font-size: 5rem;
    margin-bottom: 30px;
    animation: float 3s ease-in-out infinite;
    cursor: pointer;
    transition: all 0.3s ease;
}

.hero-icon:hover {
    transform: scale(1.2) rotate(10deg);
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    25% { transform: translateY(-20px) rotate(-5deg); }
    50% { transform: translateY(-10px) rotate(5deg); }
    75% { transform: translateY(-25px) rotate(-3deg); }
}

.hero h1 {
    font-size: 3.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.hero p {
    font-size: 1.4rem;
    color: rgba(255, 255, 255, 0.95);
    margin-bottom: 40px;
}

.hero-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 15px 35px;
    border: none;
    border-radius: 50px;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.btn-primary {
    background: white;
    color: var(--primary);
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.btn-primary:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 2px solid white;
}

.btn-secondary:hover {
    background: white;
    color: var(--primary);
    transform: translateY(-5px);
}

/* Features Section */
.features {
    padding: 100px 20px;
    background: var(--bg-primary);
    position: relative;
    z-index: 1;
}

.features-container {
    max-width: 1200px;
    margin: 0 auto;
}

.features h2 {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 20px;
}

.features-subtitle {
    text-align: center;
    font-size: 1.1rem;
    color: var(--text-secondary);
    margin-bottom: 60px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 40px;
}

.feature-card {
    background: var(--bg-card);
    padding: 50px 40px;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow);
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid var(--border-color);
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    display: block;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.5s ease;
}

.feature-card:hover::before {
    left: 100%;
}

[data-theme="premium"] .feature-card {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: rgba(56, 189, 248, 0.2);
}

.feature-card:hover {
    transform: translateY(-20px) scale(1.05);
    box-shadow: 0 25px 70px var(--shadow);
}

[data-theme="premium"] .feature-card:hover {
    box-shadow: 0 25px 70px rgba(56, 189, 248, 0.6);
    border-color: var(--primary);
}

.feature-card:hover .feature-icon {
    transform: scale(1.4) rotate(360deg);
    filter: drop-shadow(0 0 20px var(--primary));
}

.feature-icon {
    font-size: 4.5rem;
    margin-bottom: 30px;
    transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    display: inline-block;
}

.feature-card h3 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 20px;
}

.feature-card p {
    color: var(--text-secondary);
    line-height: 1.8;
    font-size: 1rem;
}

/* Footer */
.footer {
    background: var(--bg-card);
    padding: 40px 20px;
    text-align: center;
    border-top: 2px solid var(--border-color);
    position: relative;
    z-index: 1;
}

[data-theme="premium"] .footer {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-top-color: rgba(56, 189, 248, 0.3);
}

.footer p {
    color: var(--text-secondary);
    font-size: 0.95rem;
}

@media (max-width: 768px) {
    .hero h1 {
        font-size: 2.5rem;
    }

    .hero p {
        font-size: 1.1rem;
    }

    .hero-buttons {
        flex-direction: column;
        width: 100%;
        max-width: 300px;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .features h2 {
        font-size: 2rem;
    }

    .features-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-icon"></div>
        <h1>Sistema de Biblioteca CIF</h1>
        <p>Gestión moderna y eficiente de préstamos bibliotecarios</p>
        <div class="hero-buttons">
            <?php if ($isLoggedIn): ?>
                <a href="index.php?ruta=libros" class="btn btn-primary">
                     Ver Catálogo
                </a>
                <a href="index.php?ruta=home" class="btn btn-secondary">
                     Ir al Dashboard
                </a>
            <?php else: ?>
                <a href="index.php?ruta=login" class="btn btn-primary">
                     Iniciar Sesión
                </a>
                <a href="#features" class="btn btn-secondary">
                    ℹ Conocer Más
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features" id="features">
    <div class="features-container">
        <h2>Funcionalidades Principales</h2>
        <p class="features-subtitle">Explora todas las herramientas disponibles para gestionar tu biblioteca</p>
        <div class="features-grid">
            <?php if ($isLoggedIn): ?>
                <a href="index.php?ruta=libros" class="feature-card">
                    <div class="feature-icon"></div>
                    <h3>Gestión de Libros</h3>
                    <p>Administra el catálogo completo de libros de la biblioteca con facilidad. Agrega, edita y elimina registros de manera eficiente.</p>
                </a>
                <a href="index.php?ruta=usuarios" class="feature-card">
                    <div class="feature-icon"></div>
                    <h3>Control de Usuarios</h3>
                    <p>Registra y gestiona estudiantes, docentes y personal. Mantén un control completo de los miembros de tu biblioteca.</p>
                </a>
                <a href="index.php?ruta=prestamos" class="feature-card">
                    <div class="feature-icon"></div>
                    <h3>Préstamos</h3>
                    <p>Controla préstamos y devoluciones con seguimiento en tiempo real. Gestiona multas y fechas de vencimiento automáticamente.</p>
                </a>
            <?php else: ?>
                <div class="feature-card" style="cursor: default;">
                    <div class="feature-icon"></div>
                    <h3>Gestión de Libros</h3>
                    <p>Administra el catálogo completo de libros de la biblioteca con facilidad. Agrega, edita y elimina registros de manera eficiente.</p>
                </div>
                <div class="feature-card" style="cursor: default;">
                    <div class="feature-icon"></div>
                    <h3>Control de Usuarios</h3>
                    <p>Registra y gestiona estudiantes, docentes y personal. Mantén un control completo de los miembros de tu biblioteca.</p>
                </div>
                <div class="feature-card" style="cursor: default;">
                    <div class="feature-icon"></div>
                    <h3>Préstamos</h3>
                    <p>Controla préstamos y devoluciones con seguimiento en tiempo real. Gestiona multas y fechas de vencimiento automáticamente.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <p>&copy; <?php echo date('Y'); ?> Sistema de Biblioteca CIF. Todos los derechos reservados.</p>
</footer>

</body>
</html>
