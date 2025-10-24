<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Libros - Biblioteca CIF</title>
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
            --bg-primary: #f5f7fa;
            --bg-secondary: #ffffff;
            --text-primary: #1e3c72;
            --text-secondary: #666;
            --card-bg: #ffffff;
            --card-shadow: rgba(0,0,0,0.1);
            --gradient-1: #667eea;
            --gradient-2: #764ba2;
            --gradient-3: #7e22ce;
        }

        /* Modo Oscuro Elegante */
        body.dark-mode {
            --bg-primary: #0a0f1e;
            --bg-secondary: #1f2937;
            --text-primary: #e5e7eb;
            --text-secondary: #9ca3af;
            --card-bg: #1f2937;
            --card-shadow: rgba(59, 130, 246, 0.15);
            --gradient-1: #1e3a8a;
            --gradient-2: #3b82f6;
            --gradient-3: #60a5fa;
        }

        body {
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.5s ease;
        }

        /* ========== TOGGLE MODO OSCURO ========== */
        .dark-mode-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background: linear-gradient(135deg, var(--gradient-1) 0%, var(--gradient-2) 100%);
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
        }

        body.dark-mode .dark-mode-toggle {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);
            box-shadow: 
                0 10px 40px rgba(37, 99, 235, 0.4),
                0 0 30px rgba(30, 58, 138, 0.3);
            animation: silverPulse 2s ease-in-out infinite;
        }

        @keyframes silverPulse {
            0%, 100% { 
                box-shadow: 
                    0 10px 40px rgba(37, 99, 235, 0.4),
                    0 0 30px rgba(30, 58, 138, 0.3);
            }
            50% { 
                box-shadow: 
                    0 10px 50px rgba(37, 99, 235, 0.6),
                    0 0 40px rgba(30, 58, 138, 0.5);
            }
        }

        .dark-mode-toggle:hover {
            transform: scale(1.1) translateY(-3px);
        }

        .toggle-icon {
            font-size: 20px;
            transition: transform 0.4s ease;
        }

        .dark-mode-toggle:hover .toggle-icon {
            transform: rotate(20deg) scale(1.2);
        }

        body.dark-mode .dark-mode-toggle:hover .toggle-icon {
            transform: rotate(-20deg) scale(1.2);
        }

        .page-header {
            background: linear-gradient(135deg, var(--gradient-1) 0%, var(--gradient-2) 100%);
            padding: 40px 0;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.5s ease;
        }

        body.dark-mode .page-header {
            box-shadow: 
                0 10px 30px rgba(37, 99, 235, 0.3),
                inset 0 0 100px rgba(59, 130, 246, 0.1);
        }

        .page-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        body.dark-mode .page-title {
            text-shadow: 
                0 0 20px rgba(59, 130, 246, 0.5),
                2px 2px 4px rgba(0,0,0,0.3);
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            animation: fadeInUp 0.8s ease-out;
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

        .stat-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px var(--card-shadow);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        body.dark-mode .stat-card {
            box-shadow: 
                0 10px 40px rgba(37, 99, 235, 0.2),
                0 0 30px rgba(30, 58, 138, 0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--card-color-1), var(--card-color-2));
        }

        .stat-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border-color: var(--card-color-1);
        }

        body.dark-mode .stat-card:hover {
            box-shadow: 
                0 20px 50px rgba(37, 99, 235, 0.3),
                0 0 40px rgba(30, 58, 138, 0.25);
        }

        .stat-card.blue {
            --card-color-1: #667eea;
            --card-color-2: #764ba2;
        }

        body.dark-mode .stat-card.blue {
            --card-color-1: #3b82f6;
            --card-color-2: #60a5fa;
        }

        .stat-card.green {
            --card-color-1: #56ab2f;
            --card-color-2: #a8e063;
        }

        .stat-card.pink {
            --card-color-1: #f093fb;
            --card-color-2: #f5576c;
        }

        body.dark-mode .stat-card.pink {
            --card-color-1: #6366f1;
            --card-color-2: #8b5cf6;
        }

        .stat-card.orange {
            --card-color-1: #fa709a;
            --card-color-2: #fee140;
        }

        body.dark-mode .stat-card.orange {
            --card-color-1: #2563eb;
            --card-color-2: #3b82f6;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            color: white;
            animation: iconFloat 4s ease-in-out infinite;
        }

        @keyframes iconFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        body.dark-mode .stat-icon {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.95rem;
            font-weight: 600;
        }

        .search-section {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px var(--card-shadow);
            transition: all 0.5s ease;
        }

        body.dark-mode .search-section {
            box-shadow: 
                0 10px 40px rgba(37, 99, 235, 0.2),
                0 0 30px rgba(30, 58, 138, 0.1);
        }

        .search-bar {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 200px;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            font-size: 16px;
            transition: all 0.3s;
            background: var(--bg-primary);
            color: var(--text-primary);
        }

        body.dark-mode .search-input {
            border-color: rgba(59, 130, 246, 0.3);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--gradient-2);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        body.dark-mode .search-input:focus {
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }

        .btn-modern {
            padding: 15px 30px;
            border-radius: 15px;
            border: none;
            font-weight: 700;
            transition: all 0.4s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--gradient-1) 0%, var(--gradient-2) 100%);
            color: white;
        }

        .btn-primary-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        body.dark-mode .btn-primary-modern:hover {
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.6);
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            color: white;
        }

        .btn-success-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(86, 171, 47, 0.4);
            color: white;
        }

        .table-container {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px var(--card-shadow);
            overflow-x: auto;
            transition: all 0.5s ease;
        }

        body.dark-mode .table-container {
            box-shadow: 
                0 10px 40px rgba(37, 99, 235, 0.2),
                0 0 30px rgba(30, 58, 138, 0.1);
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            min-width: 800px;
        }

        .modern-table thead th {
            background: linear-gradient(135deg, var(--gradient-1) 0%, var(--gradient-2) 100%);
            color: white;
            padding: 15px;
            font-weight: 700;
            text-align: left;
            border: none;
        }

        .modern-table thead th:first-child {
            border-radius: 10px 0 0 10px;
        }

        .modern-table thead th:last-child {
            border-radius: 0 10px 10px 0;
        }

        .modern-table tbody tr {
            background: var(--card-bg);
            box-shadow: 0 2px 10px var(--card-shadow);
            transition: all 0.4s;
        }

        body.dark-mode .modern-table tbody tr {
            box-shadow: 0 2px 15px rgba(37, 99, 235, 0.15);
        }

        .modern-table tbody tr:hover {
            transform: scale(1.01);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        body.dark-mode .modern-table tbody tr:hover {
            box-shadow: 0 5px 25px rgba(59, 130, 246, 0.3);
        }

        .modern-table tbody td {
            padding: 18px 15px;
            border-top: 1px solid rgba(0,0,0,0.05);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            color: var(--text-primary);
        }

        body.dark-mode .modern-table tbody td {
            border-top: 1px solid rgba(59, 130, 246, 0.1);
            border-bottom: 1px solid rgba(59, 130, 246, 0.1);
        }

        .modern-table tbody td:first-child {
            border-left: 1px solid rgba(0,0,0,0.05);
            border-radius: 10px 0 0 10px;
        }

        body.dark-mode .modern-table tbody td:first-child {
            border-left: 1px solid rgba(59, 130, 246, 0.1);
        }

        .modern-table tbody td:last-child {
            border-right: 1px solid rgba(0,0,0,0.05);
            border-radius: 0 10px 10px 0;
        }

        body.dark-mode .modern-table tbody td:last-child {
            border-right: 1px solid rgba(59, 130, 246, 0.1);
        }

        .badge-modern {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
        }

        .badge-category {
            background: linear-gradient(135deg, var(--gradient-1) 0%, var(--gradient-2) 100%);
            color: white;
        }

        .badge-stock {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            color: white;
        }

        .btn-action {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: none;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s;
            margin: 0 3px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-view {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        body.dark-mode .btn-view {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        }

        .btn-edit {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        body.dark-mode .btn-edit {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }

        .btn-delete {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .btn-action:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        body.dark-mode .btn-action:hover {
            box-shadow: 0 5px 20px rgba(59, 130, 246, 0.5);
        }

        .alert-modern {
            border-radius: 15px;
            border: none;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            background: var(--card-bg);
        }

        body.dark-mode .modal-content {
            box-shadow: 
                0 20px 60px rgba(37, 99, 235, 0.4),
                0 0 40px rgba(30, 58, 138, 0.3);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--gradient-1) 0%, var(--gradient-2) 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            border: none;
            padding: 25px;
        }

        .modal-title {
            font-weight: 800;
            font-size: 1.5rem;
        }

        .modal-body {
            padding: 30px;
            background: var(--card-bg);
        }

        .detail-row {
            padding: 15px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        body.dark-mode .detail-row {
            border-bottom: 1px solid rgba(59, 130, 246, 0.1);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 700;
            color: var(--gradient-2);
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .detail-value {
            font-size: 1.1rem;
            color: var(--text-primary);
        }

        .book-description {
            background: var(--bg-primary);
            padding: 15px;
            border-radius: 10px;
            margin-top: 10px;
            line-height: 1.6;
            color: var(--text-secondary);
        }

        /* ========== RESPONSIVE DESIGN ========== */
        
        /* Tablets (768px - 991px) */
        @media (max-width: 991px) {
            .page-title {
                font-size: 2rem;
            }
            
            .stat-value {
                font-size: 2rem;
            }
            
            .stats-container {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }
        }
        
        /* Móviles (576px - 767px) */
        @media (max-width: 767px) {
            .dark-mode-toggle {
                padding: 10px 20px;
                font-size: 14px;
                top: 15px;
                right: 15px;
            }
            
            .page-header {
                padding: 30px 0;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .search-bar {
                flex-direction: column;
            }
            
            .search-input {
                width: 100%;
            }
            
            .btn-modern {
                width: 100%;
                justify-content: center;
                padding: 12px 20px;
            }
            
            .table-container {
                padding: 15px;
            }
            
            .stats-container {
                gap: 15px;
            }
            
            .stat-card {
                padding: 20px;
            }
            
            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }
            
            .stat-value {
                font-size: 1.8rem;
            }
        }
        
        /* Móviles pequeños (hasta 575px) */
        @media (max-width: 575px) {
            .dark-mode-toggle {
                padding: 8px 15px;
                font-size: 12px;
            }
            
            .dark-mode-toggle span {
                display: none;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-icon {
                width: 45px;
                height: 45px;
                font-size: 20px;
            }
            
            .stat-value {
                font-size: 1.5rem;
            }
            
            .stat-label {
                font-size: 0.85rem;
            }
            
            .modern-table {
                min-width: 600px;
            }
            
            .btn-action {
                width: 35px;
                height: 35px;
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

    <?php require_once 'views/layouts/navbar.php'; ?>

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-book"></i> Catálogo de Libros
        </h1>
    </div>

    <div class="container">
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-modern alert-dismissible fade show">
                <i class="fas fa-check-circle"></i>
                <?php
                $mensajes = [
                    'creado' => '¡Libro creado exitosamente!',
                    'actualizado' => '¡Libro actualizado exitosamente!',
                    'eliminado' => '¡Libro eliminado exitosamente!'
                ];
                echo $mensajes[$_GET['msg']] ?? 'Operación exitosa';
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Tarjetas de Estadísticas -->
        <div class="stats-container">
            <div class="stat-card blue">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-value"><?php echo count($libros); ?></div>
                <div class="stat-label">Total de Libros</div>
            </div>

            <div class="stat-card green">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">
                    <?php
                    $disponibles = array_sum(array_column($libros, 'cantidad_disponible'));
                    echo $disponibles;
                    ?>
                </div>
                <div class="stat-label">Copias Disponibles</div>
            </div>

            <div class="stat-card pink">
                <div class="stat-icon">
                    <i class="fas fa-bookmark"></i>
                </div>
                <div class="stat-value">
                    <?php
                    $categorias = array_unique(array_column($libros, 'categoria'));
                    echo count($categorias);
                    ?>
                </div>
                <div class="stat-label">Categorías</div>
            </div>

            <div class="stat-card orange">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">Préstamos Activos</div>
            </div>
        </div>

        <!-- Búsqueda -->
        <div class="search-section">
            <form method="GET" class="search-bar">
                <input type="hidden" name="ruta" value="libros">
                <input type="text" name="buscar" class="search-input"
                       placeholder="🔍 Buscar por título, autor o ISBN..."
                       value="<?php echo $_GET['buscar'] ?? ''; ?>">
                <button type="submit" class="btn-modern btn-primary-modern">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
                <a href="index.php?ruta=libros&accion=crear" class="btn-modern btn-success-modern">
                    <i class="fas fa-plus"></i>
                    Nuevo Libro
                </a>
            </form>
        </div>

        <!-- Tabla de Libros -->
        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Categoría</th>
                        <th>Disponibles</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($libros)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px;">
                                <i class="fas fa-inbox" style="font-size: 50px; color: #ccc; display: block; margin-bottom: 15px;"></i>
                                <span style="color: #999; font-size: 18px;">No hay libros registrados</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($libros as $libro): ?>
                        <tr>
                            <td><strong>#<?php echo $libro['id']; ?></strong></td>
                            <td><strong><?php echo htmlspecialchars($libro['titulo']); ?></strong></td>
                            <td><?php echo htmlspecialchars($libro['autor']); ?></td>
                            <td><code><?php echo htmlspecialchars($libro['isbn']); ?></code></td>
                            <td>
                                <span class="badge-modern badge-category">
                                    <?php echo htmlspecialchars($libro['categoria']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-modern badge-stock">
                                    <?php echo $libro['cantidad_disponible']; ?>
                                </span>
                            </td>
                            <td>
                                <!-- BOTÓN VER DETALLES -->
                                <button type="button" 
                                        class="btn-action btn-view" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detalleLibroModal"
                                        data-libro='<?php echo json_encode($libro); ?>'
                                        title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <a href="index.php?ruta=libros&accion=editar&id=<?php echo $libro['id']; ?>"
                                   class="btn-action btn-edit" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?ruta=libros&accion=eliminar&id=<?php echo $libro['id']; ?>"
                                   class="btn-action btn-delete"
                                   title="Eliminar"
                                   onclick="return confirm('¿Estás seguro de eliminar este libro?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL DE DETALLES DEL LIBRO -->
    <div class="modal fade" id="detalleLibroModal" tabindex="-1" aria-labelledby="detalleLibroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleLibroModalLabel">
                        <i class="fas fa-book-open"></i> Detalles del Libro
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-hashtag"></i> ID
                                </div>
                                <div class="detail-value" id="detalle-libro-id"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-book"></i> Título
                                </div>
                                <div class="detail-value" id="detalle-libro-titulo"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-user-edit"></i> Autor
                                </div>
                                <div class="detail-value" id="detalle-libro-autor"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-barcode"></i> ISBN
                                </div>
                                <div class="detail-value" id="detalle-libro-isbn"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-building"></i> Editorial
                                </div>
                                <div class="detail-value" id="detalle-libro-editorial"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-calendar"></i> Año de Publicación
                                </div>
                                <div class="detail-value" id="detalle-libro-anio"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-bookmark"></i> Categoría
                                </div>
                                <div class="detail-value" id="detalle-libro-categoria"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-boxes"></i> Cantidad Total
                                </div>
                                <div class="detail-value" id="detalle-libro-cantidad"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-check-circle"></i> Cantidad Disponible
                                </div>
                                <div class="detail-value" id="detalle-libro-disponible"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-book-reader"></i> En Préstamo
                                </div>
                                <div class="detail-value" id="detalle-libro-prestamo"></div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-align-left"></i> Descripción
                                </div>
                                <div class="book-description" id="detalle-libro-descripcion"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sistema de Modo Oscuro
        const darkModeToggle = document.getElementById('darkModeToggle');
        const toggleIcon = document.getElementById('toggleIcon');
        const toggleText = document.getElementById('toggleText');
        const body = document.body;

        // Verificar modo guardado
        const savedMode = localStorage.getItem('darkMode');
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
                toggleIcon.classList.remove('fa-moon');
                toggleIcon.classList.add('fa-sun');
                toggleText.textContent = 'Modo Claro';
                localStorage.setItem('darkMode', 'enabled');
            } else {
                toggleIcon.classList.remove('fa-sun');
                toggleIcon.classList.add('fa-moon');
                toggleText.textContent = 'Modo Oscuro';
                localStorage.setItem('darkMode', 'disabled');
            }
        });

        // Modal de detalles del libro
        document.addEventListener('DOMContentLoaded', function() {
            const detalleModal = document.getElementById('detalleLibroModal');
            
            detalleModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const libro = JSON.parse(button.getAttribute('data-libro'));
                
                document.getElementById('detalle-libro-id').textContent = '#' + libro.id;
                document.getElementById('detalle-libro-titulo').textContent = libro.titulo;
                document.getElementById('detalle-libro-autor').textContent = libro.autor;
                document.getElementById('detalle-libro-isbn').textContent = libro.isbn;
                document.getElementById('detalle-libro-editorial').textContent = libro.editorial || 'No especificada';
                document.getElementById('detalle-libro-anio').textContent = libro.anio_publicacion || 'No especificado';
                document.getElementById('detalle-libro-categoria').textContent = libro.categoria;
                document.getElementById('detalle-libro-cantidad').textContent = libro.cantidad_total;
                document.getElementById('detalle-libro-disponible').textContent = libro.cantidad_disponible;
                
                const enPrestamo = libro.cantidad_total - libro.cantidad_disponible;
                document.getElementById('detalle-libro-prestamo').textContent = enPrestamo;
                
                const descripcion = libro.descripcion || 'Sin descripción disponible';
                document.getElementById('detalle-libro-descripcion').textContent = descripcion;
            });
        });
    </script>
</body>
</html>