<?php
if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

$busqueda = $_GET['buscar'] ?? '';
$query = "SELECT * FROM libros WHERE cantidad_disponible > 0";

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
    <title>Catálogo - Biblioteca CIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            padding-top: 100px;
            padding-bottom: 150px;
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
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 10px;
        }
        .header p {
            color: rgba(255,255,255,0.9);
            font-size: 1.1rem;
        }
        .search-box {
            max-width: 600px;
            margin: 0 auto 40px;
        }
        .search-form {
            display: flex;
            gap: 10px;
        }
        .search-input {
            flex: 1;
            padding: 15px 20px;
            border-radius: 12px;
            border: none;
            font-size: 1rem;
        }
        .btn-search {
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-search:hover {
            transform: translateY(-2px);
        }
        .libros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
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
            min-height: 60px;
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
            font-size: 0.9rem;
            color: #4b5563;
        }
        .badge-disponible {
            background: #d1fae5;
            color: #065f46;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .btn-solicitar {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s;
        }
        .btn-solicitar:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #fff;
        }
        .empty-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            opacity: 0.6;
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
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #ef4444;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📚 Catálogo de Libros</h1>
        <p>Explora nuestra colección disponible</p>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['mensaje']['tipo'] ?? 'success') ?>">
            <?= htmlspecialchars($_SESSION['mensaje']['texto'] ?? '') ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="search-box">
        <form method="GET" class="search-form">
            <input type="hidden" name="ruta" value="catalogo">
            <input type="text" name="buscar" class="search-input" 
                   placeholder="🔍 Buscar por título, autor o ISBN..."
                   value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i> Buscar
            </button>
        </form>
    </div>

    <?php if (empty($libros)): ?>
        <div class="empty-state">
            <div class="empty-icon">📚</div>
            <h3>No se encontraron libros disponibles</h3>
            <p>Intenta con otra búsqueda</p>
        </div>
    <?php else: ?>
        <div class="libros-grid">
            <?php foreach ($libros as $libro): ?>
                <?php
                // Validar y asignar valores con valores por defecto
                $titulo = $libro['titulo'] ?? 'Sin título';
                $autor = $libro['autor'] ?? 'Sin autor';
                $isbn = $libro['isbn'] ?? 'N/A';
                $editorial = $libro['editorial'] ?? 'N/A';
                $anio = $libro['anio_publicacion'] ?? 'N/A';
                $disponible = intval($libro['cantidad_disponible'] ?? 0);
                $total = intval($libro['cantidad_total'] ?? 0);
                $libro_id = intval($libro['id'] ?? 0);
                ?>
                <div class="libro-card">
                    <div class="libro-icon">📖</div>
                    <div class="libro-titulo">
                        <?= htmlspecialchars($titulo) ?>
                    </div>
                    <div class="libro-autor">
                        <i class="fas fa-user"></i> <?= htmlspecialchars($autor) ?>
                    </div>

                    <div class="libro-info">
                        <div class="info-row">
                            <span><strong>ISBN:</strong></span>
                            <span><?= htmlspecialchars($isbn) ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Editorial:</strong></span>
                            <span><?= htmlspecialchars($editorial) ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Año:</strong></span>
                            <span><?= htmlspecialchars($anio) ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Disponibles:</strong></span>
                            <span class="badge-disponible">
                                ✅ <?= $disponible ?> / <?= $total ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($libro_id > 0): ?>
                        <form method="POST" action="index.php?ruta=prestamos&accion=solicitar">
                            <input type="hidden" name="libro_id" value="<?= $libro_id ?>">
                            <button type="submit" class="btn-solicitar">
                                <i class="fas fa-hand-holding-heart"></i> Solicitar Préstamo
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>