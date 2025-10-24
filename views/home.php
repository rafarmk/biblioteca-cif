<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 50px 0;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .welcome-text {
            color: white;
            text-align: center;
            position: relative;
            z-index: 10;
        }
        .welcome-text h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .welcome-text p {
            font-size: 1.2rem;
            opacity: 0.95;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .stat-card-large {
            background: white;
            border-radius: 25px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .stat-card-large::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--card-color-1), var(--card-color-2));
        }
        .stat-card-large::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            opacity: 0.1;
            border-radius: 50%;
            transition: all 0.4s;
        }
        .stat-card-large:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
        }
        .stat-card-large:hover::after {
            bottom: -30px;
            right: -30px;
            width: 180px;
            height: 180px;
        }
        .stat-card-large.blue { --card-color-1: #667eea; --card-color-2: #764ba2; }
        .stat-card-large.green { --card-color-1: #56ab2f; --card-color-2: #a8e063; }
        .stat-card-large.orange { --card-color-1: #fa709a; --card-color-2: #fee140; }
        .stat-card-large.red { --card-color-1: #f093fb; --card-color-2: #f5576c; }
        .stat-card-large.purple { --card-color-1: #667eea; --card-color-2: #764ba2; }
        
        .stat-icon-large {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            color: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .stat-number {
            font-size: 3.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 10px;
        }
        .stat-text {
            color: #666;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .stat-btn {
            padding: 12px 25px;
            border-radius: 15px;
            border: 2px solid;
            border-color: var(--card-color-1);
            background: white;
            color: var(--card-color-1);
            font-weight: 700;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .stat-btn:hover {
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            color: white;
            transform: scale(1.05);
            border-color: transparent;
        }
        .alert-danger-custom {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);
            border-left: 5px solid #f5576c;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(245,87,108,0.3);
        }
        .recent-section {
            background: white;
            border-radius: 25px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .section-title i {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        .recent-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }
        .recent-table thead th {
            background: #f8f9fa;
            padding: 15px;
            font-weight: 700;
            color: #666;
            border: none;
            text-align: left;
        }
        .recent-table tbody tr {
            background: #fafbfc;
            transition: all 0.3s;
        }
        .recent-table tbody tr:hover {
            background: #f0f3f7;
            transform: translateX(5px);
        }
        .recent-table tbody td {
            padding: 18px 15px;
            border-top: 1px solid #e9ecef;
            border-bottom: 1px solid #e9ecef;
        }
        .recent-table tbody td:first-child {
            border-left: 1px solid #e9ecef;
            border-radius: 10px 0 0 10px;
        }
        .recent-table tbody td:last-child {
            border-right: 1px solid #e9ecef;
            border-radius: 0 10px 10px 0;
        }
        .badge-mini {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
        }
        .badge-green { background: linear-gradient(135deg, #56ab2f, #a8e063); color: white; }
        .badge-red { background: linear-gradient(135deg, #f093fb, #f5576c); color: white; }
        .badge-orange { background: linear-gradient(135deg, #fa709a, #fee140); color: white; }
    </style>
</head>
<body>
    <?php require_once 'views/layouts/navbar.php'; ?>

    <div class="dashboard-header">
        <div class="welcome-text">
            <h1><i class="fas fa-chart-line"></i> Panel de Control</h1>
            <p>Bienvenido de vuelta, <?php echo htmlspecialchars($_SESSION['admin_nombre']); ?></p>
        </div>
    </div>

    <div class="container">
        <!-- Alerta de Préstamos Atrasados -->
        <?php if (!empty($prestamos_atrasados)): ?>
            <div class="alert-danger-custom">
                <h5 style="margin: 0; color: #c62828;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>¡Alerta!</strong> Hay <?php echo count($prestamos_atrasados); ?> préstamo(s) atrasado(s).
                    <a href="index.php?ruta=prestamos/atrasados" style="color: #c62828; text-decoration: underline; margin-left: 10px;">Ver detalles</a>
                </h5>
            </div>
        <?php endif; ?>

        <!-- Estadísticas Principales -->
        <div class="stats-grid">
            <div class="stat-card-large blue">
                <div class="stat-icon-large">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-number"><?php echo count($libros); ?></div>
                <div class="stat-text">Total de Libros</div>
                <a href="index.php?ruta=libros" class="stat-btn">
                    <i class="fas fa-arrow-right"></i> Ver Catálogo
                </a>
            </div>

            <div class="stat-card-large green">
                <div class="stat-icon-large">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo count($usuarios); ?></div>
                <div class="stat-text">Usuarios Registrados</div>
                <a href="index.php?ruta=usuarios" class="stat-btn">
                    <i class="fas fa-arrow-right"></i> Ver Usuarios
                </a>
            </div>

            <div class="stat-card-large orange">
                <div class="stat-icon-large">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="stat-number"><?php echo $stats_prestamos['prestamos_activos']; ?></div>
                <div class="stat-text">Préstamos Activos</div>
                <a href="index.php?ruta=prestamos/activos" class="stat-btn">
                    <i class="fas fa-arrow-right"></i> Ver Activos
                </a>
            </div>

            <div class="stat-card-large red">
                <div class="stat-icon-large">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-number"><?php echo $stats_prestamos['prestamos_atrasados']; ?></div>
                <div class="stat-text">Préstamos Atrasados</div>
                <a href="index.php?ruta=prestamos/atrasados" class="stat-btn">
                    <i class="fas fa-arrow-right"></i> Ver Atrasados
                </a>
            </div>

            <div class="stat-card-large purple">
                <div class="stat-icon-large">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stat-number"><?php echo $stats_prestamos['total_prestamos']; ?></div>
                <div class="stat-text">Total Préstamos</div>
                <a href="index.php?ruta=prestamos" class="stat-btn">
                    <i class="fas fa-arrow-right"></i> Ver Todos
                </a>
            </div>

            <div class="stat-card-large green">
                <div class="stat-icon-large">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?php echo $stats_prestamos['prestamos_devueltos']; ?></div>
                <div class="stat-text">Libros Devueltos</div>
                <a href="index.php?ruta=prestamos/crear" class="stat-btn">
                    <i class="fas fa-plus"></i> Nuevo Préstamo
                </a>
            </div>
        </div>

        <!-- Préstamos Activos Recientes -->
        <?php if (!empty($prestamos_activos)): ?>
            <div class="recent-section">
                <div class="section-title">
                    <i><i class="fas fa-clock"></i></i>
                    <span>Préstamos Activos Recientes</span>
                </div>
                <table class="recent-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Libro</th>
                            <th>Fecha Préstamo</th>
                            <th>Devolución Esperada</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count = 0;
                        foreach ($prestamos_activos as $prestamo): 
                            if ($count >= 5) break; // Mostrar solo los primeros 5
                            $count++;
                            $fecha_esperada = strtotime($prestamo['fecha_devolucion_esperada']);
                            $hoy = strtotime(date('Y-m-d'));
                            $dias_diferencia = ($fecha_esperada - $hoy) / 86400;
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($prestamo['usuario_nombre']); ?></strong></td>
                            <td><?php echo htmlspecialchars($prestamo['libro_titulo']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_prestamo'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_devolucion_esperada'])); ?></td>
                            <td>
                                <?php if ($dias_diferencia < 0): ?>
                                    <span class="badge-mini badge-red">
                                        <i class="fas fa-exclamation-triangle"></i> Atrasado
                                    </span>
                                <?php elseif ($dias_diferencia <= 3): ?>
                                    <span class="badge-mini badge-orange">
                                        <i class="fas fa-clock"></i> Próximo a vencer
                                    </span>
                                <?php else: ?>
                                    <span class="badge-mini badge-green">
                                        <i class="fas fa-check"></i> Activo
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="index.php?ruta=prestamos/activos" class="stat-btn" style="border-color: #667eea; color: #667eea;">
                        Ver Todos los Préstamos Activos <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="recent-section">
                <div class="section-title">
                    <i><i class="fas fa-clock"></i></i>
                    <span>Préstamos Activos</span>
                </div>
                <div style="text-align: center; padding: 40px; color: #999;">
                    <i class="fas fa-inbox" style="font-size: 50px; display: block; margin-bottom: 15px;"></i>
                    <p style="font-size: 18px;">No hay préstamos activos en este momento.</p>
                    <a href="index.php?ruta=prestamos/crear" class="stat-btn" style="border-color: #56ab2f; color: #56ab2f; margin-top: 15px;">
                        <i class="fas fa-plus"></i> Crear Nuevo Préstamo
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>