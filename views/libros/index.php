<?php
if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

$busqueda = $_GET['buscar'] ?? '';
$query = "SELECT * FROM libros WHERE 1=1";

if (!empty($busqueda)) {
    $query .= " AND (titulo LIKE :busqueda OR autor LIKE :busqueda OR isbn LIKE :busqueda)";
}

$query .= " ORDER BY titulo ASC";
$stmt = $conn->prepare($query);

if (!empty($busqueda)) {
    $busquedaParam = "%$busqueda%";
    $stmt->bindParam(':busqueda', $busquedaParam);
}

$stmt->execute();
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../layouts/navbar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Libros - Biblioteca CIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: var(--bg-primary);
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
            color: var(--text-primary);
            text-shadow: 2px 2px 4px var(--shadow);
        }

        .header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px var(--shadow);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
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

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px var(--shadow);
        }

        .btn-small {
            padding: 6px 14px;
            font-size: 0.85rem;
        }

        .search-box {
            max-width: 600px;
            margin: 0 auto 30px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 12px 20px;
            border-radius: 10px;
            border: 2px solid var(--border-color);
            font-size: 1rem;
            background: var(--bg-card);
            color: var(--text-primary);
        }

        .table-container {
            background: var(--bg-card);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 20px var(--shadow);
            overflow-x: auto;
        }

        .table-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        thead {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        tbody tr {
            transition: all 0.3s;
            background: var(--bg-card);
        }

        tbody tr:hover {
            background: var(--bg-secondary);
            transform: scale(1.01);
        }

        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-disponible {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-agotado {
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
            border: 2px solid #10b981;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📚 Gestión de Libros</h1>
        <p>Administra el catálogo completo de la biblioteca</p>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['mensaje']) ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="actions">
        <a href="index.php?ruta=libros&accion=crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Libro
        </a>
        <a href="index.php?ruta=libros&accion=importar" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Importar Excel
        </a>
    </div>

    <div class="search-box">
        <form method="GET" class="search-form">
            <input type="hidden" name="ruta" value="libros">
            <input type="text" name="buscar" class="search-input" 
                   placeholder="🔍 Buscar por título, autor o ISBN..."
                   value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Buscar
            </button>
        </form>
    </div>

    <div class="table-container">
        <div class="table-title">📖 Listado de Libros</div>

        <?php if (empty($libros)): ?>
            <div class="empty-state">
                <div style="font-size: 4rem; margin-bottom: 20px;">📚</div>
                <h3>No se encontraron libros</h3>
                <p>Comienza agregando libros al catálogo</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Editorial</th>
                        <th>Año</th>
                        <th>Disponibles</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($libros as $libro): ?>
                        <tr>
                            <td><?= $libro['id'] ?></td>
                            <td><strong><?= htmlspecialchars($libro['titulo'] ?? 'Sin título') ?></strong></td>
                            <td><?= htmlspecialchars($libro['autor'] ?? 'Sin autor') ?></td>
                            <td><?= htmlspecialchars($libro['isbn'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($libro['editorial'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($libro['anio_publicacion'] ?? 'N/A') ?></td>
                            <td>
                                <?php if ($libro['cantidad_disponible'] > 0): ?>
                                    <span class="badge badge-disponible">
                                        ✅ <?= $libro['cantidad_disponible'] ?> / <?= $libro['cantidad_total'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-agotado">
                                        ❌ Agotado
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?ruta=libros&accion=editar&id=<?= $libro['id'] ?>" 
                                   class="btn btn-primary btn-small">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="index.php?ruta=libros&accion=eliminar&id=<?= $libro['id'] ?>" 
                                   class="btn btn-danger btn-small"
                                   onclick="return confirm('¿Eliminar este libro?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>