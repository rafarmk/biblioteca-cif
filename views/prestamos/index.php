<?php
if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

// Detectar tema actual
$tema = $_SESSION['tema'] ?? 'claro';
$esOscuro = ($tema === 'oscuro');

require_once __DIR__ . '/../layouts/navbar.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Préstamos - Biblioteca CIF</title>
    <style>
        body {
            <?php if ($esOscuro): ?>
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            <?php else: ?>
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            <?php endif; ?>
            min-height: 100vh;
            padding-top: 80px;
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
            margin-bottom: 10px;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            font-weight: 700;
        }

        .header p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.1rem;
            font-weight: 500;
        }

        .actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 32px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
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
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            <?php if ($esOscuro): ?>
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                border: 2px solid #475569;
            <?php else: ?>
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border: 2px solid rgba(102, 126, 234, 0.2);
            <?php endif; ?>
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1rem;
            <?php if ($esOscuro): ?>
                color: #cbd5e1;
            <?php else: ?>
                color: #334155;
            <?php endif; ?>
            font-weight: 600;
        }

        .search-bar {
            margin-bottom: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .search-bar input {
            padding: 12px 20px;
            border-radius: 25px;
            width: 300px;
            font-size: 1rem;
            <?php if ($esOscuro): ?>
                background: #1e293b;
                color: #fff;
                border: 2px solid #475569;
            <?php else: ?>
                background: white;
                color: #1f2937;
                border: 2px solid #e2e8f0;
            <?php endif; ?>
        }

        .table-container {
            <?php if ($esOscuro): ?>
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                border: 2px solid #475569;
            <?php else: ?>
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border: 2px solid rgba(102, 126, 234, 0.1);
            <?php endif; ?>
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 1rem;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid <?php echo $esOscuro ? '#475569' : '#e5e7eb'; ?>;
            color: <?php echo $esOscuro ? '#e2e8f0' : '#1f2937'; ?>;
            font-weight: 500;
        }

        tr:hover {
            background: <?php echo $esOscuro ? 'rgba(51, 65, 85, 0.5)' : '#f0f9ff'; ?>;
        }

        .badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: <?php echo $esOscuro ? '#94a3b8' : '#64748b'; ?>;
        }

        .empty-state-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: <?php echo $esOscuro ? '#cbd5e1' : '#475569'; ?>;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .empty-state a {
            color: #667eea;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.875rem;
            border-radius: 15px;
            margin: 2px;
        }

        .mensaje {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            padding: 18px 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 700;
            font-size: 1.1rem;
            border: 3px solid #10b981;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📚 Gestión de Préstamos</h1>
        <p>Administra todos los préstamos de libros del sistema</p>
    </div>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="mensaje">
            <?php
            switch($_GET['mensaje']) {
                case 'creado':
                    echo '✅ Préstamo creado exitosamente';
                    break;
                case 'devuelto':
                    echo '✅ Libro devuelto exitosamente';
                    break;
            }
            ?>
        </div>
    <?php endif; ?>

    <div class="actions">
        <button onclick="window.location.href='index.php?ruta=prestamos&accion=crear'" class="btn btn-primary">
            ➕ Nuevo Préstamo
        </button>
        <button onclick="window.location.href='index.php?ruta=prestamos&accion=activos'" class="btn btn-success">
            📖 Préstamos Activos
        </button>
        <button onclick="window.location.href='index.php?ruta=prestamos&accion=atrasados'" class="btn btn-danger">
            ⚠️ Atrasados
        </button>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-number" style="color: #60a5fa;"><?= $totalPrestamos ?></div>
            <div class="stat-label">Total de Préstamos</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #34d399;"><?= $prestamosActivos ?></div>
            <div class="stat-label">Préstamos Activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #f87171;"><?= $prestamosAtrasados ?></div>
            <div class="stat-label">Préstamos Atrasados</div>
        </div>
    </div>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="🔍 Buscar por usuario o libro..." onkeyup="buscarPrestamo()">
    </div>

    <div class="table-container">
        <?php if (empty($prestamos)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📚</div>
                <h3>No hay préstamos registrados aún</h3>
                <p><span onclick="window.location.href='index.php?ruta=prestamos&accion=crear'" style="cursor:pointer;">Registrar el primero</span></p>
            </div>
        <?php else: ?>
            <table id="prestamosTable">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Libro</th>
                        <th>Fecha Préstamo</th>
                        <th>Fecha Devolución</th>
                        <th>Días Restantes</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prestamos as $prestamo): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($prestamo['usuario_nombre'] ?? 'Sin nombre') ?></strong></td>
                            <td><?= htmlspecialchars($prestamo['libro_titulo'] ?? 'Sin título') ?></td>
                            <td>
                                <?php 
                                if (!empty($prestamo['fecha_prestamo'])) {
                                    echo date('d/m/Y', strtotime($prestamo['fecha_prestamo']));
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if (!empty($prestamo['fecha_devolucion'])) {
                                    echo date('d/m/Y', strtotime($prestamo['fecha_devolucion']));
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($prestamo['estado'] == 'activo' && isset($prestamo['dias_restantes'])): ?>
                                    <?php if ($prestamo['dias_restantes'] < 0): ?>
                                        <span class="badge badge-danger"><?= abs($prestamo['dias_restantes']) ?> días de atraso</span>
                                    <?php elseif ($prestamo['dias_restantes'] <= 3): ?>
                                        <span class="badge badge-warning"><?= $prestamo['dias_restantes'] ?> días</span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><?= $prestamo['dias_restantes'] ?> días</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span>-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($prestamo['estado'] == 'activo'): ?>
                                    <span class="badge badge-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Devuelto</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button onclick="window.location.href='index.php?ruta=prestamos&accion=ver&id=<?= $prestamo['id'] ?>'" class="btn btn-primary btn-sm">Ver</button>
                                <?php if ($prestamo['estado'] == 'activo'): ?>
                                    <button onclick="if(confirm('¿Confirmar devolución?')) window.location.href='index.php?ruta=prestamos&accion=devolver&id=<?= $prestamo['id'] ?>'" class="btn btn-success btn-sm">Devolver</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script>
function buscarPrestamo() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('prestamosTable');
    if (!table) return;
    
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const td1 = tr[i].getElementsByTagName('td')[0];
        const td2 = tr[i].getElementsByTagName('td')[1];
        if (td1 || td2) {
            const txtValue1 = td1.textContent || td1.innerText;
            const txtValue2 = td2.textContent || td2.innerText;
            if (txtValue1.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>