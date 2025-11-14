<?php
$pageTitle = 'Mi Biblioteca - Biblioteca CIF';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar_usuario.php'; // Navbar especial para usuarios

// Obtener información del usuario
$usuario_id = $_SESSION['usuario_id'] ?? 0;

require_once __DIR__ . '/../../config/Database.php';
$database = new Database();
$db = $database->getConnection();

// Obtener préstamos activos del usuario
$queryActivos = "SELECT p.*, l.titulo, l.autor, l.isbn
                 FROM prestamos p
                 JOIN libros l ON p.libro_id = l.id
                 WHERE p.usuario_id = :usuario_id AND p.estado = 'activo'
                 ORDER BY p.fecha_devolucion ASC";
$stmtActivos = $db->prepare($queryActivos);
$stmtActivos->bindParam(':usuario_id', $usuario_id);
$stmtActivos->execute();
$prestamos_activos = $stmtActivos->fetchAll(PDO::FETCH_ASSOC);

// Obtener historial de préstamos
$queryHistorial = "SELECT p.*, l.titulo, l.autor
                   FROM prestamos p
                   JOIN libros l ON p.libro_id = l.id
                   WHERE p.usuario_id = :usuario_id
                   ORDER BY p.fecha_prestamo DESC
                   LIMIT 10";
$stmtHistorial = $db->prepare($queryHistorial);
$stmtHistorial->bindParam(':usuario_id', $usuario_id);
$stmtHistorial->execute();
$historial = $stmtHistorial->fetchAll(PDO::FETCH_ASSOC);

// Obtener libros disponibles
$queryLibros = "SELECT * FROM libros WHERE cantidad_disponible > 0 ORDER BY titulo ASC LIMIT 20";
$libros_disponibles = $db->query($queryLibros)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-container fade-in">
    <div class="content-wrapper">
        <h1 class="page-title">📚 Mi Biblioteca</h1>
        <p class="page-subtitle">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></p>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Estadísticas del usuario -->
        <div class="row" style="margin-bottom: 30px;">
            <div class="col-3">
                <div class="card">
                    <h3 style="color: var(--primary-color); font-size: 2rem; margin-bottom: 5px;">
                        <?php echo count($prestamos_activos); ?>
                    </h3>
                    <p style="color: var(--text-light); margin: 0;">Préstamos Activos</p>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <h3 style="color: var(--success-color); font-size: 2rem; margin-bottom: 5px;">
                        <?php echo count($historial); ?>
                    </h3>
                    <p style="color: var(--text-light); margin: 0;">Préstamos Totales</p>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <h3 style="color: var(--warning-color); font-size: 2rem; margin-bottom: 5px;">
                        <?php echo count($libros_disponibles); ?>+
                    </h3>
                    <p style="color: var(--text-light); margin: 0;">Libros Disponibles</p>
                </div>
            </div>
        </div>

        <!-- Préstamos activos -->
        <div class="card mb-4">
            <h2 class="section-title">📖 Mis Préstamos Activos</h2>
            
            <?php if (count($prestamos_activos) > 0): ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Libro</th>
                                <th>Autor</th>
                                <th>Fecha Préstamo</th>
                                <th>Fecha Devolución</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prestamos_activos as $prestamo): ?>
                                <?php
                                $hoy = new DateTime();
                                $fecha_dev = new DateTime($prestamo['fecha_devolucion']);
                                $dias = $hoy->diff($fecha_dev)->days;
                                $atrasado = $hoy > $fecha_dev;
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($prestamo['titulo']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($prestamo['autor']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_prestamo'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_devolucion'])); ?></td>
                                    <td>
                                        <?php if ($atrasado): ?>
                                            <span class="badge badge-danger">Atrasado (<?php echo $dias; ?> días)</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Activo (<?php echo $dias; ?> días)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="text-align: center; padding: 40px; color: var(--text-light);">
                    <i class="fas fa-book-open" style="font-size: 3rem; display: block; margin-bottom: 15px; opacity: 0.3;"></i>
                    No tienes préstamos activos en este momento
                </p>
            <?php endif; ?>
        </div>

        <!-- Libros disponibles -->
        <div class="card">
            <h2 class="section-title">📚 Libros Disponibles</h2>
            <p style="color: var(--text-light); margin-bottom: 20px;">
                Solicita un libro contactando a un bibliotecario o acércate a la biblioteca
            </p>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>ISBN</th>
                            <th>Disponibles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($libros_disponibles as $libro): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($libro['titulo']); ?></strong></td>
                                <td><?php echo htmlspecialchars($libro['autor']); ?></td>
                                <td><?php echo htmlspecialchars($libro['isbn']); ?></td>
                                <td>
                                    <span class="badge badge-success">
                                        <?php echo $libro['cantidad_disponible']; ?> disponible(s)
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>