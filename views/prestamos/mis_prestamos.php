<?php
$page_title = "Mis Préstamos - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

// ✅ CORREGIDO: Usar usuario_id
$query = "SELECT p.*, l.titulo, l.autor, l.isbn, l.ubicacion,
          DATEDIFF(p.fecha_devolucion_esperada, CURDATE()) as dias_restantes
          FROM prestamos p
          INNER JOIN libros l ON p.libro_id = l.id
          WHERE p.usuario_id = ?
          ORDER BY p.estado ASC, p.fecha_prestamo DESC";

$stmt = $conn->prepare($query);
$stmt->execute([$_SESSION['usuario_id']]);
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Separar préstamos activos y devueltos
$prestamos_activos = array_filter($prestamos, fn($p) => $p['estado'] === 'activo');
$prestamos_devueltos = array_filter($prestamos, fn($p) => $p['estado'] === 'devuelto');

require_once __DIR__ . '/../layouts/navbar.php';
?>

<style>
body {
    padding-top: 100px;
    padding-bottom: 50px;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px 20px;
}

.header {
    text-align: center;
    margin-bottom: 40px;
}

.header h1 {
    font-size: 2.5rem;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.header p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    font-weight: 500;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 2px solid #10b981;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 2px solid #fca5a5;
}

.alert-warning {
    background: #fef3c7;
    color: #92400e;
    border: 2px solid #fbbf24;
}

.tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    border-bottom: 2px solid var(--border-color);
}

.tab {
    padding: 12px 30px;
    background: transparent;
    border: none;
    color: var(--text-secondary);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    border-bottom: 3px solid transparent;
}

.tab.active {
    color: var(--accent-primary);
    border-bottom-color: var(--accent-primary);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.prestamos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
}

.prestamo-card {
    background: var(--bg-card);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 15px var(--shadow-color);
    transition: all 0.3s;
    border: 2px solid transparent;
}

.prestamo-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px var(--shadow-color);
}

.prestamo-card.atrasado {
    border-color: #ef4444;
}

.prestamo-card.proximo-vencer {
    border-color: #fbbf24;
}

.libro-titulo {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.libro-autor {
    color: var(--text-secondary);
    margin-bottom: 15px;
    font-size: 0.95rem;
}

.prestamo-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin: 15px 0;
    padding: 15px;
    background: var(--bg-secondary);
    border-radius: 10px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.info-row strong {
    color: var(--text-primary);
}

.badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-top: 10px;
}

.badge-activo {
    background: #d1fae5;
    color: #065f46;
}

.badge-devuelto {
    background: #e0e7ff;
    color: #3730a3;
}

.badge-atrasado {
    background: #fee2e2;
    color: #991b1b;
}

.badge-proximo {
    background: #fef3c7;
    color: #92400e;
}

.prestamo-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.btn {
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    flex: 1;
    justify-content: center;
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px var(--shadow-color);
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-secondary);
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: var(--text-primary);
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .prestamos-grid {
        grid-template-columns: 1fr;
    }
    
    .tabs {
        overflow-x: auto;
    }
    
    .prestamo-actions {
        flex-direction: column;
    }
}
</style>

