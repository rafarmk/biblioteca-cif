<?php
if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->query("SELECT COUNT(*) as total FROM libros");
$totalLibros = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE tipo_usuario != 'administrador'");
$totalUsuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'");
$prestamosActivos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE estado = 'pendiente'");
$solicitudesPendientes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

require_once __DIR__ . '/layouts/navbar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Biblioteca CIF</title>
    <style>
        body {
            padding-top: 90px;
            padding-bottom: 0;
        }

        .container {
            flex: 1;
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
        }

        .header h1 {
            font-size: 2.5rem;
            color: var(--text-primary);
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .stat-card {
            background: var(--bg-card);
            padding: 35px 25px;
            border-radius: 15px;
            box-shadow: 0 8px 20px var(--shadow);
            text-align: center;
            transition: all 0.3s ease;
            border-top: 4px solid var(--card-color);
        }

        .stat-card:nth-child(1) { --card-color: #667eea; }
        .stat-card:nth-child(2) { --card-color: #10b981; }
        .stat-card:nth-child(3) { --card-color: #3b82f6; }
        .stat-card:nth-child(4) { --card-color: #ef4444; }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px var(--shadow);
        }

        .stat-icon {
            font-size: 3.5rem;
            margin-bottom: 20px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 15px;
            color: var(--card-color);
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .btn-link {
            display: inline-block;
            padding: 12px 25px;
            background: var(--card-color);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px var(--shadow);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📊 Panel de Control</h1>
        <p>Bienvenido, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📚</div>
            <div class="stat-number"><?= $totalLibros ?></div>
            <div class="stat-label">Total de Libros</div>
            <a href="index.php?ruta=libros" class="btn-link">Ver Libros</a>
        </div>

        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-number"><?= $totalUsuarios ?></div>
            <div class="stat-label">Usuarios Registrados</div>
            <a href="index.php?ruta=usuarios" class="btn-link">Ver Usuarios</a>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📖</div>
            <div class="stat-number"><?= $prestamosActivos ?></div>
            <div class="stat-label">Préstamos Activos</div>
            <a href="index.php?ruta=prestamos" class="btn-link">Ver Préstamos</a>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🔔</div>
            <div class="stat-number"><?= $solicitudesPendientes ?></div>
            <div class="stat-label">Solicitudes Pendientes</div>
            <a href="index.php?ruta=solicitudes" class="btn-link">Ver Solicitudes</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
</body>
</html>