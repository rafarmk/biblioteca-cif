<?php
$pageTitle = 'Gestión de Préstamos - Biblioteca CIF';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="page-container fade-in">
    <div class="content-wrapper">
        <h1 class="page-title">📖 Gestión de Préstamos</h1>
        <p class="page-subtitle">Administra todos los préstamos de libros del sistema</p>

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

        <!-- Toolbar -->
        <div class="toolbar">
            <div class="toolbar-left">
                <a href="index.php?ruta=prestamos&accion=crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Préstamo
                </a>
                <a href="index.php?ruta=prestamos/activos" class="btn btn-success">
                    <i class="fas fa-book-open"></i> Préstamos Activos
                </a>
                <a href="index.php?ruta=prestamos/atrasados" class="btn btn-danger">
                    <i class="fas fa-exclamation-triangle"></i> Atrasados
                </a>
            </div>
            <div class="toolbar-right">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Buscar por usuario o libro..." onkeyup="filtrarPrestamos()">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="row" style="margin-bottom: 30px;">
            <div class="col-4">
                <div class="card">
                    <h3 style="color: var(--primary-color); font-size: 2rem; margin-bottom: 5px;">
                        <?php echo isset($estadisticas['total']) ? (int)$estadisticas['total'] : 0; ?>
                    </h3>
                    <p style="color: var(--text-light); margin: 0;">Total de Préstamos</p>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <h3 style="color: var(--success-color); font-size: 2rem; margin-bottom: 5px;">
                        <?php echo isset($estadisticas['activos']) ? (int)$estadisticas['activos'] : 0; ?>
                    </h3>
                    <p style="color: var(--text-light); margin: 0;">Préstamos Activos</p>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <h3 style="color: var(--danger-color); font-size: 2rem; margin-bottom: 5px;">
                        <?php echo isset($estadisticas['atrasados']) ? (int)$estadisticas['atrasados'] : 0; ?>
                    </h3>
                    <p style="color: var(--text-light); margin: 0;">Préstamos Atrasados</p>
                </div>
            </div>
        </div>

        <!-- Tabla de préstamos -->
        <div class="table-container">
            <table class="table" id="prestamosTable">
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
                    <?php if (!empty($prestamos)): ?>
                        <?php foreach ($prestamos as $prestamo): ?>
                            <?php
                            // --- CÁLCULO DE DÍAS (CORREGIDO) ---
                            $fechaEsperadaStr = $prestamo['fecha_devolucion_esperada'] ?? null;   // si tu modelo la usa
                            // Si no manejas 'fecha_devolucion_esperada', comenta la línea anterior
                            // y usa directamente 'fecha_devolucion' como "esperada" según tu esquema.

                            $fechaRealStr     = $prestamo['fecha_devolucion'] ?? null;            // real si ya se devolvió
                            $fechaPrestamoStr = $prestamo['fecha_prestamo'] ?? null;

                            $diasValor = null;     // firmado (negativo = atrasado, positivo = faltan días)
                            $diasAbs   = null;     // absoluto para mostrar
                            $atrasado  = false;    // flag

                            try {
                                $hoy = new DateTime();

                                // Construir objetos si existen
                                $fechaEsperada = $fechaEsperadaStr ? new DateTime($fechaEsperadaStr) : null;
                                $fechaReal     = $fechaRealStr ? new DateTime($fechaRealStr) : null;

                                if ($fechaEsperada) {
                                    if ($fechaReal) {
                                        // Ya devuelto: diferencia entre esperada y real
                                        $diff = $fechaEsperada->diff($fechaReal);
                                        $diasValor = (int)$diff->format('%r%a');
                                        // Si real > esperada => tardó (negativo si consideras "esperada - real")
                                        // Con esta convención, atrasado si $fechaReal > $fechaEsperada:
                                        $atrasado = ($fechaReal > $fechaEsperada);
                                    } else {
                                        // No devuelto: diferencia entre esperada y hoy
                                        $diff = $fechaEsperada->diff($hoy);
                                        $diasValor = (int)$diff->format('%r%a');
                                        // Si hoy > esperada => atrasado
                                        $atrasado = ($hoy > $fechaEsperada);
                                    }
                                    $diasAbs = abs($diasValor);
                                }
                            } catch (\Throwable $e) {
                                $diasValor = null;
                                $diasAbs   = null;
                                $atrasado  = false;
                            }
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($prestamo['usuario_nombre'] ?? 'N/D', ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                <td><?php echo htmlspecialchars($prestamo['libro_titulo'] ?? 'N/D', ENT_QUOTES, 'UTF-8'); ?></td>

                                <td>
                                    <?php
                                    echo $fechaPrestamoStr
                                        ? date('d/m/Y', strtotime($fechaPrestamoStr))
                                        : 'N/D';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    // Mostramos 'fecha_devolucion' (real) si existe; si no, la esperada si existe.
                                    if (!empty($fechaRealStr)) {
                                        echo date('d/m/Y', strtotime($fechaRealStr));
                                    } elseif (!empty($fechaEsperadaStr)) {
                                        echo date('d/m/Y', strtotime($fechaEsperadaStr));
                                    } else {
                                        echo 'N/D';
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php if (!empty($fechaRealStr)): ?>
                                        <span class="badge badge-secondary">-</span>
                                    <?php elseif ($diasAbs !== null && $atrasado): ?>
                                        <span class="badge badge-danger"><?php echo $diasAbs; ?> día(s) atrasado</span>
                                    <?php elseif ($diasAbs !== null): ?>
                                        <span class="badge badge-success"><?php echo $diasAbs; ?> día(s)</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">N/D</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php
                                    $estado = $prestamo['estado'] ?? 'desconocido';
                                    if ($estado === 'devuelto') {
                                        echo '<span class="badge badge-secondary">Devuelto</span>';
                                    } elseif ($estado === 'activo' && $atrasado) {
                                        echo '<span class="badge badge-danger">Atrasado</span>';
                                    } elseif ($estado === 'activo') {
                                        echo '<span class="badge badge-success">Activo</span>';
                                    } else {
                                        echo '<span class="badge badge-secondary">'.htmlspecialchars($estado, ENT_QUOTES, 'UTF-8').'</span>';
                                    }
                                    ?>
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        <?php if (($prestamo['estado'] ?? '') === 'activo'): ?>
                                            <a href="index.php?ruta=prestamos&accion=devolver&id=<?php echo (int)($prestamo['id'] ?? 0); ?>"
                                               class="action-btn action-btn-edit"
                                               onclick="return confirm('¿Confirmar devolución de este libro?')"
                                               title="Registrar Devolución">
                                                <i class="fas fa-undo"></i> Devolver
                                            </a>
                                        <?php endif; ?>
                                        <a href="index.php?ruta=prestamos&accion=ver&id=<?php echo (int)($prestamo['id'] ?? 0); ?>"
                                           class="action-btn action-btn-view"
                                           title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-light);">
                                <i class="fas fa-book-open" style="font-size: 3rem; margin-bottom: 15px; display: block; opacity: 0.3;"></i>
                                No hay préstamos registrados aún.
                                <a href="index.php?ruta=prestamos&accion=crear" style="color: var(--primary-color);">Registrar el primero</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filtrarPrestamos() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('prestamosTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const tdUsuario = tr[i].getElementsByTagName('td')[0];
        const tdLibro = tr[i].getElementsByTagName('td')[1];

        if (tdUsuario || tdLibro) {
            const txtUsuario = (tdUsuario.textContent || tdUsuario.innerText).toUpperCase();
            const txtLibro   = (tdLibro.textContent   || tdLibro.innerText).toUpperCase();

            tr[i].style.display = (txtUsuario.indexOf(filter) > -1 || txtLibro.indexOf(filter) > -1) ? '' : 'none';
        }
    }
}
</script>

</body>
</html>
