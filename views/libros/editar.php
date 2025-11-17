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
    <title>Catálogo de Libros - Biblioteca CIF</title>
    <style>
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

.btn-small {
    padding: 6px 14px;
    font-size: 0.85rem;
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
            <?php if ($esOscuro): ?>
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            <?php else: ?>
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            <?php endif; ?>
            min-height: 100vh;
            padding-top: 20px;
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
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
        }

        .search-bar {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
        }

        .search-bar input {
            padding: 14px 20px;
            border-radius: 25px;
            border: none;
            width: 400px;
            font-size: 1rem;
        }

        .libros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .libro-card {
            <?php if ($esOscuro): ?>
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                border: 2px solid #475569;
            <?php else: ?>
                background: white;
                border: 2px solid rgba(255, 255, 255, 0.3);
            <?php endif; ?>
            border-radius: 15px;
            padding: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .libro-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        }

        .libro-titulo {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
            <?php if ($esOscuro): ?>
                color: #e2e8f0;
            <?php else: ?>
                color: #1f2937;
            <?php endif; ?>
        }

        .libro-autor {
            font-size: 1rem;
            margin-bottom: 15px;
            <?php if ($esOscuro): ?>
                color: #94a3b8;
            <?php else: ?>
                color: #6b7280;
            <?php endif; ?>
        }

        .libro-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid <?php echo $esOscuro ? '#475569' : '#e5e7eb'; ?>;
        }

        .libro-disponible {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .disponible {
            background: #d1fae5;
            color: #065f46;
        }

        .no-disponible {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #fff;
        }

        .empty-state-icon {
            font-size: 5rem;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 10px;
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

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            <?php if ($esOscuro): ?>
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            <?php else: ?>
                background: rgba(255, 255, 255, 0.95);
            <?php endif; ?>
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #60a5fa;
            margin-bottom: 5px;
        }

        .stat-label {
            <?php if ($esOscuro): ?>
                color: #cbd5e1;
            <?php else: ?>
                color: #4b5563;
            <?php endif; ?>
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📚 Catálogo de Libros</h1>
        <p>Explora nuestra colección completa</p>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-number"><?= count($libros) ?></div>
            <div class="stat-label">Total de Libros</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?= count(array_filter($libros, fn($l) => $l['cantidad_disponible'] > 0)) ?>
            </div>
            <div class="stat-label">Disponibles</div>
        </div>
    </div>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="🔍 Buscar por título o autor..." onkeyup="buscarLibro()">
    </div>

    <?php if (empty($libros)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <h3>No hay libros en el catálogo</h3>
            <p>Importa libros para comenzar</p>
        </div>
    <?php else: ?>
        <div class="libros-grid" id="librosGrid">
            <?php foreach ($libros as $libro): ?>
                <div class="libro-card" data-titulo="<?= htmlspecialchars($libro['titulo']) ?>" data-autor="<?= htmlspecialchars($libro['autor']) ?>">
                    <div class="libro-titulo"><?= htmlspecialchars($libro['titulo']) ?></div>
                    <div class="libro-autor">📖 <?= htmlspecialchars($libro['autor']) ?></div>
                    
                    <?php if (!empty($libro['categoria_nombre'])): ?>
                        <div class="libro-autor">🏷️ <?= htmlspecialchars($libro['categoria_nombre']) ?></div>
                    <?php endif; ?>
                    
                    <div class="libro-info">
                        <div>
                            <strong>ISBN:</strong> <?= htmlspecialchars($libro['isbn']) ?><br>
                            <strong>Disponibles:</strong> <?= $libro['cantidad_disponible'] ?>/<?= $libro['cantidad_total'] ?>
                        </div>
                        
                        <span class="libro-disponible <?= $libro['cantidad_disponible'] > 0 ? 'disponible' : 'no-disponible' ?>">
                            <?= $libro['cantidad_disponible'] > 0 ? '✅ Disponible' : '❌ No disponible' ?>
                        </span>
                    </div>
                    
                    <div style="margin-top: 15px;">
                        <a href="index.php?ruta=libros&accion=editar&id=<?= $libro['id'] ?>" class="btn btn-primary">
                            ✏️ Editar
                        </a>
                        <?php if ($libro['cantidad_disponible'] > 0): ?>
                            <a href="index.php?ruta=prestamos&accion=crear&libro_id=<?= $libro['id'] ?>" class="btn btn-success">
                                📤 Prestar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function buscarLibro() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toUpperCase();
    const cards = document.querySelectorAll('.libro-card');

    cards.forEach(card => {
        const titulo = card.dataset.titulo.toUpperCase();
        const autor = card.dataset.autor.toUpperCase();
        
        if (titulo.includes(filter) || autor.includes(filter)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>