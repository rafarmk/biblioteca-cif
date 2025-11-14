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

<div class="page-container fade-in">
    <div class="content-wrapper">
        <h1 class="page-title">📋 Detalles del Préstamo</h1>
        <p class="page-subtitle">Información completa del préstamo registrado</p>

        <div class="row">
            <!-- Información del Usuario -->
            <div class="col-2">
                <div class="card">
                    <h2 class="section-title">👤 Información del Usuario</h2>
                    <div style="margin-bottom: 15px;">
                        <label style="color: var(--text-light); font-size: 0.9rem;">Nombre:</label>
                        <p style="color: var(--text-white); font-weight: 600; margin: 5px 0;">
                            <?php echo htmlspecialchars($prestamo['usuario_nombre']); ?>
                        </p>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="color: var(--text-light); font-size: 0.9rem;">Email:</label>
                        <p style="color: var(--text-white); margin: 5px 0;">
                            <?php echo htmlspecialchars($prestamo['usuario_email'] ?? 'No disponible'); ?>
                        </p>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="color: var(--text-light); font-size: 0.9rem;">Tipo:</label>
                        <p style="margin: 5px 0;">
                            <span class="badge badge-primary">
                                <?php echo ucfirst(str_replace('_', ' ', $prestamo['usuario_tipo'] ?? 'N/A')); ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información del Libro -->
            <div class="col-2">
                <div class="card">
                    <h2 class="section-title">📚 Información del Libro</h2>
                    <div style="margin-bottom: 15px;">
                        <label style="color: var(--text-light); font-size: 0.9rem;">Título:</label>
                        <p style="color: var(--text-white); font-weight: 600; margin: 5px 0;">
                            <?php echo htmlspecialchars($prestamo['libro_titulo']); ?>
                        </p>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="color: var(--text-light); font-size: 0.9rem;">Autor:</label>
                        <p style="color: var(--text-white); margin: 5px 0;">
                            <?php echo htmlspecialchars($prestamo['libro_autor'] ?? 'No disponible'); ?>
                        </p>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="color: var(--text-light); font-size: 0.9rem;">ISBN:</label>
                        <p style="color: var(--text-white); margin: 5px 0;">
                            <?php echo htmlspecialchars($prestamo['libro_isbn'] ?? 'No disponible'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Préstamo -->
        <div class="card mt-3">
            <h2 class="section-title">📅 Información del Préstamo</h2>
            
            <div class="row">
                <div class="col-3">
                    <div style="margin-bottom: 20px;">
                        <label style="color: var(--text-light); font-size: 0.9rem;">Fecha de Préstamo:</label>
                        <p style="color: var(--text-white); font-weight: 600; margin: 5px 0; font-size: 1.1rem;">
                            <?php echo date('d/m/Y', strtotime($prestamo['fecha_prestamo'])); ?>
                        </p>
                    </div>
                </div>
                <div class="col-3">
                    <div style="margin-bottom: 20px;">
                        <label style="color: var(--text-light); font-size: 0.9rem;">Fecha de Devolución:</label>
                        <p style="color: var(--text-white); font-weight: 600; margin: 5px 0; font-size: 1.1rem;">
                            <?php echo date('d/m/Y', strtotime($prestamo['fecha_devolucion'])); ?>
                        </p>
                    </div>
                </div>
                <div class="col-3">
                    <div style="margin-bottom: 20px;">
                        <label style="color: var(--text-light); font-size: 0.9rem;">Estado:</label>
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

            <?php if ($prestamo['estado'] == 'activo'): ?>
                <div class="row">
                    <div class="col-2">
                        <div style="margin-bottom: 20px;">
                            <label style="color: var(--text-light); font-size: 0.9rem;">Días Transcurridos:</label>
                            <p style="color: var(--primary-color); font-weight: 700; margin: 5px 0; font-size: 1.5rem;">
                                <?php echo $dias_transcurridos; ?> días
                            </p>
                        </div>
                    </div>
                    <div class="col-2">
                        <div style="margin-bottom: 20px;">
                            <label style="color: var(--text-light); font-size: 0.9rem;">
                                <?php echo $atrasado ? 'Días de Atraso:' : 'Días Restantes:'; ?>
                            </label>
                            <p style="color: <?php echo $atrasado ? 'var(--danger-color)' : 'var(--success-color)'; ?>; font-weight: 700; margin: 5px 0; font-size: 1.5rem;">
                                <?php echo $dias_restantes; ?> días
                            </p>
                        </div>
                    </div>
                </div>

                <?php if ($atrasado): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>¡Préstamo Atrasado!</strong> Este libro debió ser devuelto hace <?php echo $dias_restantes; ?> días.
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div style="margin-bottom: 20px;">
                    <label style="color: var(--text-light); font-size: 0.9rem;">Fecha de Devolución Real:</label>
                    <p style="color: var(--text-white); font-weight: 600; margin: 5px 0; font-size: 1.1rem;">
                        <?php echo isset($prestamo['fecha_devolucion_real']) ? date('d/m/Y', strtotime($prestamo['fecha_devolucion_real'])) : 'No registrada'; ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if (!empty($prestamo['observaciones'])): ?>
                <div style="margin-top: 20px;">
                    <label style="color: var(--text-light); font-size: 0.9rem;">Observaciones:</label>
                    <p style="color: var(--text-white); margin: 5px 0; padding: 15px; background: rgba(26, 31, 58, 0.5); border-radius: 8px;">
                        <?php echo htmlspecialchars($prestamo['observaciones']); ?>
                    </p>
                </div>
            <?php endif; ?>
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

</body>
</html>