<?php
/**
 * ============================================================================
 * PANEL DE CONTROL - BIBLIOTECA CIF
 * Panel administrativo con diseño HORIZONTAL
 * ============================================================================
 */

session_start();
require_once 'config/database.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Administrador Sistema') {
    header('Location: login.php');
    exit();
}

// Obtener estadísticas
try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Total de libros
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM libros");
    $stmt->execute();
    $totalLibros = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Total de usuarios registrados
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM usuarios WHERE estado_cuenta = 'aprobada'");
    $stmt->execute();
    $totalUsuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Préstamos activos
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'");
    $stmt->execute();
    $prestamosActivos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Solicitudes pendientes
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM usuarios WHERE estado_cuenta = 'pendiente'");
    $stmt->execute();
    $solicitudesPendientes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Libros disponibles
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM libros WHERE estado = 'disponible'");
    $stmt->execute();
    $librosDisponibles = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
} catch (Exception $e) {
    error_log("Error al obtener estadísticas: " . $e->getMessage());
    $totalLibros = 0;
    $totalUsuarios = 0;
    $prestamosActivos = 0;
    $solicitudesPendientes = 0;
    $librosDisponibles = 0;
}
?>

<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Biblioteca CIF</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Principal -->
    <link rel="stylesheet" href="assets/css/soluciones_biblioteca_cif.css">
    
    <style>
        /* Reset básico */
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

        /* Contenedor principal */
        .page-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 40px 30px;
        }

        /* Título de la página */
        .page-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .page-title {
            font-size: 48px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 18px;
        }

        /* Iconos grandes para las tarjetas */
        .card-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .icon-books {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .icon-users {
            background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .icon-loans {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .icon-requests {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .icon-available {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .control-card {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
        }

        .control-card:nth-child(1) { animation-delay: 0.1s; }
        .control-card:nth-child(2) { animation-delay: 0.2s; }
        .control-card:nth-child(3) { animation-delay: 0.3s; }
        .control-card:nth-child(4) { animation-delay: 0.4s; }
        .control-card:nth-child(5) { animation-delay: 0.5s; }

        /* Badge de notificación */
        .notification-badge-card {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 12px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Contenedor principal -->
    <div class="page-container">
        <!-- Título de la página -->
        <div class="page-header">
            <h1 class="page-title">📊 Panel de Control</h1>
            <p class="page-subtitle">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></p>
        </div>

        <!-- Panel de control HORIZONTAL -->
        <div class="control-panel-container">
            
            <!-- Tarjeta: Total de Libros -->
            <div class="control-card">
                <div class="card-icon icon-books">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-number"><?php echo number_format($totalLibros); ?></div>
                <div class="stat-title">Total de Libros</div>
                <button class="action-btn" onclick="window.location.href='libros.php'">
                    Ver Libros
                </button>
            </div>

            <!-- Tarjeta: Usuarios Registrados -->
            <div class="control-card">
                <div class="card-icon icon-users">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo number_format($totalUsuarios); ?></div>
                <div class="stat-title">Usuarios Registrados</div>
                <button class="action-btn" onclick="window.location.href='usuarios.php'">
                    Ver Usuarios
                </button>
            </div>

            <!-- Tarjeta: Préstamos Activos -->
            <div class="control-card">
                <div class="card-icon icon-loans">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="stat-number"><?php echo number_format($prestamosActivos); ?></div>
                <div class="stat-title">Préstamos Activos</div>
                <button class="action-btn" onclick="window.location.href='prestamos.php'">
                    Ver Préstamos
                </button>
            </div>

            <!-- Tarjeta: Solicitudes Pendientes -->
            <div class="control-card" style="position: relative;">
                <?php if ($solicitudesPendientes > 0): ?>
                <div class="notification-badge-card"><?php echo $solicitudesPendientes; ?> Nuevas</div>
                <?php endif; ?>
                
                <div class="card-icon icon-requests">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?php echo number_format($solicitudesPendientes); ?></div>
                <div class="stat-title">Solicitudes Pendientes</div>
                <button class="action-btn" onclick="window.location.href='solicitudes.php'">
                    Ver Solicitudes
                </button>
            </div>

            <!-- Tarjeta: Libros Disponibles -->
            <div class="control-card">
                <div class="card-icon icon-available">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?php echo number_format($librosDisponibles); ?></div>
                <div class="stat-title">Libros Disponibles</div>
                <button class="action-btn" onclick="window.location.href='libros.php?filter=disponible'">
                    Ver Disponibles
                </button>
            </div>

        </div>
    </div>

    <!-- JavaScript del sistema de temas -->
    <script src="assets/js/theme_system.js"></script>
</body>
</html>
