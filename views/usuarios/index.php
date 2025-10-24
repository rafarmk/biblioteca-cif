<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<style>
.main-container {
    max-width: 1400px;
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

.btn-primary {
    background: var(--primary);
    color: white;
    box-shadow: 0 4px 15px var(--shadow);
}

.btn-primary:hover {
    transform: translateY(-2px);
    opacity: 0.9;
}

.btn-success {
    background: var(--success);
    color: white;
}

.btn-danger {
    background: var(--accent);
    color: white;
}

.content-card {
    background: var(--bg-card);
    border-radius: 16px;
    box-shadow: 0 4px 20px var(--shadow);
    overflow: hidden;
    border: 2px solid var(--border-color);
}

[data-theme="premium"] .content-card {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: rgba(56, 189, 248, 0.2);
}

.card-header {
    padding: 25px 30px;
    background: var(--bg-secondary);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    border-bottom: 2px solid var(--border-color);
}

.card-header h2 {
    color: var(--text-primary);
    font-size: 1.5rem;
    font-weight: 600;
}

.search-input {
    padding: 12px 20px;
    border: 2px solid var(--border-color);
    background: var(--bg-primary);
    border-radius: 10px;
    color: var(--text-primary);
    font-size: 14px;
    width: 300px;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary);
}

.table-container {
    overflow-x: auto;
    padding: 30px;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead th {
    background: var(--bg-secondary);
    padding: 15px 20px;
    text-align: left;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 13px;
    text-transform: uppercase;
    border-bottom: 2px solid var(--border-color);
}

.table tbody tr {
    border-bottom: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background: var(--bg-secondary);
}

.table tbody td {
    padding: 18px 20px;
    color: var(--text-secondary);
    font-size: 14px;
}

.badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.badge-estudiante {
    background: #dbeafe;
    color: #1e40af;
}

.badge-docente {
    background: #d1fae5;
    color: #065f46;
}

.badge-personal {
    background: #fef3c7;
    color: #92400e;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 13px;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }

    .search-input {
        width: 100%;
    }

    .table-container {
        padding: 15px;
    }
}
</style>

<div class="main-container">
    <div class="page-header">
        <h1> Gestión de Usuarios</h1>
        <a href="index.php?ruta=usuarios&accion=crear" class="btn btn-primary">
             Agregar Usuario
        </a>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h2>Lista de Usuarios</h2>
            <input type="text" id="searchInput" placeholder=" Buscar usuario..." class="search-input">
        </div>
        
        <div class="table-container">
            <?php if (isset($usuarios) && count($usuarios) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="usuariosTable">
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['correo'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($usuario['telefono'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php 
                                    $tipo = $usuario['tipo_usuario'] ?? 'estudiante';
                                    $badgeClass = 'badge-' . strtolower($tipo);
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo ucfirst($tipo); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="index.php?ruta=usuarios&accion=editar&id=<?php echo $usuario['id']; ?>" 
                                           class="btn btn-success btn-sm">
                                             Editar
                                        </a>
                                        <a href="index.php?ruta=usuarios&accion=eliminar&id=<?php echo $usuario['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                             Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align: center; padding: 60px 20px; color: var(--text-secondary);">
                    <div style="font-size: 4rem; margin-bottom: 20px; opacity: 0.5;"></div>
                    <h3 style="color: var(--text-primary);">No hay usuarios registrados</h3>
                    <p>Comienza agregando el primer usuario</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#usuariosTable tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
