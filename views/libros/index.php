<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Libros - Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 0;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .page-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            animation: fadeInUp 0.6s ease-out;
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
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
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
        
        .stat-card.blue {
            --card-color-1: #667eea;
            --card-color-2: #764ba2;
        }
        
        .stat-card.green {
            --card-color-1: #56ab2f;
            --card-color-2: #a8e063;
        }
        
        .stat-card.pink {
            --card-color-1: #f093fb;
            --card-color-2: #f5576c;
        }
        
        .stat-card.orange {
            --card-color-1: #fa709a;
            --card-color-2: #fee140;
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
            color: #666;
            font-size: 0.95rem;
            font-weight: 600;
        }
        
        .search-section {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        .search-bar {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .search-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .btn-modern {
            padding: 15px 30px;
            border-radius: 15px;
            border: none;
            font-weight: 700;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success-modern {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            color: white;
        }
        
        .btn-success-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(86, 171, 47, 0.4);
        }
        
        .table-container {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }
        
        .modern-table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .modern-table tbody tr:hover {
            transform: scale(1.01);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .modern-table tbody td {
            padding: 18px 15px;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .modern-table tbody td:first-child {
            border-left: 1px solid #f0f0f0;
            border-radius: 10px 0 0 10px;
        }
        
        .modern-table tbody td:last-child {
            border-right: 1px solid #f0f0f0;
            border-radius: 0 10px 10px 0;
        }
        
        .badge-modern {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
        }
        
        .badge-category {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            transition: all 0.3s;
            margin: 0 3px;
        }
        
        .btn-view {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .btn-action:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .alert-modern {
            border-radius: 15px;
            border: none;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
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
                                <button class="btn-action btn-view" title="Ver detalles">
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>