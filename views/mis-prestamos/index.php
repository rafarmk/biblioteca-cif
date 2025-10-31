<?php require_once 'views/layouts/navbar.php'; ?>

<style>
.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    border: 2px solid var(--border-color);
    text-align: center;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin: 0 auto 15px;
}

.stat-icon.blue {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.stat-icon.green {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.stat-icon.gray {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    color: white;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 5px;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

thead th {
    padding: 18px 16px;
    text-align: center;
    font-weight: 700;
    color: white;
    font-size: 0.85rem;
    text-transform: uppercase;
}

tbody td {
    padding: 16px;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-primary);
    text-align: center;
    vertical-align: middle;
}

tbody tr:hover {
    background: var(--bg-secondary);
}

.book-info {
    text-align: left;
}

.book-title {
    font-weight: 600;
    margin-bottom: 5px;
}

.book-isbn {
    font-size: 0.85rem;
    color: var(--text-secondary);
}
</style>

<div class="container">
    <div class="page-header">
        <h1 class="page-title"> Mis Préstamos</h1>
        <p class="page-subtitle">Historial de libros que has pedido prestados</p>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success">
            <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
            <div class="alert-content"><?php echo htmlspecialchars($_SESSION['mensaje']); unset($_SESSION['mensaje']); ?></div>
        </div>
    <?php endif; ?>

    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-number"><?php echo $total; ?></div>
            <div class="stat-label">Total Préstamos</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number"><?php echo $activos; ?></div>
            <div class="stat-label">Activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon gray">
                <i class="fas fa-undo"></i>
            </div>
            <div class="stat-number"><?php echo $devueltos; ?></div>
            <div class="stat-label">Devueltos</div>
        </div>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 20px; font-size: 1.4rem;"> Historial de Préstamos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Libro</th>
                    <th>Fecha Préstamo</th>
                    <th>Fecha Devolución</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($prestamos)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px;">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: var(--text-secondary); display: block; margin-bottom: 15px;"></i>
                            No tienes préstamos registrados
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($prestamos as $prestamo): ?>
                        <tr>
                            <td><strong>#<?php echo $prestamo['id']; ?></strong></td>
                            <td class="book-info">
                                <div class="book-title"><?php echo htmlspecialchars($prestamo['libro_titulo']); ?></div>
                                <div class="book-isbn">ISBN: <?php echo htmlspecialchars($prestamo['isbn']); ?></div>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_prestamo'])); ?></td>
                            <td>
                                <?php 
                                if ($prestamo['fecha_devolucion_esperada']) {
                                    echo date('d/m/Y', strtotime($prestamo['fecha_devolucion_esperada']));
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                $estado = $prestamo['estado'];
                                // Verificar si está atrasado
                                if ($estado === 'activo' && $prestamo['fecha_devolucion_esperada']) {
                                    if (strtotime($prestamo['fecha_devolucion_esperada']) < time()) {
                                        $estado = 'atrasado';
                                    }
                                }
                                
                                if ($estado === 'activo'): ?>
                                    <span class="badge badge-activo">
                                        <i class="fas fa-check"></i> Activo
                                    </span>
                                <?php elseif ($estado === 'atrasado'): ?>
                                    <span class="badge badge-atrasado">
                                        <i class="fas fa-exclamation-triangle"></i> Atrasado
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-devuelto">
                                        <i class="fas fa-undo"></i> Devuelto
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
