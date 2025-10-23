<!DOCTYPE html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Préstamos - Biblioteca CIF</title>
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
        }
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s;
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
        }
        .stat-card.blue { --card-color-1: #667eea; --card-color-2: #764ba2; }
        .stat-card.green { --card-color-1: #56ab2f; --card-color-2: #a8e063; }
        .stat-card.orange { --card-color-1: #fa709a; --card-color-2: #fee140; }
        .stat-card.red { --card-color-1: #f093fb; --card-color-2: #f5576c; }
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .badge-active {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            color: white;
        }
        .badge-returned {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .badge-late {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            text-decoration: none;
            cursor: pointer;
        }
        .btn-view {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        }
        .detail-row {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }
        .detail-value {
            font-size: 1.1rem;
            color: #333;
        }
    </style>
</head>
<body>
    <?php require_once 'views/layouts/navbar.php'; ?>

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-handshake"></i> Gestión de Préstamos
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

        <?php
        $prestamo_model = new Prestamo();
        $stats = $prestamo_model->obtenerEstadisticas();
        ?>
        <div class="stats-container">
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-handshake"></i></div>
                <div class="stat-value"><?php echo $stats['total_prestamos']; ?></div>
                <div class="stat-label">Total Préstamos</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-book-open"></i></div>
                <div class="stat-value"><?php echo $stats['prestamos_activos']; ?></div>
                <div class="stat-label">Préstamos Activos</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-value"><?php echo $stats['prestamos_devueltos']; ?></div>
                <div class="stat-label">Libros Devueltos</div>
            </div>
            <div class="stat-card red">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-value"><?php echo $stats['prestamos_atrasados']; ?></div>
                <div class="stat-label">Préstamos Atrasados</div>
            </div>
        </div>

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
                        <th>Devolución Esperada</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prestamos)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px;">
                                <i class="fas fa-inbox" style="font-size: 50px; color: #ccc; display: block; margin-bottom: 15px;"></i>
                                <span style="color: #999; font-size: 18px;">No hay préstamos registrados</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($prestamos as $prestamo): ?>
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
                                <?php
                                $badge_class = 'badge-active';
                                $estado_texto = 'Activo';
                                if ($prestamo['estado'] == 'devuelto') {
                                    $badge_class = 'badge-returned';
                                    $estado_texto = 'Devuelto';
                                } elseif ($prestamo['estado'] == 'atrasado' ||
                                         (strtotime($prestamo['fecha_devolucion_esperada']) < time() && $prestamo['estado'] == 'activo')) {
                                    $badge_class = 'badge-late';
                                    $estado_texto = 'Atrasado';
                                }
                                ?>
                                <span class="badge-modern <?php echo $badge_class; ?>">
                                    <?php echo $estado_texto; ?>
                                </span>
                            </td>
                            <td>
                                <!-- BOTÓN VER DETALLES -->
                                <button type="button" class="btn-action btn-view" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detalleModal"
                                        data-prestamo='<?php echo json_encode($prestamo); ?>'
                                        title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <?php if ($prestamo['estado'] == 'activo' || $prestamo['estado'] == 'atrasado'): ?>
                                <form method="POST" action="index.php?ruta=prestamos/devolver&id=<?php echo $prestamo['id']; ?>"
                                      style="display: inline;">
                                    <button type="submit" class="btn-action btn-return"
                                            title="Registrar devolución"
                                            onclick="return confirm('¿Confirmar devolución del libro?')">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL DE DETALLES -->
    <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleModalLabel">
                        <i class="fas fa-info-circle"></i> Detalles del Préstamo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-hashtag"></i> ID del Préstamo
                                </div>
                                <div class="detail-value" id="detalle-id"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-user"></i> Usuario
                                </div>
                                <div class="detail-value" id="detalle-usuario"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-envelope"></i> Email
                                </div>
                                <div class="detail-value" id="detalle-email"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-book"></i> Libro
                                </div>
                                <div class="detail-value" id="detalle-libro"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-user-edit"></i> Autor
                                </div>
                                <div class="detail-value" id="detalle-autor"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-calendar-plus"></i> Fecha de Préstamo
                                </div>
                                <div class="detail-value" id="detalle-fecha-prestamo"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-calendar-check"></i> Devolución Esperada
                                </div>
                                <div class="detail-value" id="detalle-fecha-esperada"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-calendar-alt"></i> Devolución Real
                                </div>
                                <div class="detail-value" id="detalle-fecha-real"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-info-circle"></i> Estado
                                </div>
                                <div class="detail-value" id="detalle-estado"></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-sticky-note"></i> Notas
                                </div>
                                <div class="detail-value" id="detalle-notas"></div>
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
        // Script para el modal de detalles
        document.addEventListener('DOMContentLoaded', function() {
            const detalleModal = document.getElementById('detalleModal');
            
            detalleModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const prestamo = JSON.parse(button.getAttribute('data-prestamo'));
                
                // Llenar los datos del modal
                document.getElementById('detalle-id').textContent = '#' + prestamo.id;
                document.getElementById('detalle-usuario').textContent = prestamo.usuario_nombre;
                document.getElementById('detalle-email').textContent = prestamo.usuario_email;
                document.getElementById('detalle-libro').textContent = prestamo.libro_titulo;
                document.getElementById('detalle-autor').textContent = prestamo.libro_autor;
                
                // Formatear fechas
                document.getElementById('detalle-fecha-prestamo').textContent = 
                    new Date(prestamo.fecha_prestamo).toLocaleDateString('es-ES');
                document.getElementById('detalle-fecha-esperada').textContent = 
                    new Date(prestamo.fecha_devolucion_esperada).toLocaleDateString('es-ES');
                
                // Fecha de devolución real
                const fechaReal = prestamo.fecha_devolucion_real ? 
                    new Date(prestamo.fecha_devolucion_real).toLocaleDateString('es-ES') : 
                    'Pendiente';
                document.getElementById('detalle-fecha-real').textContent = fechaReal;
                
                // Estado con badge
                let estadoHTML = '';
                let badgeClass = '';
                
                if (prestamo.estado === 'devuelto') {
                    badgeClass = 'badge-returned';
                    estadoHTML = '<span class="badge-modern ' + badgeClass + '">Devuelto</span>';
                } else if (prestamo.estado === 'atrasado' || 
                          (new Date(prestamo.fecha_devolucion_esperada) < new Date() && prestamo.estado === 'activo')) {
                    badgeClass = 'badge-late';
                    estadoHTML = '<span class="badge-modern ' + badgeClass + '">Atrasado</span>';
                } else {
                    badgeClass = 'badge-active';
                    estadoHTML = '<span class="badge-modern ' + badgeClass + '">Activo</span>';
                }
                
                document.getElementById('detalle-estado').innerHTML = estadoHTML;
                
                // Notas
                document.getElementById('detalle-notas').textContent = 
                    prestamo.notas || 'Sin notas';
            });
        });
    </script>
</body>
</html>