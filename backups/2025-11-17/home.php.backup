<?php
if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

// Estadísticas
$stmt = $conn->query("SELECT COUNT(*) as total FROM libros");
$totalLibros = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE estado = 'activo'");
$totalUsuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'");
$prestamosActivos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo' AND fecha_devolucion < CURDATE()");
$prestamosAtrasados = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

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
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
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
            color: #fff;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .icon-purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .icon-green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .icon-pink { background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); }
        .icon-orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #6b7280;
            font-size: 1rem;
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
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📊 Panel de Control</h1>
        <p>Bienvenido de vuelta, Administrador</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-purple">📚</div>
            <div class="stat-number"><?= $totalLibros ?></div>
            <div class="stat-label">Total de Libros</div>
            <a href="index.php?ruta=libros" class="stat-link">
                Ver Catálogo → 
            </a>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-green">👥</div>
            <div class="stat-number"><?= $totalUsuarios ?></div>
            <div class="stat-label">Usuarios Registrados</div>
            <a href="index.php?ruta=usuarios" class="stat-link">
                Ver Usuarios →
            </a>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-pink">📖</div>
            <div class="stat-number"><?= $prestamosActivos ?></div>
            <div class="stat-label">Préstamos Activos</div>
            <a href="index.php?ruta=prestamos" class="stat-link">
                Ver Activos →
            </a>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-orange">⚠️</div>
            <div class="stat-number"><?= $prestamosAtrasados ?></div>
            <div class="stat-label">Préstamos Atrasados</div>
            <a href="index.php?ruta=prestamos" class="stat-link">
                Ver Atrasados →
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
</body>
</html>