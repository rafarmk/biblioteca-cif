<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca CIF - Sistema de Gestión</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            --success: #27ae60;
            --warning: #f39c12;
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
            --accent: #ef4444;
        }

        [data-theme="original"] {
            --bg-primary: #f0f4f8;
            --bg-secondary: #e3e8ef;
            --bg-card: #ffffff;
            --text-primary: #2c3e50;
            --text-secondary: #5a6c7d;
            --border-color: #d1dce5;
            --shadow: rgba(52, 152, 219, 0.15);
        }

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
            min-height: 100vh;
            transition: all 0.3s ease;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero {
    min-height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 40px 20px;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
        }

        [data-theme="premium"] .hero {
            background: linear-gradient(135deg, #0f1419 0%, #1a1f29 50%, #0f1419 100%);
        }

        [data-theme="premium"] .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 50%, rgba(56, 189, 248, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(96, 165, 250, 0.15) 0%, transparent 50%);
            animation: premium-glow 15s ease-in-out infinite alternate;
            pointer-events: none;
        }

        @keyframes premium-glow {
            0%, 100% { opacity: 0.8; }
            50% { opacity: 1; }
        }

        .hero-content {
            max-width: 1400px;
            width: 100%;
            position: relative;
            z-index: 10;
        }

        .hero-header {
            text-align: center;
            margin-bottom: 80px;
        }

        .hero-icon {
            font-size: 8rem;
            margin-bottom: 30px;
            animation: float 4s ease-in-out infinite;
            display: inline-block;
            filter: drop-shadow(0 10px 30px var(--shadow));
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-20px) rotate(5deg); }
            50% { transform: translateY(0) rotate(0deg); }
            75% { transform: translateY(-15px) rotate(-5deg); }
        }

        .hero-title {
            font-size: 4.5rem;
            font-weight: 900;
            color: var(--text-primary);
            margin-bottom: 20px;
            letter-spacing: -2px;
            line-height: 1.1;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 40px;
        }

        /* Cards Grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 40px;
            padding: 0 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--bg-card);
            border-radius: 24px;
            padding: 50px 40px;
            text-align: center;
            box-shadow: 0 10px 40px var(--shadow);
            border: 2px solid var(--border-color);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        [data-theme="premium"] .feature-card {
            background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
            border-color: rgba(56, 189, 248, 0.2);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 0;
        }

        .feature-card:hover::before {
            opacity: 0.05;
        }

        .feature-card:hover {
            transform: translateY(-15px) scale(1.03);
            box-shadow: 0 20px 60px var(--shadow);
            border-color: var(--primary);
        }

        .feature-card * {
            position: relative;
            z-index: 1;
        }

        .card-icon {
            font-size: 5rem;
            margin-bottom: 30px;
            display: inline-block;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .feature-card:hover .card-icon {
            animation: spin 0.6s ease-in-out;
        }

        @keyframes spin {
            0% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.2); }
            100% { transform: rotate(360deg) scale(1); }
        }

        .card-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 15px;
        }

        .card-description {
            font-size: 1.1rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .card-button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .card-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(52, 152, 219, 0.4);
        }

        /* Theme Toggle */
        .theme-toggle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 10000;
            background: var(--bg-card);
            border: 2px solid var(--border-color);
            border-radius: 50px;
            padding: 16px 28px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 30px var(--shadow);
            transition: all 0.4s ease;
        }

        .theme-toggle:hover {
            transform: translateY(-5px) scale(1.05);
        }

        /* Footer */
        footer {
    background: transparent;
    color: var(--text-secondary);
    text-align: center;
    padding: 25px 20px;
    margin-top: 80px;
    border-top: none;
    box-shadow: none;
    position: relative;
    z-index: 100;
    width: 100%;
}

        footer p:first-child {
            margin: 0;
            font-size: 17px;
            font-weight: 600;
            color: var(--text-primary);
        }

        footer p:last-child {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.85;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 3rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .hero-icon {
                font-size: 5rem;
            }

            .cards-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .feature-card {
                padding: 40px 30px;
            }
        }

        /* Navbar Include */
        <?php if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true): ?>
        body {
            padding-top: 80px;
        }
        <?php endif; ?>
    </style>
</head>
<body>
    <?php if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true): ?>
        <?php require_once __DIR__ . '/layouts/navbar.php'; ?>
    <?php endif; ?>

    <div class="hero">
        <div class="hero-content">
            <div class="hero-header">
                <div class="hero-icon"></div>
                <h1 class="hero-title">Biblioteca CIF</h1>
                <p class="hero-subtitle">Sistema Moderno de Gestión de Préstamos Bibliotecarios</p>
            </div>

            <div class="cards-grid">
                <!-- Card 1: Libros -->
                <div class="feature-card" onclick="window.location.href='index.php?ruta=libros'">
                    <div class="card-icon"></div>
                    <h2 class="card-title">Gestión de Libros</h2>
                    <p class="card-description">
                        Administra tu colección completa de libros con control de inventario y disponibilidad en tiempo real.
                    </p>
                    <a href="index.php?ruta=libros" class="card-button">Ver Catálogo</a>
                </div>

                <!-- Card 2: Usuarios -->
                <div class="feature-card" onclick="window.location.href='index.php?ruta=usuarios'">
                    <div class="card-icon"></div>
                    <h2 class="card-title">Gestión de Usuarios</h2>
                    <p class="card-description">
                        Registra y administra estudiantes, docentes y personal con historial completo de préstamos.
                    </p>
                    <a href="index.php?ruta=usuarios" class="card-button">Ver Usuarios</a>
                </div>

                <!-- Card 3: Préstamos -->
                <div class="feature-card" onclick="window.location.href='index.php?ruta=prestamos'">
                    <div class="card-icon"></div>
                    <h2 class="card-title">Control de Préstamos</h2>
                    <p class="card-description">
                        Gestiona préstamos, devoluciones y consulta el historial completo con alertas de atrasos.
                    </p>
                    <a href="index.php?ruta=prestamos" class="card-button">Ver Préstamos</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Theme Toggle -->
    <div class="theme-toggle" id="themeToggle">
        <span style="font-size: 24px;"></span>
        <span style="font-weight: 700; font-size: 16px; color: var(--text-primary);">Modo Claro</span>
    </div>

    <?php require_once __DIR__ . "/layouts/footer.php"; ?>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        const themeIcon = themeToggle.querySelector('span:first-child');
        const themeText = themeToggle.querySelector('span:last-child');

        const themes = {
            light: { icon: '', text: 'Modo Claro', next: 'dark' },
            dark: { icon: '', text: 'Modo Oscuro', next: 'original' },
            original: { icon: '', text: 'Modo Original', next: 'premium' },
            premium: { icon: '', text: 'Modo Premium', next: 'light' }
        };

        let currentTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', currentTheme);
        updateTheme(currentTheme);

        themeToggle.addEventListener('click', function() {
            currentTheme = themes[currentTheme].next;
            html.setAttribute('data-theme', currentTheme);
            localStorage.setItem('theme', currentTheme);
            updateTheme(currentTheme);
        });

        function updateTheme(theme) {
            themeIcon.textContent = themes[theme].icon;
            themeText.textContent = themes[theme].text;
        }

        // Animación de entrada para las cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.feature-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(50px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
</body>
</html>


