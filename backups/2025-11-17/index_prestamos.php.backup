<?php
if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

// Calcular estadísticas
$stmt = $conn->query("SELECT COUNT(*) as total FROM prestamos");
$totalPrestamos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'");
$prestamosActivos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("
    SELECT COUNT(*) as total 
    FROM prestamos 
    WHERE estado = 'activo' 
    AND fecha_devolucion < CURDATE()
");
$prestamosAtrasados = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Obtener lista de préstamos
$stmt = $conn->query("
    SELECT 
        p.*,
        u.nombre as usuario_nombre,
        u.apellido as usuario_apellido,
        u.email as usuario_email,
        l.titulo as libro_titulo,
        l.autor as libro_autor,
        l.isbn,
        DATEDIFF(p.fecha_devolucion, CURDATE()) as dias_restantes
    FROM prestamos p
    INNER JOIN usuarios u ON p.usuario_id = u.id
    INNER JOIN libros l ON p.libro_id = l.id
    ORDER BY p.fecha_prestamo DESC
");
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../layouts/navbar.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Préstamos - Biblioteca CIF</title>
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            padding-top: 100px;
            padding-bottom: 150px;
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
            color: #fff;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
        }

        .actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #6b7280;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .stat-link {
            margin-top: 15px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .stat-link:hover {
            gap: 10px;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .table-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95rem;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        tbody tr {
            transition: all 0.3s;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
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
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .btn-small {
            padding: 6px 14px;
            font-size: 0.85rem;
            border-radius: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 2px solid #10b981;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #ef4444;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📚 Gestión de Préstamos</h1>
        <p>Administra todos los préstamos de libros del sistema</p>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?>">
            <?= $_SESSION['mensaje']['texto'] ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="actions">
        <a href="index.php?ruta=prestamos&accion=crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Préstamo
        </a>
        <a href="#activos" class="btn btn-success">
            <i class="fas fa-list"></i> Préstamos Activos
        </a>
        <a href="#atrasados" class="btn btn-danger">
            <i class="fas fa-exclamation-triangle"></i> Atrasados
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-number" style="color: #667eea;"><?= $totalPrestamos ?></div>
            <div class="stat-label">Total de Préstamos</div>
            <a href="#todos" class="stat-link">
                Ver todos <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-number" style="color: #10b981;"><?= $prestamosActivos ?></div>
            <div class="stat-label">Préstamos Activos</div>
            <a href="#activos" class="stat-link">
                Ver activos <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⚠️</div>
            <div class="stat-number" style="color: #ef4444;"><?= $prestamosAtrasados ?></div>
            <div class="stat-label">Préstamos Atrasados</div>
            <a href="#atrasados" class="stat-link">
                Ver atrasados <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <div class="table-title">📋 Lista de Préstamos</div>
        </div>

        <?php if (empty($prestamos)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📚</div>
                <h3>No hay préstamos registrados</h3>
                <p>Comienza creando un nuevo préstamo</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Libro</th>
                        <th>ISBN</th>
                        <th>Fecha Préstamo</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prestamos as $prestamo): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($prestamo['usuario_nombre'] . ' ' . $prestamo['usuario_apellido']) ?></strong><br>
                                <small style="color: #6b7280;"><?= htmlspecialchars($prestamo['usuario_email']) ?></small>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($prestamo['libro_titulo']) ?></strong><br>
                                <small style="color: #6b7280;"><?= htmlspecialchars($prestamo['libro_autor']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($prestamo['isbn']) ?></td>
                            <td><?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($prestamo['fecha_devolucion'])) ?></td>
                            <td>
                                <?php if ($prestamo['estado'] == 'activo'): ?>
                                    <?php if ($prestamo['dias_restantes'] < 0): ?>
                                        <span class="badge badge-atrasado">
                                            ⚠️ Atrasado (<?= abs($prestamo['dias_restantes']) ?> días)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-activo">
                                            ✅ Activo (<?= $prestamo['dias_restantes'] ?> días)
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-devuelto">📥 Devuelto</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($prestamo['estado'] == 'activo'): ?>
                                    <a href="index.php?ruta=prestamos&accion=devolver&id=<?= $prestamo['id'] ?>" 
                                       class="btn btn-success btn-small"
                                       onclick="return confirm('¿Marcar este préstamo como devuelto?')">
                                        <i class="fas fa-check"></i> Devolver
                                    </a>
                                <?php else: ?>
                                    <span style="color: #10b981; font-weight: 600;">
                                        <i class="fas fa-check-circle"></i> Completado
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>