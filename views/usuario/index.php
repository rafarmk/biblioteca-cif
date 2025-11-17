<?php
if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->query("
    SELECT * FROM usuarios 
    WHERE tipo_usuario != 'admin'
    ORDER BY created_at DESC
");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../layouts/navbar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Biblioteca CIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            padding-top: 100px;
            padding-bottom: 150px;
        }
        .container {
            max-width: 1600px;
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
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .actions {
            text-align: center;
            margin-bottom: 30px;
        }
        .btn {
            padding: 12px 30px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            margin: 5px;
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
        .btn-small {
            padding: 6px 14px;
            font-size: 0.85rem;
        }
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
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
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
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
        .badge-pendiente {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-inactivo {
            background: #fee2e2;
            color: #991b1b;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>👥 Gestión de Usuarios</h1>
        <p style="color: rgba(255,255,255,0.9);">Administra todos los usuarios del sistema</p>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['mensaje'] ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="actions">
        <a href="index.php?ruta=usuarios&accion=crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px;">
                            No hay usuarios registrados
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($usuario['nombre'] ?? '') ?> <?= htmlspecialchars($usuario['apellido'] ?? '') ?></strong>
                            </td>
                            <td><?= htmlspecialchars($usuario['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($usuario['telefono'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($usuario['tipo_usuario'] ?? 'N/A') ?></td>
                            <td>
                                <?php if ($usuario['estado'] == 'activo'): ?>
                                    <span class="badge badge-activo">✅ Activo</span>
                                <?php elseif ($usuario['estado'] == 'pendiente'): ?>
                                    <span class="badge badge-pendiente">⏳ Pendiente</span>
                                <?php else: ?>
                                    <span class="badge badge-inactivo">❌ Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?ruta=usuarios&accion=editar&id=<?= $usuario['id'] ?>" 
                                   class="btn btn-primary btn-small">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="index.php?ruta=usuarios&accion=eliminar&id=<?= $usuario['id'] ?>" 
                                   class="btn btn-danger btn-small"
                                   onclick="return confirm('¿Eliminar este usuario?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>