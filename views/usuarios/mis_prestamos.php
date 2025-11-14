<?php
$pageTitle = 'Mis Préstamos - Biblioteca CIF';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

/** Variables esperadas:
 * $prestamos: array con:
 *  - id, fecha_prestamo, fecha_devolucion_esperada, fecha_devolucion_real, estado, observaciones
 *  - libro_titulo, libro_autor, libro_isbn
 *  - atrasado (bool calculado en el controlador)
 * $stats: ['total','activos','devueltos','atrasados']
 * Filtro GET: estado=(todos|activo|devuelto|atrasado)
 */
$estadoActual = isset($_GET['estado']) ? strtolower((string)$_GET['estado']) : 'todos';
$estadoActual = in_array($estadoActual, ['todos','activo','devuelto','atrasado'], true) ? $estadoActual : 'todos';
?>
<div class="page-container fade-in">
  <div class="content-wrapper">
    <h1 class="page-title">📚 Mis Préstamos</h1>
    <p class="page-subtitle">Consulta el estado de tus préstamos activos, devueltos y atrasados</p>

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

    <!-- Toolbar / Filtro por estado -->
    <div class="toolbar">
      <div class="toolbar-left">
        <a class="btn <?php echo $estadoActual==='todos'?'btn-primary':'btn-secondary'; ?>" href="index.php?ruta=usuario/prestamos&estado=todos">
          <i class="fas fa-list"></i> Todos
        </a>
        <a class="btn <?php echo $estadoActual==='activo'?'btn-primary':'btn-secondary'; ?>" href="index.php?ruta=usuario/prestamos&estado=activo">
          <i class="fas fa-book-open"></i> Activos
        </a>
        <a class="btn <?php echo $estadoActual==='atrasado'?'btn-primary':'btn-secondary'; ?>" href="index.php?ruta=usuario/prestamos&estado=atrasado">
          <i class="fas fa-exclamation-triangle"></i> Atrasados
        </a>
        <a class="btn <?php echo $estadoActual==='devuelto'?'btn-primary':'btn-secondary'; ?>" href="index.php?ruta=usuario/prestamos&estado=devuelto">
          <i class="fas fa-undo"></i> Devueltos
        </a>
      </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row" style="margin-bottom: 30px;">
      <div class="col-3">
        <div class="card">
          <h3 style="color: var(--primary-color); font-size:2rem; margin-bottom:5px;"><?php echo (int)($stats['total'] ?? 0); ?></h3>
          <p style="color: var(--text-light); margin:0;">Total</p>
        </div>
      </div>
      <div class="col-3">
        <div class="card">
          <h3 style="color: var(--success-color); font-size:2rem; margin-bottom:5px;"><?php echo (int)($stats['activos'] ?? 0); ?></h3>
          <p style="color: var(--text-light); margin:0;">Activos</p>
        </div>
      </div>
      <div class="col-3">
        <div class="card">
          <h3 style="color: var(--warning-color); font-size:2rem; margin-bottom:5px;"><?php echo (int)($stats['atrasados'] ?? 0); ?></h3>
          <p style="color: var(--text-light); margin:0;">Atrasados</p>
        </div>
      </div>
      <div class="col-3">
        <div class="card">
          <h3 style="color: var(--text-muted); font-size:2rem; margin-bottom:5px;"><?php echo (int)($stats['devueltos'] ?? 0); ?></h3>
          <p style="color: var(--text-light); margin:0;">Devueltos</p>
        </div>
      </div>
    </div>

    <!-- Tabla -->
    <div class="table-container">
      <table class="table" id="misPrestamosTable">
        <thead>
          <tr>
            <th>Libro</th>
            <th>Autor</th>
            <th>ISBN</th>
            <th>Fecha Préstamo</th>
            <th>Dev. Esperada</th>
            <th>Dev. Real</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
        <?php if (!empty($prestamos)): ?>
          <?php foreach ($prestamos as $p): ?>
            <?php
              $titulo = htmlspecialchars($p['libro_titulo'] ?? 'N/D', ENT_QUOTES, 'UTF-8');
              $autor  = htmlspecialchars($p['libro_autor'] ?? 'N/D', ENT_QUOTES, 'UTF-8');
              $isbn   = htmlspecialchars($p['libro_isbn'] ?? 'N/D', ENT_QUOTES, 'UTF-8');
              $fp     = !empty($p['fecha_prestamo']) ? date('d/m/Y', strtotime($p['fecha_prestamo'])) : 'N/D';
              $fe     = !empty($p['fecha_devolucion_esperada']) ? date('d/m/Y', strtotime($p['fecha_devolucion_esperada'])) : 'N/D';
              $fr     = !empty($p['fecha_devolucion_real']) ? date('d/m/Y', strtotime($p['fecha_devolucion_real'])) : '-';
              $estado = strtolower((string)($p['estado'] ?? 'desconocido'));
              $badge  = 'badge-secondary';
              if ($estado === 'activo' && !empty($p['atrasado'])) { $badge = 'badge-danger'; $estado = 'atrasado'; }
              elseif ($estado === 'activo') { $badge = 'badge-success'; }
              elseif ($estado === 'devuelto') { $badge = 'badge-secondary'; }
            ?>
            <tr>
              <td><strong><?php echo $titulo; ?></strong></td>
              <td><?php echo $autor; ?></td>
              <td><?php echo $isbn; ?></td>
              <td><?php echo $fp; ?></td>
              <td><?php echo $fe; ?></td>
              <td><?php echo $fr; ?></td>
              <td><span class="badge <?php echo $badge; ?>"><?php echo ucfirst($estado); ?></span></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" style="text-align:center; padding:40px; color: var(--text-light);">
              <i class="fas fa-book-open" style="font-size:3rem; margin-bottom:15px; display:block; opacity:.3;"></i>
              No tienes préstamos registrados.
            </td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
