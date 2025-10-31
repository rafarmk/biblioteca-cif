<?php require_once 'views/layouts/navbar.php'; ?>
<style>
.main-container {
    max-width: 1400px;
    margin: 40px auto;
    padding: 0 20px;
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
    border: 2px solid var(--border-color);
}
.page-title {
    display: flex;
    align-items: center;
    gap: 15px;
    color: var(--text-primary);
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}
.page-title i {
    color: #ef4444;
}
.alert-danger {
    background: rgba(239, 68, 68, 0.1);
    border-left: 4px solid #ef4444;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    color: var(--text-primary);
}
.filters {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}
.btn-modern {
    padding: 12px 24px;
    border-radius: 12px;
    border: 2px solid var(--border-color);
    background: var(--bg-secondary);
    color: var(--text-primary);
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}
.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px var(--shadow);
}
.btn-primary-modern {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-color: transparent;
}
.btn-success-modern {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-color: transparent;
}
.btn-danger-modern {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border-color: transparent;
}
.table-container {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 20px var(--shadow);
    border: 2px solid var(--border-color);
    overflow-x: auto;
}
table {
    width: 100%;
    border-collapse: collapse;
}
thead {
    background: var(--bg-secondary);
}
th {
    padding: 15px;
    text-align: left;
    color: var(--text-primary);
    font-weight: 600;
    border-bottom: 2px solid var(--border-color);
}
td {
    padding: 15px;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border-color);
}
tbody tr:hover {
    background: var(--bg-secondary);
}
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}
.badge-danger {
    background: #ef4444;
    color: white;
}
.btn-sm {
    padding: 8px 16px;
    font-size: 0.9rem;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
}
.btn-success {
    background: #10b981;
    color: white;
}
.btn-primary {
    background: var(--primary);
    color: white;
}
.btn-sm:hover {
    transform: translateY(-2px);
}
</style>

<div class="main-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-exclamation-triangle"></i> Préstamos Atrasados
        </h1>
    </div>

    <?php if (!empty($prestamos)): ?>
        <div class="alert-danger">
            <strong>¡Atención!</strong> Hay <?php echo count($prestamos); ?> préstamo(s) atrasado(s) que requieren acción inmediata.
        </div>
    <?php endif; ?>

    <div style="margin-bottom: 20px;">
        <div class="filters">
            <a href="index.php?ruta=prestamos" class="btn-modern btn-primary-modern">
                <i class="fas fa-list"></i> Todos
            </a>
            <a href="index.php?ruta=prestamos&accion=activos" class="btn-modern btn-success-modern">
                <i class="fas fa-check-circle"></i> Activos
            </a>
            <a href="index.php?ruta=prestamos&accion=atrasados" class="btn-modern btn-danger-modern">
                <i class="fas fa-exclamation-triangle"></i> Atrasados
            </a>
        </div>
        <a href="index.php?ruta=prestamos&accion=crear" class="btn-modern btn-success-modern" style="margin-top: 15px;">
            <i class="fas fa-plus"></i> Nuevo Préstamo
        </a>
    </div>

    <div class="table-container">
        <?php if (!empty($prestamos)): ?>
            <table>
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
                    <?php foreach ($prestamos as $prestamo): ?>
                        <tr>
                            <td><?php echo $prestamo['id']; ?></td>
                            <td><?php echo htmlspecialchars($prestamo['usuario_nombre'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($prestamo['libro_titulo'] ?? 'N/A'); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_prestamo'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_devolucion_esperada'])); ?></td>
                            <td>
                                <span class="badge badge-danger">
                                    <?php echo $prestamo['dias_atraso'] ?? 0; ?> días
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="index.php?ruta=prestamos&accion=devolver&id=<?php echo $prestamo['id']; ?>" style="display: inline;">
                                    <button type="submit" class="btn-sm btn-success" onclick="return confirm('¿Confirmar devolución?')">
                                        <i class="fas fa-check"></i> Devolver
                                    </button>
                                </form>
                                <a href="index.php?ruta=prestamos&accion=ver&id=<?php echo $prestamo['id']; ?>" class="btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: var(--text-secondary); padding: 40px;">
                <i class="fas fa-check-circle" style="font-size: 3rem; color: #10b981; display: block; margin-bottom: 15px;"></i>
                ¡Excelente! No hay préstamos atrasados.
            </p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>