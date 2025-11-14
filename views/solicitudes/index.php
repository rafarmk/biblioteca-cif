<?php
if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

$tema = $_SESSION['tema'] ?? 'claro';
$esOscuro = ($tema === 'oscuro');

require_once __DIR__ . '/../layouts/navbar.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes Pendientes - Biblioteca CIF</title>
    <style>
        body {
            <?php if ($esOscuro): ?>
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            <?php else: ?>
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            <?php endif; ?>
            min-height: 100vh;
            padding-top: 20px;
        }

        .container {
            max-width: 1200px;
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

        .solicitudes-container {
            <?php if ($esOscuro): ?>
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                border: 2px solid #475569;
            <?php else: ?>
                background: white;
                border: 2px solid rgba(255, 255, 255, 0.3);
            <?php endif; ?>
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .solicitud-card {
            <?php if ($esOscuro): ?>
                background: #0f172a;
                border: 2px solid #475569;
            <?php else: ?>
                background: #f8fafc;
                border: 2px solid #e2e8f0;
            <?php endif; ?>
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .solicitud-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .solicitud-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .solicitud-nombre {
            font-size: 1.4rem;
            font-weight: 700;
            <?php if ($esOscuro): ?>
                color: #e2e8f0;
            <?php else: ?>
                color: #1f2937;
            <?php endif; ?>
        }

        .solicitud-tipo {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            background: #dbeafe;
            color: #1e40af;
        }

        .solicitud-info {
            <?php if ($esOscuro): ?>
                color: #94a3b8;
            <?php else: ?>
                color: #64748b;
            <?php endif; ?>
            margin-bottom: 15px;
        }

        .solicitud-info div {
            margin-bottom: 8px;
        }

        .solicitud-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: <?php echo $esOscuro ? '#94a3b8' : '#64748b'; ?>;
        }

        .empty-state-icon {
            font-size: 5rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🔔 Solicitudes Pendientes</h1>
        <p>Usuarios esperando aprobación para acceder al sistema</p>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?>">
            <?= $_SESSION['mensaje']['texto'] ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="solicitudes-container">
        <?php if (empty($solicitudes)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">✅</div>
                <h3>No hay solicitudes pendientes</h3>
                <p>Todas las solicitudes han sido procesadas</p>
            </div>
        <?php else: ?>
            <?php foreach ($solicitudes as $solicitud): ?>
                <div class="solicitud-card">
                    <div class="solicitud-header">
                        <div class="solicitud-nombre">
                            <?= htmlspecialchars($solicitud['nombre'] . ' ' . $solicitud['apellido']) ?>
                        </div>
                        <div class="solicitud-tipo">
                            <?= htmlspecialchars(ucwords(str_replace('_', ' ', $solicitud['tipo_usuario']))) ?>
                        </div>
                    </div>
                    
                    <div class="solicitud-info">
                        <div>
                            <strong>📧 Email:</strong> <?= htmlspecialchars($solicitud['email']) ?>
                        </div>
                        <div>
                            <strong>📅 Fecha de solicitud:</strong> 
                            <?= date('d/m/Y H:i', strtotime($solicitud['fecha_registro'])) ?>
                        </div>
                    </div>
                    
                    <div class="solicitud-actions">
                        <a href="index.php?ruta=solicitudes&accion=aprobar&id=<?= $solicitud['id'] ?>" 
                           class="btn btn-success"
                           onclick="return confirm('¿Aprobar esta solicitud?')">
                            ✅ Aprobar
                        </a>
                        <a href="index.php?ruta=solicitudes&accion=rechazar&id=<?= $solicitud['id'] ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('¿Rechazar y eliminar esta solicitud?')">
                            ❌ Rechazar
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>