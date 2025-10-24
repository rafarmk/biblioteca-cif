<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0 100px 0;
            text-align: center;
        }
        .feature-card {
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #667eea;
        }
    </style>
</head>
<body>

<?php include 'views/layouts/navbar.php'; ?>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1 class="display-3 fw-bold mb-4">
                <i class="bi bi-book-fill"></i> Sistema de Biblioteca CIF
            </h1>
            <p class="lead mb-5">Gestión moderna y eficiente de préstamos bibliotecarios</p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="?ruta=libros" class="btn btn-light btn-lg">
                    <i class="bi bi-search"></i> Ver Catálogo
                </a>
                <a href="?ruta=dashboard" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container my-5 py-5">
        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="bi bi-book feature-icon"></i>
                    <h3>Gestión de Libros</h3>
                    <p class="text-muted">
                        Administra el catálogo completo de libros de la biblioteca con facilidad.
                    </p>
                    <a href="?ruta=libros" class="btn btn-primary">
                        Ver Libros
                    </a>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="bi bi-person-circle feature-icon"></i>
                    <h3>Control de Usuarios</h3>
                    <p class="text-muted">
                        Registra y gestiona estudiantes, docentes y personal administrativo.
                    </p>
                    <a href="?ruta=usuarios" class="btn btn-primary">
                        Ver Usuarios
                    </a>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="bi bi-arrow-left-right feature-icon"></i>
                    <h3>Préstamos</h3>
                    <p class="text-muted">
                        Controla préstamos y devoluciones con registro detallado de fechas.
                    </p>
                    <a href="?ruta=prestamos" class="btn btn-primary">
                        Ver Préstamos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <h2 class="display-4 fw-bold text-primary">1,234</h2>
                    <p class="text-muted">Libros en catálogo</p>
                </div>
                <div class="col-md-3">
                    <h2 class="display-4 fw-bold text-success">456</h2>
                    <p class="text-muted">Usuarios activos</p>
                </div>
                <div class="col-md-3">
                    <h2 class="display-4 fw-bold text-warning">89</h2>
                    <p class="text-muted">Préstamos activos</p>
                </div>
                <div class="col-md-3">
                    <h2 class="display-4 fw-bold text-info">98%</h2>
                    <p class="text-muted">Tasa de devolución</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">
                <i class="bi bi-book-fill"></i> Sistema de Biblioteca CIF © 2025
            </p>
            <small class="text-muted">Desarrollado con 💙 para la educación</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>