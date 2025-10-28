<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<style>
.main-container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px;
    position: relative;
    z-index: 1;
}

.page-header {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px var(--shadow);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    border: 2px solid var(--border-color);
}

[data-theme="premium"] .page-header {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: rgba(56, 189, 248, 0.2);
}

.page-header h1 {
    font-size: 2rem;
    color: var(--text-primary);
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 15px;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-secondary {
    background: var(--secondary);
    color: white;
}

.btn-secondary:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.content-card {
    background: var(--bg-card);
    border-radius: 16px;
    box-shadow: 0 4px 20px var(--shadow);
    overflow: hidden;
    border: 2px solid var(--border-color);
    padding: 40px;
}

[data-theme="premium"] .content-card {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: rgba(56, 189, 248, 0.2);
}

.detail-row {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 20px;
    padding: 20px 0;
    border-bottom: 1px solid var(--border-color);
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 700;
    color: var(--text-primary);
    font-size: 1rem;
}

.detail-value {
    color: var(--text-secondary);
    font-size: 1rem;
}

.badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
}

.badge-activo {
    background: #d1fae5;
    color: #065f46;
}

.badge-devuelto {
    background: #dbeafe;
    color: #1e40af;
}

.badge-atrasado {
    background: #fee2e2;
    color: #991b1b;
}

@media (max-width: 768px) {
    .detail-row {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}
</style>

<div class="main-container">
    <div class="page-header">
        <h1> Detalles del Préstamo</h1>
        <a href="index.php?ruta=prestamos" class="btn btn-secondary">
             Volver a Préstamos
        </a>
    </div>

    <div class="content-card">
        <?php if (isset($datos)): ?>
            <div class="detail-row">
                <div class="detail-label">ID del Préstamo:</div>
                <div class="detail-value">#<?php echo htmlspecialchars($datos['id']); ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Usuario:</div>
                <div class="detail-value">
                    <strong><?php echo htmlspecialchars($datos['usuario_nombre'] ?? 'N/A'); ?></strong>
                    <?php if (isset($datos['usuario_correo'])): ?>
                        <br><small><?php echo htmlspecialchars($datos['usuario_correo']); ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Libro:</div>
                <div class="detail-value">
                    <strong><?php echo htmlspecialchars($datos['libro_titulo'] ?? 'N/A'); ?></strong>
                    <?php if (isset($datos['libro_autor'])): ?>
                        <br><small>Autor: <?php echo htmlspecialchars($datos['libro_autor']); ?></small>
                    <?php endif; ?>
                    <?php if (isset($datos['libro_isbn'])): ?>
                        <br><small>ISBN: <?php echo htmlspecialchars($datos['libro_isbn']); ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Fecha de Préstamo:</div>
                <div class="detail-value">
                    <?php 
                    if (isset($datos['fecha_prestamo'])) {
                        echo date('d/m/Y H:i', strtotime($datos['fecha_prestamo']));
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Fecha de Devolución Estimada:</div>
                <div class="detail-value">
                    <?php 
                    $fechaDevolucion = $datos['fecha_devolucion_esperada'] ?? 
                                      $datos['fecha_devolucion'] ?? 
                                      $datos['fecha_limite'] ?? null;
                    if ($fechaDevolucion) {
                        echo date('d/m/Y', strtotime($fechaDevolucion));
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </div>
            </div>

            <?php if (isset($datos['fecha_devolucion_real'])): ?>
            <div class="detail-row">
                <div class="detail-label">Fecha de Devolución Real:</div>
                <div class="detail-value">
                    <?php echo date('d/m/Y H:i', strtotime($datos['fecha_devolucion_real'])); ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="detail-row">
                <div class="detail-label">Estado:</div>
                <div class="detail-value">
                    <?php 
                    $estado = $datos['estado'] ?? 'activo';
                    $badgeClass = 'badge-activo';
                    
                    if ($estado === 'devuelto') {
                        $badgeClass = 'badge-devuelto';
                    } elseif ($estado === 'activo' && $fechaDevolucion && strtotime($fechaDevolucion) < time()) {
                        $estado = 'atrasado';
                        $badgeClass = 'badge-atrasado';
                    }
                    ?>
                    <span class="badge <?php echo $badgeClass; ?>">
                        <?php echo ucfirst($estado); ?>
                    </span>
                    
                    <?php if ($estado === 'atrasado'): ?>
                        <br><small style="color: var(--accent); font-weight: 600;">
                            Días de retraso: <?php 
                                $dias = (time() - strtotime($fechaDevolucion)) / (60 * 60 * 24);
                                echo floor($dias);
                            ?>
                        </small>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (isset($datos['observaciones']) && !empty($datos['observaciones'])): ?>
            <div class="detail-row">
                <div class="detail-label">Observaciones:</div>
                <div class="detail-value"><?php echo nl2br(htmlspecialchars($datos['observaciones'])); ?></div>
            </div>
            <?php endif; ?>

            <?php if ($estado === 'activo' || $estado === 'atrasado'): ?>
            <div style="margin-top: 40px; text-align: center;">
                <a href="index.php?ruta=prestamos/devolver&id=<?php echo $datos['id']; ?>" 
                   class="btn btn-secondary"
                   onclick="return confirm('¿Confirmar devolución del libro?')"
                   style="background: var(--success); padding: 15px 40px; font-size: 16px;">
                     Marcar como Devuelto
                </a>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px; color: var(--text-secondary);">
                <div style="font-size: 4rem; margin-bottom: 20px; opacity: 0.5;"></div>
                <h3 style="color: var(--text-primary);">Préstamo no encontrado</h3>
                <p>El préstamo solicitado no existe o ha sido eliminado</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
