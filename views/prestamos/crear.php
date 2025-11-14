<?php
if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../layouts/navbar.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Préstamo - Biblioteca CIF</title>
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            padding-top: 80px;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .form-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .form-header h1 {
            font-size: 2rem;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #6b7280;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 12px 18px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            background: white;
        }

        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 14px 30px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 100%;
            margin-top: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #ef4444;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 2px solid #f59e0b;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php?ruta=prestamos" class="btn btn-secondary">← Volver a Préstamos</a>

    <div class="form-card">
        <div class="form-header">
            <h1>📖 Nuevo Préstamo</h1>
            <p>Registra un nuevo préstamo de libro</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                ❌ El libro seleccionado no está disponible
            </div>
        <?php endif; ?>

        <?php if (empty($usuarios)): ?>
            <div class="alert alert-warning">
                ⚠️ No hay usuarios aprobados. <a href="index.php?ruta=usuarios">Ir a Usuarios</a>
            </div>
        <?php endif; ?>

        <?php if (empty($libros)): ?>
            <div class="alert alert-warning">
                ⚠️ No hay libros disponibles. <a href="index.php?ruta=libros">Ir a Libros</a>
            </div>
        <?php endif; ?>

        <?php if (!empty($usuarios) && !empty($libros)): ?>
            <form method="POST" action="index.php?ruta=prestamos&accion=guardar">
                <div class="form-group">
                    <label for="usuario_id">👤 Usuario *</label>
                    <select name="usuario_id" id="usuario_id" required>
                        <option value="">Selecciona un usuario</option>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?= $usuario['id'] ?>">
                                <?= htmlspecialchars($usuario['nombre']) ?> - <?= htmlspecialchars($usuario['email']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="libro_id">📚 Libro *</label>
                    <select name="libro_id" id="libro_id" required>
                        <option value="">Selecciona un libro</option>
                        <?php foreach ($libros as $libro): ?>
                            <option value="<?= $libro['id'] ?>">
                                <?= htmlspecialchars($libro['titulo']) ?> - <?= htmlspecialchars($libro['autor']) ?> (<?= $libro['cantidad_disponible'] ?> disponibles)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_devolucion">📅 Fecha de Devolución *</label>
                    <input type="date" name="fecha_devolucion" id="fecha_devolucion" 
                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>" 
                           value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    ✅ Registrar Préstamo
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>