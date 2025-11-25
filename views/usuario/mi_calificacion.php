<?php
$page_title = "Mi Calificación - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT * FROM comportamiento_usuarios WHERE usuario_id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$comportamiento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comportamiento) {
    $stmt = $conn->prepare("INSERT INTO comportamiento_usuarios (usuario_id) VALUES (?)");
    $stmt->execute([$_SESSION['usuario_id']]);
    $comportamiento = [
        'puntos_totales' => 100,
        'nivel' => 'bueno',
        'prestamos_consecutivos_a_tiempo' => 0,
        'total_prestamos_completados' => 0,
        'total_retrasos' => 0
    ];
}

$stmt = $conn->prepare("
    SELECT i.*, l.titulo as libro_titulo
    FROM incidentes_usuarios i
    LEFT JOIN prestamos p ON i.prestamo_id = p.id
    LEFT JOIN libros l ON p.libro_id = l.id
    WHERE i.usuario_id = ?
    ORDER BY i.fecha_incidente DESC
    LIMIT 20
");
$stmt->execute([$_SESSION['usuario_id']]);
$incidentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$niveles = [
    'excelente' => ['nombre' => 'Excelente', 'color' => '#10b981', 'icono' => '🌟', 'limite' => 5, 'rango' => '100+'],
    'bueno' => ['nombre' => 'Bueno', 'color' => '#3b82f6', 'icono' => '✅', 'limite' => 3, 'rango' => '50-99'],
    'regular' => ['nombre' => 'Regular', 'color' => '#f59e0b', 'icono' => '⚠️', 'limite' => 2, 'rango' => '20-49'],
    'bajo' => ['nombre' => 'Bajo', 'color' => '#ef4444', 'icono' => '🔴', 'limite' => 1, 'rango' => '0-19'],
    'suspendido' => ['nombre' => 'Suspendido', 'color' => '#991b1b', 'icono' => '🚫', 'limite' => 0, 'rango' => 'Negativo']
];

$nivel_actual = $niveles[$comportamiento['nivel']];

require_once __DIR__ . '/../layouts/navbar.php';
?>

<style>
body { padding-top: 100px; padding-bottom: 50px; background: var(--bg-primary); }
.container { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }
.header { text-align: center; margin-bottom: 40px; }
.header h1 { font-size: 2.5rem; color: var(--text-primary); margin-bottom: 10px; }
.header p { color: var(--text-secondary); font-size: 1.1rem; }

.score-card {
    background: linear-gradient(135deg, <?= $nivel_actual['color'] ?> 0%, <?= $nivel_actual['color'] ?>dd 100%);
    border-radius: 20px;
    padding: 40px;
    text-align: center;
    color: white;
    margin-bottom: 30px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    animation: slideIn 0.5s ease;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.score-icon { font-size: 5rem; margin-bottom: 20px; animation: bounce 2s infinite; }

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.score-points { font-size: 4rem; font-weight: 700; margin-bottom: 10px; }
.score-level { font-size: 1.8rem; font-weight: 600; margin-bottom: 10px; }
.score-limite { font-size: 1.2rem; opacity: 0.9; }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: var(--bg-card);
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 4px 15px var(--shadow-color);
    transition: transform 0.3s ease;
}

.stat-card:hover { transform: translateY(-5px); }

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--accent-primary);
    margin-bottom: 10px;
}

.stat-label { color: var(--text-secondary); font-size: 0.95rem; }

.niveles-info, .historial {
    background: var(--bg-card);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px var(--shadow-color);
}

.section-title {
    font-size: 1.5rem;
    color: var(--text-primary);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.nivel-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 10px;
    background: var(--bg-secondary);
    transition: all 0.3s ease;
}

.nivel-item:hover { transform: translateX(5px); }

.nivel-item.actual {
    border: 3px solid var(--accent-primary);
    box-shadow: 0 0 20px var(--accent-shadow);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 0 20px var(--accent-shadow); }
    50% { box-shadow: 0 0 30px var(--accent-shadow); }
}

.nivel-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.nivel-icon { font-size: 2rem; }

.nivel-details strong {
    color: var(--text-primary);
    font-size: 1.1rem;
}

.nivel-desc {
    font-size: 0.85rem;
    color: var(--text-secondary);
    margin-top: 3px;
}

.nivel-puntos {
    font-weight: 700;
    font-size: 1.1rem;
    padding: 8px 15px;
    border-radius: 8px;
}

.incidente-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 10px;
    background: var(--bg-secondary);
    transition: all 0.3s ease;
}

.incidente-item:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 10px var(--shadow-color);
}

