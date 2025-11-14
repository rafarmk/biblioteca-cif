<?php 
// Contar solicitudes pendientes
try {
    require_once __DIR__ . '/../controllers/SolicitudController.php';
    $solicitudController = new SolicitudController();
    $pendientes = $solicitudController->contarPendientes();
} catch (Exception $e) {
    $pendientes = 0;
}

require_once __DIR__ . '/layouts/navbar.php'; 
?>

<!-- NOTIFICACIÓN DE SOLICITUDES PENDIENTES -->
<?php
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador') {
    try {
        require_once __DIR__ . '/../config/Database.php';
        $db_notif = new Database();
        $conn_notif = $db_notif->getConnection();
        
        $stmt_notif = $conn_notif->query("SELECT COUNT(*) FROM solicitudes_registro WHERE estado = 'pendiente'");
        $pendientes_count = $stmt_notif->fetchColumn();
        
        if ($pendientes_count > 0) {
            echo '<a href="index.php?ruta=solicitudes" style="
                position: fixed;
                top: 90px;
                right: 30px;
                background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
                color: white;
                padding: 15px 25px;
                border-radius: 50px;
                text-decoration: none;
                font-weight: 700;
                font-size: 15px;
                z-index: 999999;
                box-shadow: 0 8px 25px rgba(231, 76, 60, 0.7);
                display: flex;
                align-items: center;
                gap: 10px;
                animation: shake-notif 3s infinite;
                transition: all 0.3s ease;
            ">
                <span style="font-size: 20px;">🔔</span>
                <span>' . $pendientes_count . ' Solicitud' . ($pendientes_count > 1 ? 'es' : '') . '</span>
            </a>
            
            <style>
            @keyframes shake-notif {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-3px); }
                75% { transform: translateX(3px); }
            }
            </style>';
        }
    } catch (Exception $e) {
        // Silencioso
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Biblioteca CIF</title>
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: #ffffff;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        [data-theme="premium"] {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: #1e2533;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: rgba(56, 189, 248, 0.2);
            --shadow: rgba(56, 189, 248, 0.2);
        }

        body {
            background: var(--bg-secondary);
            min-height: 100vh;
            padding-top: 0;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
            position: relative;
            z-index: 1;
        }

        .dashboard-header {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: 0 10px 40px var(--shadow);
            text-align: center;
            border: 2px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        [data-theme="premium"] .dashboard-header {
            background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
            border-color: rgba(56, 189, 248, 0.3);
            box-shadow: 0 10px 40px rgba(56, 189, 248, 0.3);
        }

        .dashboard-header h1 {
            font-size: 2.5rem;
            color: var(--text-primary);
            font-weight: 800;
            margin-bottom: 10px;
        }

        .dashboard-header p {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px var(--shadow);
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px var(--shadow);
        }

        .stat-card-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 20px;
        }

        .stat-card-icon.blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card-icon.green {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .stat-card-icon.orange {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stat-card-icon.pink {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .stat-card-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--text-primary);
            margin: 10px 0;
        }

        .stat-card-label {
            font-size: 1rem;
            color: var(--text-secondary);
            font-weight: 600;
            margin-bottom: 20px;
        }

        .stat-card-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .stat-card-link:hover {
            gap: 12px;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>📊 Panel de Control</h1>
        <p>Bienvenido de vuelta, Administrador</p>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-icon blue">📚</div>
            <div class="stat-card-number"><?php echo count($libros ?? []); ?></div>
            <div class="stat-card-label">Total de Libros</div>
            <a href="index.php?ruta=libros" class="stat-card-link">Ver Catálogo →</a>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon green">👥</div>
            <div class="stat-card-number"><?php echo count($usuarios ?? []); ?></div>
            <div class="stat-card-label">Usuarios Registrados</div>
            <a href="index.php?ruta=usuarios" class="stat-card-link">Ver Usuarios →</a>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon orange">📖</div>
            <div class="stat-card-number"><?php echo $stats_prestamos['activos'] ?? 0; ?></div>
            <div class="stat-card-label">Préstamos Activos</div>
            <a href="index.php?ruta=prestamos" class="stat-card-link">Ver Activos →</a>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon pink">⚠️</div>
            <div class="stat-card-number"><?php echo $stats_prestamos['atrasados'] ?? 0; ?></div>
            <div class="stat-card-label">Préstamos Atrasados</div>
            <a href="index.php?ruta=prestamos" class="stat-card-link">Ver Atrasados →</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

</body>
</html>
```

---

**Ahora prueba acceder a:**
```
http://localhost/biblioteca-cif-limpio/index.php?ruta=login