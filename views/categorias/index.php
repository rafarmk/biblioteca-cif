<?php
$page_title = "Gestión de Categorías - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['logueado']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->query("
    SELECT c.*, COUNT(l.id) as total_libros
    FROM categorias c
    LEFT JOIN libros l ON c.id = l.categoria_id
    GROUP BY c.id
    ORDER BY c.nombre
");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../layouts/navbar.php';
?>

<style>
body { padding-top: 100px; padding-bottom: 50px; background: var(--bg-primary); }
.container { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.header h1 {
    font-size: 2rem;
    color: var(--text-primary);
}

.btn-crear {
    padding: 12px 24px;
    background: var(--accent-primary);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-crear:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px var(--accent-shadow);
}

.categorias-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.categoria-card {
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px var(--shadow-color);
    border-left: 4px solid;
    transition: all 0.3s;
}

.categoria-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px var(--shadow-color);
}

.categoria-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.categoria-icono {
    font-size: 2rem;
}

.categoria-nombre {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.categoria-descripcion {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 15px;
    min-height: 40px;
}

.categoria-stats {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    font-size: 0.85rem;
    align-items: center;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: var(--text-secondary);
}

.categoria-actions {
    display: flex;
    gap: 10px;
}

.btn-sm {
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-edit {
    background: #3b82f6;
    color: white;
}

.btn-edit:hover {
    background: #2563eb;
}

.btn-delete {
    background: #ef4444;
    color: white;
}

.btn-delete:hover {
    background: #dc2626;
}

.badge-estado {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-activo {
    background: #d1fae5;
    color: #065f46;
}

.badge-inactivo {
    background: #fee2e2;
    color: #991b1b;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-secondary);
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

@media (max-width: 768px) {
    .categorias-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="container">
    <div class="header">
        <div>
            <h1>📚 Gestión de Categorías</h1>
            <p style="color: var(--text-secondary); margin-top: 5px;">Organiza los libros por categorías</p>
        </div>
        <a href="index.php?ruta=categorias&accion=crear" class="btn-crear">
            <span>+</span> Nueva Categoría
        </a>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <?php 
        $tipo = is_array($_SESSION['mensaje']) ? ($_SESSION['mensaje']['tipo'] ?? 'success') : 'success';
        $texto = is_array($_SESSION['mensaje']) ? ($_SESSION['mensaje']['texto'] ?? '') : $_SESSION['mensaje'];
        $bgColor = $tipo === 'success' ? '#d1fae5' : ($tipo === 'warning' ? '#fef3c7' : '#fee2e2');
        $textColor = $tipo === 'success' ? '#065f46' : ($tipo === 'warning' ? '#92400e' : '#991b1b');
        ?>
        <div class="alert alert-<?= $tipo ?>" style="padding: 15px; border-radius: 8px; margin-bottom: 20px; background: <?= $bgColor ?>; color: <?= $textColor ?>;">        
            <?= htmlspecialchars($texto) ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <?php if (empty($categorias)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <p>No hay categorías registradas</p>
            <p style="margin-top: 10px;">
                <a href="index.php?ruta=categorias&accion=crear" class="btn-crear">Crear primera categoría</a>
            </p>
        </div>
    <?php else: ?>
        <div class="categorias-grid">
            <?php foreach ($categorias as $cat): ?>
                <div class="categoria-card" style="border-left-color: <?= htmlspecialchars($cat['color'] ?? '#3b82f6') ?>;">
                    <div class="categoria-header">
                        <span class="categoria-icono"><?= htmlspecialchars($cat['icono'] ?? '📚') ?></span>
                        <span class="categoria-nombre"><?= htmlspecialchars($cat['nombre'] ?? '') ?></span>
                    </div>

                    <p class="categoria-descripcion"><?= htmlspecialchars($cat['descripcion'] ?? '') ?></p>

                    <div class="categoria-stats">
                        <span class="stat-item">
                            📖 <?= intval($cat['total_libros'] ?? 0) ?> libro(s)
                        </span>
                        <span class="badge-estado badge-<?= htmlspecialchars($cat['estado'] ?? 'activo') ?>">
                            <?= ($cat['estado'] ?? 'activo') === 'activo' ? '✓ Activo' : '✗ Inactivo' ?>
                        </span>
                    </div>

                    <div class="categoria-actions">
                        <a href="index.php?ruta=categorias&accion=editar&id=<?= intval($cat['id']) ?>" class="btn-sm btn-edit">
                            ✏️ Editar
                        </a>
                        <?php if (intval($cat['total_libros'] ?? 0) == 0): ?>
                            <a href="index.php?ruta=categorias&accion=eliminar&id=<?= intval($cat['id']) ?>"
                               class="btn-sm btn-delete"
                               onclick="return confirm('¿Eliminar categoría <?= htmlspecialchars($cat['nombre'] ?? '') ?>?')">
                                🗑️ Eliminar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>