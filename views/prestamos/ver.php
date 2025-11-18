<?php
$pageTitle = 'Detalles del Préstamo - Biblioteca CIF';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

// Calcular información adicional
$hoy = new DateTime();
$fecha_prestamo = new DateTime($prestamo['fecha_prestamo']);
$fecha_devolucion = new DateTime($prestamo['fecha_devolucion']);
$dias_transcurridos = $fecha_prestamo->diff($hoy)->days;
$dias_restantes = $hoy->diff($fecha_devolucion)->days;
$atrasado = $hoy > $fecha_devolucion && $prestamo['estado'] == 'activo';
?>

<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/soluciones_biblioteca_cif.css">
    <style>
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
        }
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
        }
        .section-title {
            color: var(--text-primary);
            margin-bottom: 20px;
        }
        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        label {
            color: var(--text-secondary);
        }
        p {
            color: var(--text-primary);
        }
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        .badge-primary {
            background-color: rgba(99, 102, 241, 0.2);
            color: #6366f1;
        }
        .badge-success {
            background-color: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }
        .badge-danger {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .btn-secondary {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }
    </style>
</head>
<body>

<div class="page-container fade-in">
    <div class="content-wrapper">
        <h1 class="page-title">📋 Detalles del Préstamo</h1>
        <p class="page-subtitle">Información completa del préstamo registrado</p>

        <div class="row">
            <!-- Información del Usuario -->
            <div class="card">
                <h2 class="section-title">👤 Información del Usuario</h2>
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 0.9rem;">Nombre:</label>
                    <p style="font-weight: 600; margin: 5px 0;">
                        <?php echo htmlspecialchars($prestamo['usuario_nombre']); ?>
                    </p>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 0.9rem;">Email:</label>
                    <p style="margin: 5px 0;">
                        <?php echo htmlspecialchars($prestamo['usuario_email'] ?? 'No disponible'); ?>
                    </p>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 0.9rem;">Tipo:</label>
                    <p style="margin: 5px 0;">
                        <span class="badge badge-primary">
                            <?php echo ucfirst(str_replace('_', ' ', $prestamo['usuario_tipo'] ?? 'N/A')); ?>
                        </span>
                    </p>
                </div>
            </div>

            <!-- Información del Libro -->
            <div class="card">
                <h2 class="section-title">📚 Información del Libro</h2>
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 0.9rem;">Título:</label>
                    <p style="font-weight: 600; margin: 5px 0;">
                        <?php echo htmlspecialchars($prestamo['libro_titulo']); ?>
                    </p>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 0.9rem;">Autor:</label>
                    <p style="margin: 5px 0;">
                        <?php echo htmlspecialchars($prestamo['libro_autor'] ?? 'No disponible'); ?>
                    </p>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 0.9rem;">ISBN:</label>
                    <p style="margin: 5px 0;">
                        <?php echo htmlspecialchars($prestamo['libro_isbn'] ?? 'No disponible'); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Información del Préstamo -->
        <div class="card mt-3">
            <h2 class="section-title">📅 Información del Préstamo</h2>
            
            <div class="row">
                <div>
                    <label style="font-size: 0.9rem;">Fecha de Préstamo:</label>
                    <p style="font-weight: 600; margin: 5px 0; font-size: 1.1rem;">
                        <?php echo date('d/m/Y', strtotime($prestamo['fecha_prestamo'])); ?>
                    </p>
                </div>
                <div>
                    <label style="font-size: 0.9rem;">Fecha de Devolución:</label>
                    <p style="font-weight: 600; margin: 5px 0; font-size: 1.1rem;">
                        <?php echo date('d/m/Y', strtotime($prestamo['fecha_devolucion'])); ?>
                    </p>
                </div>
                <div>
                    <label style="font-size: 0.9rem;">Estado:</label>
                    <p style="margin: 5px 0;">
                        <?php if ($prestamo['estado'] == 'activo' && !$atrasado): ?>
                            <span class="badge badge-success" style="font-size: 1rem; padding: 8px 20px;">Activo</span>
                        <?php elseif ($prestamo['estado'] == 'activo' && $atrasado): ?>
                            <span class="badge badge-danger" style="font-size: 1rem; padding: 8px 20px;">Atrasado</span>
                        <?php else: ?>
                            <span class="badge badge-secondary" style="font-size: 1rem; padding: 8px 20px;">Devuelto</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="btn-group mt-4">
            <?php if ($prestamo['estado'] == 'activo'): ?>
                <a href="index.php?ruta=prestamos&accion=devolver&id=<?php echo $prestamo['id']; ?>" 
                   class="btn btn-success btn-lg"
                   onclick="return confirm('¿Confirmar devolución de este libro?')">
                    <i class="fas fa-undo"></i> Registrar Devolución
                </a>
            <?php endif; ?>
            <a href="index.php?ruta=prestamos" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left"></i> Volver a Préstamos
            </a>
        </div>
    </div>
</div>

<script src="../../assets/js/theme_system.js"></script>
</body>
</html>