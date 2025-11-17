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
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            padding-top: 100px;
            padding-bottom: 50px;
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
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .search-form {
            max-width: 600px;
            margin: 0 auto 30px;
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 15px 20px;
            border-radius: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.9);
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
        }

        .libros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }

        .libro-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
        }

        .libro-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .libro-icon {
            font-size: 3rem;
            text-align: center;
            margin-bottom: 15px;
        }

        .libro-titulo {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #1f2937;
        }

        .libro-autor {
            color: #6b7280;
            margin-bottom: 15px;
        }

        .libro-info {
            margin: 15px 0;
            padding: 15px 0;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            color: #4b5563;
            font-size: 0.9rem;
        }

        .badge-disponible {
            background: #d1fae5;
            color: #065f46;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-agotado {
            background: #fee2e2;
            color: #991b1b;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .libro-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-small {
            padding: 8px 16px;
            font-size: 0.85rem;
            flex: 1;
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
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📚 Gestión de Libros</h1>
        <p style="color: rgba(255,255,255,0.9);">Administra el catálogo completo de la biblioteca</p>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['mensaje'] ?>
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

    <form method="GET" action="" class="search-form">
        <input type="hidden" name="ruta" value="libros">
        <input 
            type="text" 
            name="buscar" 
            class="search-input" 
            placeholder="🔍 Buscar por título, autor o ISBN..."
            value="<?= htmlspecialchars($busqueda) ?>"
        >
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Buscar
        </button>
    </form>

    <div class="libros-grid">
        <?php foreach ($libros as $libro): ?>
            <div class="libro-card">
                <div class="libro-icon">📖</div>
                <div class="libro-titulo">
                    <?= htmlspecialchars($libro['titulo']) ?>
                </div>
                <div class="libro-autor">
                    <i class="fas fa-user"></i> <?= htmlspecialchars($libro['autor']) ?>
                </div>

                <div class="libro-info">
                    <div class="info-row">
                        <span><strong>ISBN:</strong></span>
                        <span><?= htmlspecialchars($libro['isbn']) ?></span>
                    </div>
                    <div class="info-row">
                        <span><strong>Editorial:</strong></span>
                        <span><?= htmlspecialchars($libro['editorial']) ?></span>
                    </div>
                    <div class="info-row">
                        <span><strong>Año:</strong></span>
                        <span><?= htmlspecialchars($libro['anio_publicacion']) ?></span>
                    </div>
                    <div class="info-row">
                        <span><strong>Total:</strong></span>
                        <span><?= $libro['cantidad_total'] ?> copias</span>
                    </div>
                    <div class="info-row">
                        <span><strong>Disponibles:</strong></span>
                        <?php if ($libro['cantidad_disponible'] > 0): ?>
                            <span class="badge-disponible">
                                ✅ <?= $libro['cantidad_disponible'] ?>
                            </span>
                        <?php else: ?>
                            <span class="badge-agotado">❌ Agotado</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="libro-actions">
                    <a href="index.php?ruta=libros&accion=editar&id=<?= $libro['id'] ?>" 
                       class="btn btn-primary btn-small">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="index.php?ruta=libros&accion=eliminar&id=<?= $libro['id'] ?>" 
                       class="btn btn-danger btn-small"
                       onclick="return confirm('¿Eliminar este libro?')"
                       style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                        <i class="fas fa-trash"></i> Eliminar
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>