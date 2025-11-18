<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener libros
try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $query = "SELECT * FROM libros ORDER BY titulo ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $libros = [];
}
?>

<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Libros - Biblioteca CIF</title>
    
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
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-nuevo:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .libros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .libro-card {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .libro-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px var(--shadow-color);
            border-color: var(--accent-primary);
        }

        .libro-imagen {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
            background-color: var(--bg-tertiary);
        }

        .libro-titulo {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-primary);
        }

        .libro-autor {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .libro-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
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

        .estado-disponible {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .estado-prestado {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .libro-acciones {
            display: flex;
            gap: 8px;
            margin-top: 15px;
        }

        .btn-accion {
            flex: 1;
            padding: 8px;
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
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1 class="page-title">📚 Gestión de Libros</h1>

        <div class="actions-bar">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Buscar libros..." id="searchInput">
            </div>
            <button class="btn-nuevo" onclick="window.location.href='nuevo_libro.php'">
                <i class="fas fa-plus"></i>
                Nuevo Libro
            </button>
        </div>

        <div class="libros-grid">
            <?php foreach ($libros as $libro): ?>
            <div class="libro-card">
                <img src="<?php echo htmlspecialchars($libro['imagen'] ?? 'assets/img/libro-default.jpg'); ?>" 
                     alt="<?php echo htmlspecialchars($libro['titulo']); ?>" 
                     class="libro-imagen">
                
                <h3 class="libro-titulo"><?php echo htmlspecialchars($libro['titulo']); ?></h3>
                <p class="libro-autor">
                    <i class="fas fa-user"></i> 
                    <?php echo htmlspecialchars($libro['autor']); ?>
                </p>
                <p class="libro-autor">
                    <i class="fas fa-book"></i> 
                    ISBN: <?php echo htmlspecialchars($libro['isbn']); ?>
                </p>

                <div class="libro-info">
                    <span class="estado-badge <?php echo $libro['estado'] === 'disponible' ? 'estado-disponible' : 'estado-prestado'; ?>">
                        <?php echo $libro['estado'] === 'disponible' ? '✅ Disponible' : '📕 Prestado'; ?>
                    </span>
                </div>

                <div class="libro-acciones">
                    <button class="btn-accion btn-editar" onclick="window.location.href='editar_libro.php?id=<?php echo $libro['id']; ?>'">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="btn-accion btn-eliminar" onclick="eliminarLibro(<?php echo $libro['id']; ?>)">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="assets/js/theme_system.js"></script>
    <script>
        function eliminarLibro(id) {
            if (confirm('¿Estás seguro de eliminar este libro?')) {
                window.location.href = 'eliminar_libro.php?id=' + id;
            }
        }

        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.libro-card');
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
