<?php
if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

$usuario_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("
    SELECT 
        p.*,
        l.titulo as libro_titulo,
        l.autor as libro_autor,
        l.isbn,
        DATEDIFF(p.fecha_devolucion, CURDATE()) as dias_restantes
    FROM prestamos p
    INNER JOIN libros l ON p.libro_id = l.id
    WHERE p.usuario_id = ?
    ORDER BY p.fecha_prestamo DESC
");
$stmt->execute([$usuario_id]);
$mis_prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../layouts/navbar.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Préstamos - Biblioteca CIF</title>
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            padding-top: 100px;
            padding-bottom: 150px;
        }

        .container {
            max-width: 1200px;
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

        .prestamo-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .prestamo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .prestamo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
        }

        .libro-titulo {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1f2937;
        }

        .badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-activo {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-devuelto {
            background: #e0e7ff;
            color: #3730a3;
        }

        .badge-atrasado {
            background: #fee2e2;
            color: #991b1b;
        }

        .prestamo-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .info-item {
            color: #4b5563;
        }

        .info-item strong {
            color: #1f2937;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #fff;
        }

        .empty-state-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            opacity: 0.6;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            margin-top: 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📚 Mis Préstamos</h1>
        <p>Historial de libros que has solicitado</p>
    </div>

    <?php if (empty($mis_prestamos)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📖</div>
            <h3>No tienes préstamos registrados</h3>
            <p>Solicita tu primer libro para comenzar</p>
            <a href="index.php?ruta=catalogo" class="btn btn-primary">
                <i class="fas fa-book"></i> Ver Catálogo
            </a>
        </div>
    <?php else: ?>
        <?php foreach ($mis_prestamos as $prestamo): ?>
            <div class="prestamo-card">
                <div class="prestamo-header">
                    <div class="libro-titulo">
                        📖 <?= htmlspecialchars($prestamo['libro_titulo']) ?>
                    </div>
                    <?php if ($prestamo['estado'] == 'activo'): ?>
                        <?php if ($prestamo['dias_restantes'] < 0): ?>
                            <span class="badge badge-atrasado">⚠️ Atrasado</span>
                        <?php else: ?>
                            <span class="badge badge-activo">✅ Activo</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge badge-devuelto">📥 Devuelto</span>
                    <?php endif; ?>
                </div>

                <div class="prestamo-info">
                    <div class="info-item">
                        <strong>Autor:</strong><br>
                        <?= htmlspecialchars($prestamo['libro_autor']) ?>
                    </div>
                    <div class="info-item">
                        <strong>ISBN:</strong><br>
                        <?= htmlspecialchars($prestamo['isbn']) ?>
                    </div>
                    <div class="info-item">
                        <strong>Fecha de préstamo:</strong><br>
                        <?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?>
                    </div>
                    <div class="info-item">
                        <strong>Fecha de devolución:</strong><br>
                        <?= date('d/m/Y', strtotime($prestamo['fecha_devolucion'])) ?>
                    </div>
                    <?php if ($prestamo['estado'] == 'activo'): ?>
                        <div class="info-item">
                            <strong>Días restantes:</strong><br>
                            <?php if ($prestamo['dias_restantes'] < 0): ?>
                                <span style="color: #ef4444; font-weight: 700;">
                                    <?= abs($prestamo['dias_restantes']) ?> días de atraso
                                </span>
                            <?php else: ?>
                                <span style="color: #10b981; font-weight: 700;">
                                    <?= $prestamo['dias_restantes'] ?> días
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>