.incidente-positivo { border-left: 4px solid #10b981; }
.incidente-negativo { border-left: 4px solid #ef4444; }

.incidente-content strong {
    color: var(--text-primary);
    display: block;
    margin-bottom: 5px;
}

.incidente-libro {
    font-size: 0.85rem;
    color: var(--text-secondary);
    margin-bottom: 3px;
}

.incidente-fecha {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.puntos-badge {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.9rem;
    white-space: nowrap;
}

.puntos-positivos { background: #d1fae5; color: #065f46; }
.puntos-negativos { background: #fee2e2; color: #991b1b; }

.info-box {
    background: #dbeafe;
    border-left: 4px solid #3b82f6;
    padding: 20px;
    border-radius: 10px;
    margin-top: 30px;
}

.info-box h3 {
    color: #1e40af;
    margin-bottom: 10px;
    font-size: 1.2rem;
}

.info-box ul {
    color: #1e3a8a;
    margin: 0;
    padding-left: 20px;
}

.info-box li { margin-bottom: 8px; }

.empty-state {
    text-align: center;
    padding: 40px;
    color: var(--text-secondary);
}

.empty-state-icon { font-size: 4rem; margin-bottom: 15px; opacity: 0.5; }

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr; }
    .nivel-item, .incidente-item {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    .nivel-info { flex-direction: column; }
    .score-points { font-size: 3rem; }
    .score-level { font-size: 1.5rem; }
}
</style>

<div class="container">
    <div class="header">
        <h1>📊 Mi Calificación de Usuario</h1>
        <p>Tu comportamiento como lector responsable</p>
    </div>

    <div class="score-card">
        <div class="score-icon"><?= $nivel_actual['icono'] ?></div>
        <div class="score-points"><?= $comportamiento['puntos_totales'] ?></div>
        <div class="score-level">Nivel: <?= $nivel_actual['nombre'] ?></div>
        <div class="score-limite">
            Puedes tener hasta <?= $nivel_actual['limite'] ?> préstamo(s) simultáneo(s)
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?= $comportamiento['total_prestamos_completados'] ?></div>
            <div class="stat-label">📚 Préstamos Completados</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $comportamiento['prestamos_consecutivos_a_tiempo'] ?></div>
            <div class="stat-label">⏰ Consecutivos a Tiempo</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $comportamiento['total_retrasos'] ?></div>
            <div class="stat-label">⚠️ Total de Retrasos</div>
        </div>
    </div>

    <div class="niveles-info">
        <h2 class="section-title">📈 Sistema de Niveles</h2>
        <?php foreach ($niveles as $key => $nivel): ?>
            <div class="nivel-item <?= $comportamiento['nivel'] === $key ? 'actual' : '' ?>">
                <div class="nivel-info">
                    <span class="nivel-icon"><?= $nivel['icono'] ?></span>
                    <div class="nivel-details">
                        <strong><?= $nivel['nombre'] ?></strong>
                        <div class="nivel-desc">
                            Hasta <?= $nivel['limite'] ?> préstamo(s) simultáneo(s)
                        </div>
                    </div>
                </div>
                <div class="nivel-puntos" style="background: <?= $nivel['color'] ?>20; color: <?= $nivel['color'] ?>;">
                    <?= $nivel['rango'] ?> pts
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="historial">
        <h2 class="section-title">📜 Historial de Actividad</h2>
        <?php if (empty($incidentes)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <p>No hay actividad registrada aún</p>
            </div>
        <?php else: ?>
            <?php foreach ($incidentes as $inc): ?>
                <div class="incidente-item <?= $inc['puntos_afectados'] > 0 ? 'incidente-positivo' : 'incidente-negativo' ?>">
                    <div class="incidente-content">
                        <strong><?= htmlspecialchars($inc['descripcion']) ?></strong>
                        <?php if ($inc['libro_titulo']): ?>
                            <div class="incidente-libro">
                                📖 <?= htmlspecialchars($inc['libro_titulo']) ?>
                            </div>
                        <?php endif; ?>
                        <div class="incidente-fecha">
                            <?= date('d/m/Y H:i', strtotime($inc['fecha_incidente'])) ?>
                        </div>
                    </div>
                    <span class="puntos-badge <?= $inc['puntos_afectados'] > 0 ? 'puntos-positivos' : 'puntos-negativos' ?>">
                        <?= $inc['puntos_afectados'] > 0 ? '+' : '' ?><?= $inc['puntos_afectados'] ?> pts
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="info-box">
        <h3>💡 ¿Cómo mejorar tu calificación?</h3>
        <ul>
            <li>✅ Devuelve los libros a tiempo (+2 puntos)</li>
            <li>🎉 Completa 10 devoluciones consecutivas a tiempo (+20 puntos bonus)</li>
            <li>⭐ Deja reseñas de los libros (+5 puntos)</li>
            <li>⚠️ Evita retrasos (de -5 a -20 puntos según gravedad)</li>
        </ul>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>