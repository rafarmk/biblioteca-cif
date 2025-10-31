<?php require_once 'views/layouts/navbar.php'; ?>

<style>
.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.pending-section {
    background: var(--bg-card);
    border: 2px solid #f59e0b;
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 10px 40px rgba(245, 158, 11, 0.15);
}

.pending-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.pending-header-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
}

.pending-header h2 {
    font-size: 1.6rem;
    color: var(--text-primary);
    font-weight: 700;
}

.pending-badge {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    padding: 6px 16px;
    border-radius: 25px;
    font-size: 0.95rem;
    font-weight: 700;
}

.pending-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 25px;
}

.pending-card {
    background: var(--bg-secondary);
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    border: 2px solid var(--border-color);
}

.pending-card-header {
    display: flex;
    align-items: start;
    gap: 15px;
    margin-bottom: 20px;
}

.pending-avatar {
    width: 55px;
    height: 55px;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    color: white;
    font-weight: 700;
}

.pending-info h3 {
    font-size: 1.15rem;
    color: var(--text-primary);
    font-weight: 700;
}

.pending-info p {
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.pending-details {
    margin: 20px 0;
    padding: 15px;
    background: var(--bg-primary);
    border-radius: 12px;
}

.pending-detail {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    font-size: 0.88rem;
    color: var(--text-primary);
}

.pending-detail:last-child {
    margin-bottom: 0;
}

.pending-detail i {
    color: #f59e0b;
}

.pending-actions {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

.pending-actions form {
    flex: 1;
}

.pending-actions button {
    width: 100%;
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

.actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}
</style>

<div class="container">
    <div class="page-header">
        <h1 class="page-title"> Gestión de Usuarios</h1>
        <a href="index.php?ruta=usuarios&accion=crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success">
            <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
            <div class="alert-content"><?php echo htmlspecialchars($_SESSION['mensaje']); unset($_SESSION['mensaje']); ?></div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="alert-content"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        </div>
    <?php endif; ?>

    <?php if (!empty($usuariosPendientes)): ?>
        <div class="pending-section">
            <div class="pending-header">
                <div class="pending-header-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <h2>Solicitudes Pendientes</h2>
                <span class="pending-badge"><?php echo count($usuariosPendientes); ?></span>
            </div>

            <div class="pending-cards">
                <?php foreach ($usuariosPendientes as $pendiente): ?>
                    <div class="pending-card">
                        <div class="pending-card-header">
                            <div class="pending-avatar">
                                <?php echo strtoupper(substr($pendiente['nombre'], 0, 1)); ?>
                            </div>
                            <div class="pending-info">
                                <h3><?php echo htmlspecialchars($pendiente['nombre']); ?></h3>
                                <p><?php echo htmlspecialchars($pendiente['tipo_usuario']); ?></p>
                            </div>
                        </div>

                        <div class="pending-details">
                            <div class="pending-detail">
                                <i class="fas fa-envelope"></i>
                                <span><?php echo htmlspecialchars($pendiente['email']); ?></span>
                            </div>
                            <div class="pending-detail">
                                <i class="fas fa-phone"></i>
                                <span><?php echo htmlspecialchars($pendiente['telefono']); ?></span>
                            </div>
                            <div class="pending-detail">
                                <i class="fas fa-id-card"></i>
                                <span>DUI: <?php echo htmlspecialchars($pendiente['dui']); ?></span>
                            </div>
                            <div class="pending-detail">
                                <i class="fas fa-calendar"></i>
                                <span><?php echo date('d/m/Y H:i', strtotime($pendiente['fecha_registro'])); ?></span>
                            </div>
                        </div>

                        <div class="pending-actions">
                            <form method="POST" action="index.php?ruta=usuarios&accion=aprobar" onsubmit="return confirm('¿Aprobar a este usuario?');">
                                <input type="hidden" name="id" value="<?php echo $pendiente['id']; ?>">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Aprobar
                                </button>
                            </form>
                            <form method="POST" action="index.php?ruta=usuarios&accion=rechazar" onsubmit="return confirm('¿Rechazar y eliminar?');">
                                <input type="hidden" name="id" value="<?php echo $pendiente['id']; ?>">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Rechazar
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2 style="margin-bottom: 20px; font-size: 1.4rem;"> Todos los Usuarios</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['tipo_usuario']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $usuario['estado']; ?>">
                                <?php echo ucfirst($usuario['estado']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="index.php?ruta=usuarios&accion=editar&id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="index.php?ruta=usuarios&accion=eliminar" style="display: inline;" onsubmit="return confirm('¿Eliminar este usuario?');">
                                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