<div class="container">
    <div class="header">
        <h1>📚 Mis Préstamos</h1>
        <p>Administra tus libros prestados</p>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <?php 
        $mensaje_tipo = is_array($_SESSION['mensaje']) ? $_SESSION['mensaje']['tipo'] : 'success';
        $mensaje_texto = is_array($_SESSION['mensaje']) ? $_SESSION['mensaje']['texto'] : $_SESSION['mensaje'];
        ?>
        <div class="alert alert-<?= htmlspecialchars($mensaje_tipo) ?>">
            <?= htmlspecialchars($mensaje_texto) ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="tabs">
        <button class="tab active" data-tab="activos">
            📖 Préstamos Activos (<?= count($prestamos_activos) ?>)
        </button>
        <button class="tab" data-tab="historial">
            📋 Historial (<?= count($prestamos_devueltos) ?>)
        </button>
    </div>

    <!-- TAB: Préstamos Activos -->
    <div class="tab-content active" id="activos">
        <?php if (empty($prestamos_activos)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📚</div>
                <h3>No tienes préstamos activos</h3>
                <p>Explora el catálogo y solicita un libro</p>
                <a href="index.php?ruta=catalogo" class="btn btn-primary" style="margin-top: 20px; max-width: 200px; margin-left: auto; margin-right: auto;">
                    Ver Catálogo
                </a>
            </div>
        <?php else: ?>
            <div class="prestamos-grid">
                <?php foreach ($prestamos_activos as $prestamo): ?>
                    <?php
                    $dias = intval($prestamo['dias_restantes']);
                    $clase_estado = '';
                    $badge_texto = '';
                    
                    if ($dias < 0) {
                        $clase_estado = 'atrasado';
                        $badge_clase = 'badge-atrasado';
                        $badge_texto = '⚠️ Atrasado (' . abs($dias) . ' días)';
                    } elseif ($dias <= 3) {
                        $clase_estado = 'proximo-vencer';
                        $badge_clase = 'badge-proximo';
                        $badge_texto = '⏰ Próximo a vencer (' . $dias . ' días)';
                    } else {
                        $badge_clase = 'badge-activo';
                        $badge_texto = '✅ Activo (' . $dias . ' días restantes)';
                    }
                    ?>
                    
                    <div class="prestamo-card <?= $clase_estado ?>">
                        <div class="libro-titulo">
                            📖 <?= htmlspecialchars($prestamo['titulo']) ?>
                        </div>
                        <div class="libro-autor">
                            ✍️ <?= htmlspecialchars($prestamo['autor']) ?>
                        </div>
                        
                        <div class="prestamo-info">
                            <div class="info-row">
                                <span>ISBN:</span>
                                <strong><?= htmlspecialchars($prestamo['isbn']) ?></strong>
                            </div>
                            <div class="info-row">
                                <span>Ubicación:</span>
                                <strong><?= htmlspecialchars($prestamo['ubicacion'] ?? 'N/A') ?></strong>
                            </div>
                            <div class="info-row">
                                <span>Fecha préstamo:</span>
                                <strong><?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?></strong>
                            </div>
                            <div class="info-row">
                                <span>Fecha límite:</span>
                                <strong><?= date('d/m/Y', strtotime($prestamo['fecha_devolucion_esperada'])) ?></strong>
                            </div>
                        </div>
                        
                        <span class="badge <?= $badge_clase ?>">
                            <?= $badge_texto ?>
                        </span>
                        
                        <div class="prestamo-actions">
                            <a href="index.php?ruta=prestamos&accion=devolver&id=<?= $prestamo['id'] ?>" 
                               class="btn btn-success">
                                ✅ Devolver Libro
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- TAB: Historial -->
    <div class="tab-content" id="historial">
        <?php if (empty($prestamos_devueltos)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <h3>No tienes historial de préstamos</h3>
                <p>Aquí aparecerán los libros que hayas devuelto</p>
            </div>
        <?php else: ?>
            <div class="prestamos-grid">
                <?php foreach ($prestamos_devueltos as $prestamo): ?>
                    <div class="prestamo-card">
                        <div class="libro-titulo">
                            📖 <?= htmlspecialchars($prestamo['titulo']) ?>
                        </div>
                        <div class="libro-autor">
                            ✍️ <?= htmlspecialchars($prestamo['autor']) ?>
                        </div>
                        
                        <div class="prestamo-info">
                            <div class="info-row">
                                <span>Fecha préstamo:</span>
                                <strong><?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?></strong>
                            </div>
                            <div class="info-row">
                                <span>Fecha devolución:</span>
                                <strong><?= $prestamo['fecha_devolucion_real'] ? date('d/m/Y', strtotime($prestamo['fecha_devolucion_real'])) : 'N/A' ?></strong>
                            </div>
                        </div>
                        
                        <span class="badge badge-devuelto">
                            ✅ Devuelto
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Remover active de todos
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(tc => tc.classList.remove('active'));
            
            // Agregar active al seleccionado
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>