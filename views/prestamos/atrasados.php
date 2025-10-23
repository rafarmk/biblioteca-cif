<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos Atrasados - Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .page-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        .alert-warning-custom {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left: 5px solid #ffc107;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(255,193,7,0.3);
        }
        .actions-bar {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
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
            text-decoration: none;
        }
        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-success-modern {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            color: white;
        }
        .btn-danger-modern {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 15px;
            font-weight: 700;
            text-align: left;
            border: none;
        }
        .modern-table thead th:first-child { border-radius: 10px 0 0 10px; }
        .modern-table thead th:last-child { border-radius: 0 10px 10px 0; }
        .modern-table tbody tr {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
            border-left: 5px solid #f5576c;
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
        .badge-late {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
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
            text-decoration: none;
        }
        .btn-return {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
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
        .days-late {
            display: inline-block;
            padding: 5px 12px;
            background: #fff3cd;
            border-radius: 8px;
            color: #856404;
            font-weight: 700;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <?php require_once 'views/layouts/navbar.php'; ?>

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-exclamation-triangle"></i> Préstamos Atrasados
        </h1>
    </div>

    <div class="container">
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success alert-modern alert-dismissible fade show">
                <i class="fas fa-check-circle"></i>
                <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-modern alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($prestamos)): ?>
            <div class="alert-warning-custom">
                <h5 style="margin: 0; color: #856404;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>¡Atención!</strong> Hay <?php echo count($prestamos); ?> préstamo(s) atrasado(s) que requieren acción inmediata.
                </h5>
            </div>
        <?php endif; ?>

        <div class="actions-bar">
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="index.php?ruta=prestamos" class="btn-modern btn-primary-modern">
                    <i class="fas fa-list"></i> Todos
                </a>
                <a href="index.php?ruta=prestamos/activos" class="btn-modern btn-success-modern">
                    <i class="fas fa-book-open"></i> Activos
                </a>
                <a href="index.php?ruta=prestamos/atrasados" class="btn-modern btn-danger-modern">
                    <i class="fas fa-exclamation-triangle"></i> Atrasados
                </a>
            </div>
            <a href="index.php?ruta=prestamos/crear" class="btn-modern btn-success-modern">
                <i class="fas fa-plus"></i> Nuevo Préstamo
            </a>
        </div>

        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Libro</th>
                        <th>Fecha Préstamo</th>
                        <th>Debía Devolverse</th>
                        <th>Días de Atraso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prestamos)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; border-left: none;">
                                <i class="fas fa-check-circle" style="font-size: 50px; color: #56ab2f; display: block; margin-bottom: 15px;"></i>
                                <span style="color: #999; font-size: 18px;">¡Excelente! No hay préstamos atrasados.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($prestamos as $prestamo): ?>
                        <?php
                        $dias_atraso = isset($prestamo['dias_atraso']) ? $prestamo['dias_atraso'] : 0;
                        ?>
                        <tr>
                            <td><strong>#<?php echo $prestamo['id']; ?></strong></td>
                            <td>
                                <strong><?php echo htmlspecialchars($prestamo['usuario_nombre']); ?></strong><br>
                                <small style="color: #999;"><?php echo htmlspecialchars($prestamo['usuario_email']); ?></small>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($prestamo['libro_titulo']); ?></strong><br>
                                <small style="color: #999;"><?php echo htmlspecialchars($prestamo['libro_autor']); ?></small>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_prestamo'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_devolucion_esperada'])); ?></td>
                            <td>
                                <span class="badge-modern badge-late">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <?php echo abs($dias_atraso); ?> día(s)
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="index.php?ruta=prestamos/devolver&id=<?php echo $prestamo['id']; ?>" 
                                      style="display: inline;">
                                    <button type="submit" class="btn-action btn-return" 
                                            title="Registrar devolución URGENTE"
                                            onclick="return confirm('¿Confirmar devolución del libro: <?php echo htmlspecialchars($prestamo['libro_titulo']); ?>?')">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
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