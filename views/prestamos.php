<?php
/**
 * ============================================================================
 * GESTIÓN DE PRÉSTAMOS - BIBLIOTECA CIF
 * Con aplicación correcta de temas
 * ============================================================================
 */

session_start();
require_once 'config/database.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener préstamos
try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $query = "SELECT p.*, u.nombre_completo, l.titulo, l.autor 
              FROM prestamos p
              JOIN usuarios u ON p.usuario_id = u.id
              JOIN libros l ON p.libro_id = l.id
              ORDER BY p.fecha_prestamo DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    error_log("Error al obtener préstamos: " . $e->getMessage());
    $prestamos = [];
}
?>

<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Préstamos - Biblioteca CIF</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Principal -->
    <link rel="stylesheet" href="assets/css/soluciones_biblioteca_cif.css">
    
    <style>
        /* IMPORTANTE: Usar variables CSS para que los temas funcionen */
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            background-color: var(--bg-primary); /* ✅ Variable CSS */
            color: var(--text-primary); /* ✅ Variable CSS */
        }

        /* Contenedor principal */
        .prestamos-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 40px 30px;
            background-color: var(--bg-primary); /* ✅ Variable CSS */
        }

        /* Título */
        .page-title {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 30px;
            color: var(--text-primary); /* ✅ Variable CSS */
        }

        /* Barra de acciones */
        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: var(--bg-secondary); /* ✅ Variable CSS */
            border-radius: 12px;
            border: 1px solid var(--border-color); /* ✅ Variable CSS */
        }

        .search-box {
            flex: 1;
            max-width: 400px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 12px 40px 12px 16px;
            border: 1px solid var(--border-color); /* ✅ Variable CSS */
            border-radius: 8px;
            background-color: var(--bg-tertiary); /* ✅ Variable CSS */
            color: var(--text-primary); /* ✅ Variable CSS */
            font-size: 14px;
        }

        .search-input::placeholder {
            color: var(--text-secondary); /* ✅ Variable CSS */
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-primary); /* ✅ Variable CSS */
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-nuevo {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-nuevo:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        /* Tabla de préstamos */
        .prestamos-table-container {
            background-color: var(--bg-secondary); /* ✅ Variable CSS */
            border-radius: 12px;
            border: 1px solid var(--border-color); /* ✅ Variable CSS */
            overflow: hidden;
            box-shadow: 0 4px 20px var(--shadow-color); /* ✅ Variable CSS */
        }

        .prestamos-table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--bg-secondary); /* ✅ Variable CSS */
        }

        .prestamos-table thead {
            background-color: var(--bg-tertiary); /* ✅ Variable CSS */
        }

        .prestamos-table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-primary); /* ✅ Variable CSS */
            border-bottom: 2px solid var(--border-color); /* ✅ Variable CSS */
        }

        .prestamos-table tbody tr {
            border-bottom: 1px solid var(--border-color); /* ✅ Variable CSS */
            transition: background-color 0.2s ease;
        }

        .prestamos-table tbody tr:hover {
            background-color: var(--bg-tertiary); /* ✅ Variable CSS */
        }

        .prestamos-table td {
            padding: 16px;
            color: var(--text-secondary); /* ✅ Variable CSS */
            font-size: 14px;
        }

        /* Estados de préstamo */
        .estado-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .estado-activo {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .estado-vencido {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .estado-devuelto {
            background-color: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        /* Botones de acción */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-devolver {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .btn-devolver:hover {
            background-color: rgba(16, 185, 129, 0.2);
            transform: scale(1.05);
        }

        .btn-renovar {
            background-color: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .btn-renovar:hover {
            background-color: rgba(99, 102, 241, 0.2);
            transform: scale(1.05);
        }

        .btn-detalle {
            background-color: var(--bg-tertiary); /* ✅ Variable CSS */
            color: var(--text-primary); /* ✅ Variable CSS */
            border: 1px solid var(--border-color); /* ✅ Variable CSS */
        }

        .btn-detalle:hover {
            background-color: var(--bg-primary); /* ✅ Variable CSS */
            transform: scale(1.05);
        }

        /* Mensaje vacío */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary); /* ✅ Variable CSS */
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--text-primary); /* ✅ Variable CSS */
        }

        .empty-state p {
            font-size: 16px;
            color: var(--text-secondary); /* ✅ Variable CSS */
        }

        /* Estadísticas */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: var(--bg-secondary); /* ✅ Variable CSS */
            border: 1px solid var(--border-color); /* ✅ Variable CSS */
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }

        .stat-card .stat-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .stat-card .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary); /* ✅ Variable CSS */
            margin-bottom: 5px;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: var(--text-secondary); /* ✅ Variable CSS */
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Contenedor principal -->
    <div class="prestamos-container">
        
        <!-- Título -->
        <h1 class="page-title">📚 Gestión de Préstamos</h1>

        <!-- Estadísticas -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon">📖</div>
                <div class="stat-number"><?php echo count($prestamos); ?></div>
                <div class="stat-label">Total Préstamos</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-number">
                    <?php echo count(array_filter($prestamos, fn($p) => $p['estado'] === 'activo')); ?>
                </div>
                <div class="stat-label">Activos</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">⏰</div>
                <div class="stat-number">
                    <?php echo count(array_filter($prestamos, fn($p) => $p['estado'] === 'vencido')); ?>
                </div>
                <div class="stat-label">Vencidos</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">🔄</div>
                <div class="stat-number">
                    <?php echo count(array_filter($prestamos, fn($p) => $p['estado'] === 'devuelto')); ?>
                </div>
                <div class="stat-label">Devueltos</div>
            </div>
        </div>

        <!-- Barra de acciones -->
        <div class="actions-bar">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Buscar préstamos..." id="searchInput">
            </div>
            <button class="btn-nuevo" onclick="window.location.href='nuevo_prestamo.php'">
                <i class="fas fa-plus"></i>
                Nuevo Préstamo
            </button>
        </div>

        <!-- Tabla de préstamos -->
        <div class="prestamos-table-container">
            <?php if (count($prestamos) > 0): ?>
            <table class="prestamos-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Libro</th>
                        <th>Autor</th>
                        <th>Fecha Préstamo</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prestamos as $prestamo): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($prestamo['id']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['nombre_completo']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['autor']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_prestamo'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_devolucion'])); ?></td>
                        <td>
                            <?php
                            $estadoClass = '';
                            $estadoIcon = '';
                            switch($prestamo['estado']) {
                                case 'activo':
                                    $estadoClass = 'estado-activo';
                                    $estadoIcon = '✅';
                                    break;
                                case 'vencido':
                                    $estadoClass = 'estado-vencido';
                                    $estadoIcon = '⏰';
                                    break;
                                case 'devuelto':
                                    $estadoClass = 'estado-devuelto';
                                    $estadoIcon = '🔄';
                                    break;
                            }
                            ?>
                            <span class="estado-badge <?php echo $estadoClass; ?>">
                                <span><?php echo $estadoIcon; ?></span>
                                <span><?php echo ucfirst($prestamo['estado']); ?></span>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <?php if ($prestamo['estado'] === 'activo'): ?>
                                <button class="btn-action btn-devolver" onclick="devolverPrestamo(<?php echo $prestamo['id']; ?>)">
                                    <i class="fas fa-check"></i> Devolver
                                </button>
                                <button class="btn-action btn-renovar" onclick="renovarPrestamo(<?php echo $prestamo['id']; ?>)">
                                    <i class="fas fa-redo"></i> Renovar
                                </button>
                                <?php endif; ?>
                                <button class="btn-action btn-detalle" onclick="verDetalle(<?php echo $prestamo['id']; ?>)">
                                    <i class="fas fa-eye"></i> Detalle
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No hay préstamos registrados</h3>
                <p>Comienza registrando un nuevo préstamo</p>
            </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- JavaScript del sistema de temas - CRÍTICO -->
    <script src="assets/js/theme_system.js"></script>

    <!-- JavaScript funcional -->
    <script>
        // Búsqueda en tiempo real
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.prestamos-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Funciones de acción
        function devolverPrestamo(id) {
            if (confirm('¿Confirmar devolución del préstamo #' + id + '?')) {
                window.location.href = 'procesar_devolucion.php?id=' + id;
            }
        }

        function renovarPrestamo(id) {
            if (confirm('¿Renovar el préstamo #' + id + '?')) {
                window.location.href = 'procesar_renovacion.php?id=' + id;
            }
        }

        function verDetalle(id) {
            window.location.href = 'detalle_prestamo.php?id=' + id;
        }
    </script>
</body>
</html>
