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
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    border: 2px solid var(--border-color);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 15px;
}

.stat-card-icon.blue {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.stat-card-icon.green {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.stat-card-icon.orange {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.stat-card h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-card p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.search-container {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 2px solid var(--border-color);
}

.search-box {
    display: flex;
    gap: 12px;
}

.search-input {
    flex: 1;
    padding: 14px 20px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 14px;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
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
    text-align: left;
    font-weight: 700;
    color: white;
    font-size: 0.85rem;
    text-transform: uppercase;
    text-align: center;
    text-align: center;
}

tbody td {
    padding: 16px;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-primary);
    vertical-align: middle;
    text-align: center;
    text-align: center;
}

tbody tr:hover {
    background: var(--bg-secondary);
}

.book-title {
    font-weight: 600;
}

.actions {
    display: flex;
    gap: 8px;
}
</style>

<div class="container">
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-book"></i> Gestión de Libros</h1>
        <a href="index.php?ruta=libros&accion=crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Agregar Libro
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

    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-card-icon blue"><i class="fas fa-book"></i></div>
            <h3><?php echo count($libros); ?></h3>
            <p>Total de Libros</p>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon green"><i class="fas fa-check-circle"></i></div>
            <h3><?php echo count(array_filter($libros, fn($l) => $l['estado'] === 'disponible')); ?></h3>
            <p>Libros Disponibles</p>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon orange"><i class="fas fa-handshake"></i></div>
            <h3><?php echo count(array_filter($libros, fn($l) => $l['estado'] === 'prestado')); ?></h3>
            <p>Libros Prestados</p>
        </div>
    </div>

    <div class="search-container">
        <form method="GET" action="index.php">
            <input type="hidden" name="ruta" value="libros">
            <div class="search-box">
                <input type="text" name="buscar" class="search-input" placeholder=" Buscar por título, autor, ISBN..." value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                <?php if (isset($_GET['buscar'])): ?>
                    <a href="index.php?ruta=libros" class="btn btn-secondary"><i class="fas fa-times"></i> Limpiar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 20px;"> Catálogo de Libros</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>ISBN</th>
                    <th>Categoría</th>
                    <th>Año</th>
                    <th>Disponibles</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($libros)): ?>
                    <tr><td colspan="9" style="text-align: center; padding: 40px;">No hay libros registrados</td></tr>
                <?php else: ?>
                    <?php foreach ($libros as $libro): ?>
                        <tr>
                            <td><strong>#<?php echo $libro['id']; ?></strong></td>
                            <td class="book-title"><?php echo htmlspecialchars($libro['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($libro['autor']); ?></td>
                            <td><code style="background: var(--bg-secondary); padding: 4px 8px; border-radius: 6px;"><?php echo htmlspecialchars($libro['isbn']); ?></code></td>
                            <td><?php echo htmlspecialchars($libro['categoria']); ?></td>
                            <td><?php echo $libro['anio_publicacion'] ?? 'N/A'; ?></td>
                            <td><strong style="color: var(--primary);"><?php echo $libro['cantidad_disponible']; ?></strong> / <?php echo $libro['cantidad_total']; ?></td>
                            <td>
                                <?php if ($libro['estado'] === 'disponible'): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Disponible</span>
                                <?php elseif ($libro['estado'] === 'prestado'): ?>
                                    <span class="badge badge-warning"><i class="fas fa-handshake"></i> Prestado</span>
                                <?php else: ?>
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> No Disponible</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="index.php?ruta=libros&accion=editar&id=<?php echo $libro['id']; ?>" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                    <form method="POST" action="index.php?ruta=libros&accion=eliminar" style="display: inline;" onsubmit="return confirm('¿Eliminar este libro?');">
                                        <input type="hidden" name="id" value="<?php echo $libro['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
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


