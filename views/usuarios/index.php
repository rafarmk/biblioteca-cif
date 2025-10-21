<?php
// views/usuarios/index.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Biblioteca CIF</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 1400px;
            margin: 20px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .header h1 {
            color: #333;
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header h1::before {
            content: "👥";
            font-size: 35px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: #48bb78;
            color: white;
            font-size: 12px;
            padding: 8px 16px;
        }

        .btn-success:hover {
            background: #38a169;
        }

        .btn-danger {
            background: #f56565;
            color: white;
            font-size: 12px;
            padding: 8px 16px;
        }

        .btn-danger:hover {
            background: #e53e3e;
        }

        .btn-back {
            background: #718096;
            color: white;
        }

        .btn-back:hover {
            background: #4a5568;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border-left: 4px solid #48bb78;
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border-left: 4px solid #f56565;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .search-input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-card h3 {
            font-size: 32px;
            margin-bottom: 5px;
        }

        .stat-card p {
            font-size: 14px;
            opacity: 0.9;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        thead {
            background: #f7fafc;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            border-bottom: 2px solid #e2e8f0;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            color: #2d3748;
            font-size: 14px;
        }

        tbody tr {
            transition: background 0.2s ease;
        }

        tbody tr:hover {
            background: #f7fafc;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
        }

        .badge-policia {
            background: #bee3f8;
            color: #2c5282;
        }

        .badge-administrativo {
            background: #faf089;
            color: #744210;
        }

        .badge-estudiante {
            background: #c6f6d5;
            color: #22543d;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #718096;
            font-size: 16px;
        }

        .no-data::before {
            content: "📭";
            font-size: 50px;
            display: block;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 10px 8px;
            }

            .actions {
                flex-direction: column;
            }

            .container {
                margin: 10px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<?php include 'views/layouts/navbar.php'; ?>

    <div class="container">
        <div class="header">
            <h1>Gestión de Usuarios</h1>
            <div style="display: flex; gap: 10px;">
                <a href="index.php?ruta=usuarios/crear" class="btn btn-primary">➕ Nuevo Usuario</a>
                <a href="index.php?ruta=home" class="btn btn-back">🏠 Volver al Inicio</a>
            </div>
        </div>

        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['mensaje'])):
            $tipo = isset($_SESSION['mensaje_tipo']) ? $_SESSION['mensaje_tipo'] : 'success';
        ?>
            <div class="alert alert-<?php echo $tipo; ?>">
                <?php echo $tipo === 'success' ? '✅' : '❌'; ?>
                <?php echo $_SESSION['mensaje']; ?>
            </div>
        <?php
            unset($_SESSION['mensaje']);
            unset($_SESSION['mensaje_tipo']);
        endif;
        ?>

        <form action="index.php" method="GET" class="search-bar">
            <input type="hidden" name="ruta" value="usuarios/buscar">
            <input
                type="text"
                name="q"
                class="search-input"
                placeholder="🔍 Buscar por nombre o documento..."
                value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>"
            >
            <button type="submit" class="btn btn-primary">Buscar</button>
            <?php if (isset($_GET['q'])): ?>
                <a href="index.php?ruta=usuarios" class="btn btn-back">Limpiar</a>
            <?php endif; ?>
        </form>

        <div class="stats">
            <?php
            $totalUsuarios = count($usuarios);
            $totalPolicia = count(array_filter($usuarios, fn($u) => $u['tipo'] == 'policia'));
            $totalAdmin = count(array_filter($usuarios, fn($u) => $u['tipo'] == 'administrativo'));
            $totalEstudiantes = count(array_filter($usuarios, fn($u) => $u['tipo'] == 'estudiante'));
            ?>
            <div class="stat-card">
                <h3><?php echo $totalUsuarios; ?></h3>
                <p>Total Usuarios</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $totalPolicia; ?></h3>
                <p>👮 Policías</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $totalAdmin; ?></h3>
                <p>💼 Personal Administrativo</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $totalEstudiantes; ?></h3>
                <p>👨‍🎓 Estudiantes/Visitantes</p>
            </div>
        </div>

        <div class="table-container">
            <?php if (empty($usuarios)): ?>
                <div class="no-data">
                    No se encontraron usuarios
                    <?php if (isset($_GET['q'])): ?>
                        <br><small>para la búsqueda: "<?php echo htmlspecialchars($_GET['q']); ?>"</small>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>DOCUMENTO</th>
                            <th>CARNET/TOKEN</th>
                            <th>TIPO</th>
                            <th>FECHA REGISTRO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><strong>#<?php echo $usuario['id']; ?></strong></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['documento'] ?? '—'); ?></td>
                                <td>
                                    <?php if (!empty($usuario['carnet_policial'])): ?>
                                        <strong><?php echo htmlspecialchars($usuario['carnet_policial']); ?></strong>
                                    <?php elseif (!empty($usuario['token_temporal'])): ?>
                                        <code style="background: #edf2f7; padding: 4px 8px; border-radius: 4px; font-size: 11px;">
                                            <?php echo htmlspecialchars($usuario['token_temporal']); ?>
                                        </code>
                                    <?php else: ?>
                                        <span style="color: #a0aec0;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = 'badge-' . $usuario['tipo'];
                                    $tipoTexto = $tiposUsuario[$usuario['tipo']] ?? $usuario['tipo'];
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo $tipoTexto; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="index.php?ruta=usuarios/editar&id=<?php echo $usuario['id']; ?>"
                                           class="btn btn-success"
                                           title="Editar">
                                            ✏️ Editar
                                        </a>
                                        <a href="index.php?ruta=usuarios/eliminar&id=<?php echo $usuario['id']; ?>"
                                           class="btn btn-danger"
                                           title="Eliminar"
                                           onclick="return confirm('¿Estás seguro de eliminar este usuario?\n\nUsuario: <?php echo htmlspecialchars($usuario['nombre']); ?>');">
                                            🗑️ Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>