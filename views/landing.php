<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca CIF - Sistema de Gestión</title>
    
    <!-- Sistema de Temas Centralizado -->
    <link rel="stylesheet" href="assets/css/themes.css">
    
    <style>
        /* Estilos específicos del Landing */
        .hero {
            min-height: calc(100vh - 75px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background: var(--bg-primary);
            position: relative;
            z-index: 1;
        }

        .hero-content {
            max-width: 1200px;
            width: 100%;
            text-align: center;
        }

        .hero-header {
            margin-bottom: 60px;
        }

        .hero-icon {
            width: 80px;
            height: 80px;
            background: var(--primary);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin-bottom: 30px;
            animation: float 3s ease-in-out infinite;
        }

        .hero-icon::before {
            content: '';
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: var(--text-secondary);
            font-weight: 500;
            max-width: 700px;
            margin: 0 auto;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            margin-top: 60px;
        }

        .feature-card {
            background: var(--bg-card);
            border: 2px solid var(--border-color);
            border-radius: 20px;
            padding: 40px 30px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px var(--shadow);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            opacity: 0.1;
            transition: left 0.6s ease;
        }

        .feature-card:hover::before {
            left: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: var(--primary);
            box-shadow: 0 20px 60px var(--shadow);
        }

        [data-theme="premium"] .feature-card:hover {
            box-shadow: 0 20px 60px rgba(56, 189, 248, 0.4);
        }

        .card-icon {
            width: 70px;
            height: 70px;
            background: var(--primary);
            opacity: 0.9;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            margin-bottom: 25px;
            transition: all 0.4s ease;
        }

        .feature-card:hover .card-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-card:nth-child(1) .card-icon::before { content: ''; }
        .feature-card:nth-child(2) .card-icon::before { content: ''; }
        .feature-card:nth-child(3) .card-icon::before { content: ''; }

        .card-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 15px;
        }

        .card-description {
            font-size: 1rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .card-button {
            display: inline-block;
            padding: 12px 30px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .card-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(52, 152, 219, 0.5);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .cards-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['admin_nombre'])): ?>
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
                <div class="feature-card" onclick="window.location.href='index.php?ruta=libros'">
                    <div class="card-icon"></div>
                    <h2 class="card-title">Gestión de Libros</h2>
                    <p class="card-description">
                        Administra tu colección completa de libros con control de inventario y disponibilidad en tiempo real.
                    </p>
                    <a href="index.php?ruta=libros" class="card-button">Ver Catálogo</a>
                </div>

                <div class="feature-card" onclick="window.location.href='index.php?ruta=usuarios'">
                    <div class="card-icon"></div>
                    <h2 class="card-title">Gestión de Usuarios</h2>
                    <p class="card-description">
                        Registra y administra estudiantes, docentes y personal con historial completo de préstamos.
                    </p>
                    <a href="index.php?ruta=usuarios" class="card-button">Ver Usuarios</a>
                </div>

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

    <?php require_once __DIR__ . "/layouts/footer.php"; ?>

    <script>
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
