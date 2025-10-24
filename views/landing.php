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

        /* ========== VARIABLES DE COLOR ========== */
        :root {
            /* Modo Claro */
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --text-primary: #1e3c72;
            --text-secondary: #666;
            --card-bg: #ffffff;
            --card-shadow: rgba(0,0,0,0.1);
            --gradient-1: #1e3c72;
            --gradient-2: #2a5298;
            --gradient-3: #7e22ce;
        }

        /* Modo Oscuro */
        body.dark-mode {
            --bg-primary: #0a0f1e;
            --bg-secondary: #111827;
            --text-primary: #e5e7eb;
            --text-secondary: #9ca3af;
            --card-bg: #1f2937;
            --card-shadow: rgba(59, 130, 246, 0.15);
            --gradient-1: #1e3a8a;
            --gradient-2: #3b82f6;
            --gradient-3: #60a5fa;
            --accent-1: #2563eb;
            --accent-2: #3b82f6;
            --silver: #d1d5db;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            background: var(--bg-primary);
            transition: background 0.5s ease, color 0.5s ease;
        }

        /* ========== TOGGLE MODO OSCURO ESPECTACULAR ========== */
        .dark-mode-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 
                0 10px 30px rgba(0,0,0,0.3),
                0 0 20px rgba(102, 126, 234, 0.4);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            overflow: hidden;
        }

        body.dark-mode .dark-mode-toggle {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);
            box-shadow: 
                0 10px 40px rgba(37, 99, 235, 0.4),
                0 0 30px rgba(30, 58, 138, 0.3),
                inset 0 0 20px rgba(209, 213, 219, 0.15);
            animation: silverPulse 2s ease-in-out infinite;
        }

        @keyframes silverPulse {
            0%, 100% { 
                box-shadow: 
                    0 10px 40px rgba(37, 99, 235, 0.4),
                    0 0 30px rgba(30, 58, 138, 0.3),
                    inset 0 0 20px rgba(209, 213, 219, 0.15);
            }
            50% { 
                box-shadow: 
                    0 10px 50px rgba(37, 99, 235, 0.6),
                    0 0 40px rgba(30, 58, 138, 0.5),
                    inset 0 0 30px rgba(209, 213, 219, 0.25);
            }
        }

        .dark-mode-toggle::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            animation: shine-toggle 3s linear infinite;
        }

        @keyframes shine-toggle {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .dark-mode-toggle:hover {
            transform: scale(1.1) translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
        }

        body.dark-mode .dark-mode-toggle:hover {
            transform: scale(1.1) translateY(-3px) rotate(5deg);
            box-shadow: 
                0 15px 50px rgba(37, 99, 235, 0.6),
                0 0 60px rgba(30, 58, 138, 0.5);
        }

        .toggle-icon {
            font-size: 20px;
            transition: transform 0.4s ease;
            filter: drop-shadow(0 0 5px rgba(255,255,255,0.5));
        }

        .dark-mode-toggle:hover .toggle-icon {
            transform: rotate(20deg) scale(1.2);
        }

        body.dark-mode .dark-mode-toggle:hover .toggle-icon {
            transform: rotate(-20deg) scale(1.2);
            filter: drop-shadow(0 0 10px rgba(59, 130, 246, 0.9));
        }
        
        /* ========== HERO SECTION CON PARTÍCULAS ========== */
        .hero-section {
            background: linear-gradient(135deg, var(--gradient-1) 0%, var(--gradient-2) 50%, var(--gradient-3) 100%);
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            transition: all 0.5s ease;
        }

        body.dark-mode .hero-section {
            box-shadow: 
                inset 0 0 100px rgba(88, 166, 255, 0.08),
                inset 0 0 50px rgba(31, 111, 235, 0.05);
        }
        
        /* Partículas flotantes animadas */
        .hero-section::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background-image: 
                radial-gradient(2px 2px at 20% 30%, white, transparent),
                radial-gradient(2px 2px at 60% 70%, white, transparent),
                radial-gradient(1px 1px at 50% 50%, white, transparent),
                radial-gradient(1px 1px at 80% 10%, white, transparent),
                radial-gradient(2px 2px at 90% 60%, white, transparent),
                radial-gradient(1px 1px at 33% 80%, white, transparent),
                radial-gradient(2px 2px at 15% 90%, white, transparent);
            background-size: 200% 200%;
            background-position: 0% 0%;
            opacity: 0.5;
            animation: particles 20s linear infinite;
        }

        @keyframes particles {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Ondas animadas en el fondo */
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,165.3C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x;
            background-size: 50% 100%;
            animation: wave 15s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite;
            z-index: 1;
        }

        @keyframes wave {
            0%, 100% { 
                transform: translateX(0) translateY(0);
            }
            50% { 
                transform: translateX(-25%) translateY(-10px);
            }
        }

        .hero-content {
            text-align: center;
            color: white;
            z-index: 10;
            padding: 40px 20px;
            animation: fadeInUp 1.2s ease-out;
            position: relative;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Icono con efecto de brillo giratorio */
        .hero-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite, glow 2s ease-in-out infinite alternate;
            filter: drop-shadow(0 0 20px rgba(255,255,255,0.5));
            position: relative;
        }

        @keyframes float {
            0%, 100% { 
                transform: translateY(0) rotate(0deg); 
            }
            25% { 
                transform: translateY(-15px) rotate(-5deg); 
            }
            75% { 
                transform: translateY(-10px) rotate(5deg); 
            }
        }

        @keyframes glow {
            from {
                text-shadow: 
                    0 0 10px rgba(255,255,255,0.8),
                    0 0 20px rgba(255,255,255,0.6),
                    0 0 30px rgba(255,255,255,0.4);
            }
            to {
                text-shadow: 
                    0 0 20px rgba(255,255,255,1),
                    0 0 40px rgba(255,255,255,0.8),
                    0 0 60px rgba(255,255,255,0.6);
            }
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: fadeInUp 1.4s ease-out;
            background: linear-gradient(45deg, #fff, #f0f0f0, #fff);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease infinite, fadeInUp 1.4s ease-out;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.95;
            animation: fadeInUp 1.6s ease-out;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1.8s ease-out;
        }

        .btn-hero {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        /* Efecto de brillo que se mueve */
        .btn-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-hero:hover::before {
            left: 100%;
        }

        .btn-primary-hero {
            background: white;
            color: #1e3c72;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-primary-hero:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            color: #1e3c72;
        }

        .btn-secondary-hero {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
            backdrop-filter: blur(10px);
        }

        .btn-secondary-hero:hover {
            background: white;
            color: #1e3c72;
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 35px rgba(255,255,255,0.4);
        }

        /* ========== FEATURES SECTION ========== */
        .features-section {
            padding: 80px 20px;
            background: linear-gradient(to bottom, var(--bg-secondary), var(--bg-primary));
            position: relative;
            transition: all 0.5s ease;
        }

        body.dark-mode .features-section {
            background: linear-gradient(to bottom, var(--bg-secondary), var(--bg-primary));
            box-shadow: inset 0 0 100px rgba(88, 166, 255, 0.03);
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 60px;
            color: var(--text-primary);
            position: relative;
            animation: fadeInDown 1s ease-out;
            transition: all 0.5s ease;
        }

        body.dark-mode .section-title {
            text-shadow: 
                0 0 20px rgba(59, 130, 246, 0.5),
                0 0 40px rgba(30, 58, 138, 0.3);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Subrayado animado del título */
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--gradient-1), var(--gradient-3));
            border-radius: 2px;
            animation: underlineGrow 1.5s ease-out;
            transition: all 0.5s ease;
        }

        body.dark-mode .section-title::after {
            box-shadow: 
                0 0 20px rgba(59, 130, 246, 0.7),
                0 0 40px rgba(30, 58, 138, 0.4);
        }

        @keyframes underlineGrow {
            from { width: 0; }
            to { width: 100px; }
        }

        .feature-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px var(--card-shadow);
            transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 1s ease-out backwards;
        }

        body.dark-mode .feature-card {
            box-shadow: 
                0 10px 40px rgba(37, 99, 235, 0.2),
                0 0 30px rgba(30, 58, 138, 0.15),
                inset 0 0 20px rgba(59, 130, 246, 0.05);
            border: 1px solid rgba(59, 130, 246, 0.15);
        }

        /* Efecto de brillo que pasa por la tarjeta */
        .feature-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(126, 34, 206, 0.1),
                transparent
            );
            transform: rotate(45deg);
            animation: shine 15s ease-in-out infinite;
            transition: all 0.5s ease;
        }

        body.dark-mode .feature-card::before {
            background: linear-gradient(
                45deg,
                transparent,
                rgba(59, 130, 246, 0.2),
                rgba(209, 213, 219, 0.15),
                transparent
            );
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            50% { transform: translateX(100%) translateY(100%) rotate(45deg); }
            100% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        }

        /* Animación escalonada para las tarjetas */
        .feature-card:nth-child(1) { animation-delay: 0.2s; }
        .feature-card:nth-child(2) { animation-delay: 0.4s; }
        .feature-card:nth-child(3) { animation-delay: 0.6s; }

        .feature-card:hover {
            transform: translateY(-15px) scale(1.02) rotateX(5deg);
            box-shadow: 
                0 25px 50px rgba(0,0,0,0.15),
                0 0 0 1px rgba(126, 34, 206, 0.1),
                inset 0 0 20px rgba(126, 34, 206, 0.05);
            border-color: #7e22ce;
        }

        body.dark-mode .feature-card:hover {
            transform: translateY(-15px) scale(1.02) rotateX(5deg);
            box-shadow: 
                0 25px 60px rgba(37, 99, 235, 0.4),
                0 0 50px rgba(30, 58, 138, 0.3),
                inset 0 0 40px rgba(59, 130, 246, 0.1);
            border-color: var(--gradient-1);
        }

        /* Icono con animación de rotación 3D */
        .feature-icon {
            font-size: 60px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--gradient-1), var(--gradient-3));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
            animation: iconPulse 6s ease-in-out infinite;
            transition: all 0.8s ease;
        }

        body.dark-mode .feature-icon {
            filter: drop-shadow(0 0 15px rgba(59, 130, 246, 0.6));
        }

        @keyframes iconPulse {
            0%, 100% { 
                transform: scale(1) rotateY(0deg);
            }
            50% { 
                transform: scale(1.05) rotateY(180deg);
            }
        }

        .feature-card:hover .feature-icon {
            animation: iconSpin 3s ease-in-out infinite;
            filter: drop-shadow(0 0 10px rgba(126, 34, 206, 0.5));
        }

        body.dark-mode .feature-card:hover .feature-icon {
            filter: drop-shadow(0 0 25px rgba(59, 130, 246, 0.9)) 
                    drop-shadow(0 0 50px rgba(30, 58, 138, 0.6));
        }

        @keyframes iconSpin {
            0% { transform: rotateY(0deg) scale(1); }
            50% { transform: rotateY(180deg) scale(1.1); }
            100% { transform: rotateY(360deg) scale(1); }
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--text-primary);
            transition: all 0.5s ease;
        }

        .feature-card:hover .feature-title {
            color: var(--gradient-3);
            transform: translateX(5px);
        }

        body.dark-mode .feature-card:hover .feature-title {
            text-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }

        .feature-description {
            color: var(--text-secondary);
            line-height: 1.6;
            transition: all 0.5s ease;
        }

        body.dark-mode .feature-description {
            color: var(--text-secondary);
        }

        .feature-btn {
            margin-top: 20px;
            padding: 12px 30px;
            border-radius: 25px;
            border: 2px solid var(--gradient-3);
            background: var(--card-bg);
            color: var(--gradient-3);
            font-weight: 600;
            transition: all 0.5s ease;
            text-decoration: none;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        body.dark-mode .feature-btn {
            border-color: var(--gradient-1);
            color: var(--gradient-1);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
        }

        .feature-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: var(--gradient-3);
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
            z-index: -1;
        }

        body.dark-mode .feature-btn::before {
            background: linear-gradient(135deg, var(--gradient-1), var(--gradient-2));
        }

        .feature-btn:hover {
            color: white;
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(126, 34, 206, 0.4);
        }

        .feature-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        body.dark-mode .feature-btn:hover {
            color: white;
            box-shadow: 
                0 5px 30px rgba(59, 130, 246, 0.6),
                0 0 40px rgba(30, 58, 138, 0.4);
        }

        /* ========== RESPONSIVE DESIGN ========== */
        
        /* Tablets (768px - 991px) */
        @media (max-width: 991px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .hero-icon {
                font-size: 60px;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .feature-icon {
                font-size: 50px;
            }
            
            .features-section {
                padding: 60px 20px;
            }
        }
        
        /* Móviles (576px - 767px) */
        @media (max-width: 767px) {
            .hero-section {
                min-height: 60vh;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .hero-icon {
                font-size: 50px;
            }
            
            .btn-hero {
                padding: 12px 30px;
                font-size: 1rem;
            }
            
            .hero-buttons {
                gap: 15px;
            }
            
            .section-title {
                font-size: 1.8rem;
                margin-bottom: 40px;
            }
            
            .feature-card {
                padding: 30px;
                margin-bottom: 20px;
            }
            
            .feature-icon {
                font-size: 45px;
            }
            
            .feature-title {
                font-size: 1.3rem;
            }
            
            .features-section {
                padding: 50px 15px;
            }
        }
        
        /* Móviles pequeños (hasta 575px) */
        @media (max-width: 575px) {
            .hero-content {
                padding: 30px 15px;
            }
            
            .hero-title {
                font-size: 1.6rem;
                line-height: 1.3;
            }
            
            .hero-subtitle {
                font-size: 0.9rem;
                margin-bottom: 20px;
            }
            
            .hero-icon {
                font-size: 45px;
                margin-bottom: 15px;
            }
            
            .btn-hero {
                padding: 10px 25px;
                font-size: 0.9rem;
                width: 100%;
                max-width: 250px;
            }
            
            .hero-buttons {
                gap: 10px;
                flex-direction: column;
                align-items: center;
            }
            
            .section-title {
                font-size: 1.5rem;
                margin-bottom: 30px;
            }
            
            .feature-card {
                padding: 25px;
            }
            
            .feature-icon {
                font-size: 40px;
            }
            
            .feature-title {
                font-size: 1.2rem;
            }
            
            .feature-description {
                font-size: 0.95rem;
            }
            
            .feature-btn {
                padding: 10px 25px;
                font-size: 0.9rem;
            }
        }

        /* ========== ANIMACIÓN DE ENTRADA SUAVE ========== */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <!-- Botón de Modo Oscuro -->
    <button class="dark-mode-toggle" id="darkModeToggle">
        <i class="fas fa-moon toggle-icon" id="toggleIcon"></i>
        <span id="toggleText">Modo Oscuro</span>
    </button>

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
                <div class="col-lg-4 col-md-6 col-sm-12">
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

                <div class="col-lg-4 col-md-6 col-sm-12">
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

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h3 class="feature-title">Préstamos</h3>
                        <p class="feature-description">
                            Controla préstamos y devoluciones con registro detallado de fechas. Sistema inteligente de alertas y recordatorios.
                        </p>
                        <a href="index.php?ruta=prestamos" class="btn feature-btn">
                            Ver Préstamos <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sistema de Modo Oscuro con Animaciones
        const darkModeToggle = document.getElementById('darkModeToggle');
        const toggleIcon = document.getElementById('toggleIcon');
        const toggleText = document.getElementById('toggleText');
        const body = document.body;

        // Verificar modo guardado en localStorage
        const savedMode = localStorage.getItem('darkMode');
        
        // Aplicar modo guardado al cargar la página
        if (savedMode === 'enabled') {
            body.classList.add('dark-mode');
            toggleIcon.classList.remove('fa-moon');
            toggleIcon.classList.add('fa-sun');
            toggleText.textContent = 'Modo Claro';
        }

        // Toggle entre modos
        darkModeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            
            if (body.classList.contains('dark-mode')) {
                // Activar modo oscuro
                toggleIcon.classList.remove('fa-moon');
                toggleIcon.classList.add('fa-sun');
                toggleText.textContent = 'Modo Claro';
                localStorage.setItem('darkMode', 'enabled');
                
                // Efecto de explosión de partículas
                createParticles(darkModeToggle);
            } else {
                // Activar modo claro
                toggleIcon.classList.remove('fa-sun');
                toggleIcon.classList.add('fa-moon');
                toggleText.textContent = 'Modo Oscuro';
                localStorage.setItem('darkMode', 'disabled');
                
                // Efecto de explosión de partículas
                createParticles(darkModeToggle);
            }
        });

        // Función para crear efecto de partículas al cambiar modo
        function createParticles(element) {
            const rect = element.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.style.position = 'fixed';
                particle.style.left = centerX + 'px';
                particle.style.top = centerY + 'px';
                particle.style.width = '10px';
                particle.style.height = '10px';
                particle.style.borderRadius = '50%';
                particle.style.pointerEvents = 'none';
                particle.style.zIndex = '9999';
                
                if (body.classList.contains('dark-mode')) {
                    particle.style.background = `linear-gradient(135deg, #3b82f6, #60a5fa)`;
                    particle.style.boxShadow = '0 0 10px rgba(59, 130, 246, 0.8)';
                } else {
                    particle.style.background = `linear-gradient(135deg, #667eea, #764ba2)`;
                    particle.style.boxShadow = '0 0 10px rgba(102, 126, 234, 0.8)';
                }
                
                document.body.appendChild(particle);
                
                const angle = (Math.PI * 2 * i) / 20;
                const velocity = 100 + Math.random() * 100;
                const tx = Math.cos(angle) * velocity;
                const ty = Math.sin(angle) * velocity;
                
                particle.animate([
                    {
                        transform: 'translate(0, 0) scale(1)',
                        opacity: 1
                    },
                    {
                        transform: `translate(${tx}px, ${ty}px) scale(0)`,
                        opacity: 0
                    }
                ], {
                    duration: 800,
                    easing: 'cubic-bezier(0, .9, .57, 1)',
                    fill: 'forwards'
                });
                
                setTimeout(() => particle.remove(), 800);
            }
        }
    </script>
</body>
</html>