<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Administrador Sistema') {
    header('Location: login.php');
    exit();
}

// Obtener solicitudes pendientes
try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $query = "SELECT * FROM usuarios WHERE estado_cuenta = 'pendiente' ORDER BY fecha_registro DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $solicitudes = [];
}
?>

<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes Pendientes - Biblioteca CIF</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/soluciones_biblioteca_cif.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            background-color: var(--bg-primary);
            color: var(--text-primary);
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 40px 30px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 36px;
            font-weight: 800;
            color: var(--text-primary);
        }

        .solicitudes-count {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 18px;
        }

        .solicitudes-grid {
            display: grid;
            gap: 20px;
        }

        .solicitud-card {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            transition: all 0.3s ease;
        }

        .solicitud-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px var(--shadow-color);
            border-color: var(--accent-primary);
        }

        .solicitud-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 20px;
        }

        .solicitud-info h3 {
            font-size: 20px;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .solicitud-email {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .solicitud-fecha {
            background-color: var(--bg-tertiary);
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            color: var(--text-secondary);
        }

        .solicitud-detalles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
            padding: 20px;
            background-color: var(--bg-tertiary);
            border-radius: 8px;
        }

        .detalle-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .detalle-label {
            font-size: 12px;
            text-transform: uppercase;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .detalle-value {
            font-size: 14px;
            color: var(--text-primary);
        }

        .tipo-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            background-color: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .solicitud-acciones {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .btn-accion {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-aprobar {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-aprobar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
        }

        .btn-rechazar {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-rechazar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4);
        }

        .btn-detalle {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-detalle:hover {
            background-color: var(--bg-primary);
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-state i {
            font-size: 80px;
            color: var(--text-secondary);
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 24px;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">🔔 Solicitudes Pendientes</h1>
            <?php if (count($solicitudes) > 0): ?>
            <div class="solicitudes-count">
                <?php echo count($solicitudes); ?> Pendientes
            </div>
            <?php endif; ?>
        </div>

        <?php if (count($solicitudes) > 0): ?>
        <div class="solicitudes-grid">
            <?php foreach ($solicitudes as $solicitud): ?>
            <div class="solicitud-card">
                <div class="solicitud-header">
                    <div class="solicitud-info">
                        <h3><?php echo htmlspecialchars($solicitud['nombre_completo']); ?></h3>
                        <p class="solicitud-email">
                            <i class="fas fa-envelope"></i>
                            <?php echo htmlspecialchars($solicitud['email']); ?>
                        </p>
                    </div>
                    <div class="solicitud-fecha">
                        <i class="fas fa-calendar"></i>
                        <?php echo date('d/m/Y H:i', strtotime($solicitud['fecha_registro'])); ?>
                    </div>
                </div>

                <div class="solicitud-detalles">
                    <div class="detalle-item">
                        <span class="detalle-label">Tipo de Usuario</span>
                        <span class="tipo-badge">
                            <?php echo htmlspecialchars($solicitud['tipo_usuario']); ?>
                        </span>
                    </div>

                    <div class="detalle-item">
                        <span class="detalle-label">Teléfono</span>
                        <span class="detalle-value">
                            <i class="fas fa-phone"></i>
                            <?php echo htmlspecialchars($solicitud['telefono'] ?? 'No proporcionado'); ?>
                        </span>
                    </div>

                    <div class="detalle-item">
                        <span class="detalle-label">Dirección</span>
                        <span class="detalle-value">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($solicitud['direccion'] ?? 'No proporcionada'); ?>
                        </span>
                    </div>

                    <div class="detalle-item">
                        <span class="detalle-label">DUI</span>
                        <span class="detalle-value">
                            <i class="fas fa-id-card"></i>
                            <?php echo htmlspecialchars($solicitud['dui'] ?? 'No proporcionado'); ?>
                        </span>
                    </div>
                </div>

                <div class="solicitud-acciones">
                    <button class="btn-accion btn-aprobar" onclick="aprobarSolicitud(<?php echo $solicitud['id']; ?>, '<?php echo htmlspecialchars($solicitud['nombre_completo']); ?>')">
                        <i class="fas fa-check-circle"></i>
                        Aprobar
                    </button>
                    <button class="btn-accion btn-rechazar" onclick="rechazarSolicitud(<?php echo $solicitud['id']; ?>, '<?php echo htmlspecialchars($solicitud['nombre_completo']); ?>')">
                        <i class="fas fa-times-circle"></i>
                        Rechazar
                    </button>
                    <button class="btn-accion btn-detalle" onclick="verDetalle(<?php echo $solicitud['id']; ?>)">
                        <i class="fas fa-eye"></i>
                        Ver Detalle
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-check-circle"></i>
            <h3>No hay solicitudes pendientes</h3>
            <p>Todas las solicitudes han sido procesadas</p>
        </div>
        <?php endif; ?>
    </div>

    <script src="assets/js/theme_system.js"></script>
    <script>
        function aprobarSolicitud(id, nombre) {
            if (confirm(`¿Aprobar la solicitud de ${nombre}?`)) {
                window.location.href = `aprobar_solicitud.php?id=${id}`;
            }
        }

        function rechazarSolicitud(id, nombre) {
            if (confirm(`¿Rechazar la solicitud de ${nombre}?`)) {
                const motivo = prompt('Motivo del rechazo (opcional):');
                window.location.href = `rechazar_solicitud.php?id=${id}&motivo=${encodeURIComponent(motivo || '')}`;
            }
        }

        function verDetalle(id) {
            window.location.href = `detalle_solicitud.php?id=${id}`;
        }
    </script>
</body>
</html>
