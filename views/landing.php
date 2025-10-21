<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca CIF - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7e22ce 100%);
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,165.3C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            animation: wave 10s linear infinite;
        }
        
        @keyframes wave {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        .hero-content {
            text-align: center;
            color: white;
            z-index: 10;
            padding: 40px 20px;
            animation: fadeInUp 1s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .hero-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.95;
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-hero {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary-hero {
            background: white;
            color: #1e3c72;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-primary-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        
        .btn-secondary-hero {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
        }
        
        .btn-secondary-hero:hover {
            background: white;
            color: #1e3c72;
            transform: translateY(-3px);
        }
        
        .features-section {
            padding: 80px 20px;
            background: linear-gradient(to bottom, #f8f9fa, #ffffff);
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 50px;
            color: #1e3c72;
        }
        
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            border: 2px solid transparent;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            border-color: #7e22ce;
        }
        
        .feature-icon {
            font-size: 60px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #1e3c72, #7e22ce);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #1e3c72;
        }
        
        .feature-description {
            color: #666;
            line-height: 1.6;
        }
        
        .feature-btn {
            margin-top: 20px;
            padding: 10px 25px;
            border-radius: 25px;
            border: 2px solid #7e22ce;
            background: white;
            color: #7e22ce;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .feature-btn:hover {
            background: #7e22ce;
            color: white;
            transform: scale(1.05);
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            .hero-subtitle {
                font-size: 1rem;
            }
            .btn-hero {
                padding: 12px 30px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-icon">
                <i class="fas fa-book-reader"></i>
            </div>
            <h1 class="hero-title">Sistema de Biblioteca CIF</h1>
            <p class="hero-subtitle">Gestión moderna y eficiente de préstamos bibliotecarios</p>
            <div class="hero-buttons">
                <a href="index.php?ruta=libros" class="btn-hero btn-primary-hero">
                    <i class="fas fa-search"></i>
                    Ver Catálogo
                </a>
                <a href="index.php?ruta=home" class="btn-hero btn-secondary-hero">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Funcionalidades Principales</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3 class="feature-title">Gestión de Libros</h3>
                        <p class="feature-description">
                            Administra el catálogo completo de libros de la biblioteca con facilidad. Registra, edita y elimina libros con toda la información necesaria.
                        </p>
                        <a href="index.php?ruta=libros" class="btn feature-btn">
                            Ver Libros <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">Control de Usuarios</h3>
                        <p class="feature-description">
                            Registra y gestiona estudiantes, docentes y personal administrativo. Mantén un control completo de todos los usuarios del sistema.
                        </p>
                        <a href="index.php?ruta=usuarios" class="btn feature-btn">
                            Ver Usuarios <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h3 class="feature-title">Préstamos</h3>
                        <p class="feature-description">
                            Controla préstamos y devoluciones con registro detallado de fechas. Sistema inteligente de alertas y recordatorios.
                        </p>
                        <button class="btn feature-btn" disabled>
                            Próximamente <i class="fas fa-clock"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>