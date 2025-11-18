<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Administrador Sistema') {
    header('Location: login.php');
    exit();
}

// Obtener usuarios
try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $query = "SELECT * FROM usuarios ORDER BY fecha_registro DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $usuarios = [];
}
?>

<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Biblioteca CIF</title>
    
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

        .page-title {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 30px;
            color: var(--text-primary);
        }

        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .search-box {
            flex: 1;
            max-width: 400px;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
            font-size: 14px;
        }

        .search-input::placeholder {
            color: var(--text-secondary);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-primary);
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
        }

        .btn-nuevo:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .table-container {
            background-color: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .usuarios-table {
            width: 100%;
            border-collapse: collapse;
        }

        .usuarios-table thead {
            background-color: var(--bg-tertiary);
        }

        .usuarios-table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            color: var(--text-primary);
            border-bottom: 2px solid var(--border-color);
        }

        .usuarios-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s ease;
        }

        .usuarios-table tbody tr:hover {
            background-color: var(--bg-tertiary);
        }

        .usuarios-table td {
            padding: 16px;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .estado-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .estado-aprobada {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .estado-pendiente {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .estado-rechazada {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .rol-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
        }

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

        .btn-editar {
            background-color: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .btn-editar:hover {
            background-color: rgba(99, 102, 241, 0.2);
        }

        .btn-eliminar {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-eliminar:hover {
            background-color: rgba(239, 68, 68, 0.2);
        }

        .btn-aprobar {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .btn-aprobar:hover {
            background-color: rgba(16, 185, 129, 0.2);
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1 class="page-title">👥 Gestión de Usuarios</h1>

        <div class="actions-bar">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Buscar usuarios..." id="searchInput">
            </div>
            <button class="btn-nuevo" onclick="window.location.href='nuevo_usuario.php'">
                <i class="fas fa-plus"></i>
                Nuevo Usuario
            </button>
        </div>

        <div class="table-container">
            <table class="usuarios-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th>Tipo Usuario</th>
                        <th>Estado Cuenta</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($usuario['id']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre_completo']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td>
                            <span class="rol-badge">
                                <?php echo htmlspecialchars($usuario['tipo_usuario']); ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            $estadoClass = '';
                            switch($usuario['estado_cuenta']) {
                                case 'aprobada':
                                    $estadoClass = 'estado-aprobada';
                                    $icon = '✅';
                                    break;
                                case 'pendiente':
                                    $estadoClass = 'estado-pendiente';
                                    $icon = '⏰';
                                    break;
                                case 'rechazada':
                                    $estadoClass = 'estado-rechazada';
                                    $icon = '❌';
                                    break;
                            }
                            ?>
                            <span class="estado-badge <?php echo $estadoClass; ?>">
                                <?php echo $icon . ' ' . ucfirst($usuario['estado_cuenta']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                        <td>
                            <div class="action-buttons">
                                <?php if ($usuario['estado_cuenta'] === 'pendiente'): ?>
                                <button class="btn-action btn-aprobar" onclick="aprobarUsuario(<?php echo $usuario['id']; ?>)">
                                    <i class="fas fa-check"></i> Aprobar
                                </button>
                                <?php endif; ?>
                                <button class="btn-action btn-editar" onclick="window.location.href='editar_usuario.php?id=<?php echo $usuario['id']; ?>'">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action btn-eliminar" onclick="eliminarUsuario(<?php echo $usuario['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="assets/js/theme_system.js"></script>
    <script>
        function aprobarUsuario(id) {
            if (confirm('¿Aprobar este usuario?')) {
                window.location.href = 'aprobar_usuario.php?id=' + id;
            }
        }

        function eliminarUsuario(id) {
            if (confirm('¿Estás seguro de eliminar este usuario?')) {
                window.location.href = 'eliminar_usuario.php?id=' + id;
            }
        }

        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.usuarios-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
