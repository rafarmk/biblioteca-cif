<?php
$pageTitle = 'Catálogo - Biblioteca CIF';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

/** Variables esperadas:
 * $libros: array con (id, isbn, titulo, autor, editorial, cantidad_disponible, cantidad_total, categoria)
 * $pagination: ['current','total','has_prev','has_next','prev','next','total_items','limit']
 * GET q (búsqueda), page
 */
$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
?>
<div class="page-container fade-in">
  <div class="content-wrapper">
    <h1 class="page-title">📖 Catálogo de Libros</h1>
    <p class="page-subtitle">Busca en el catálogo y revisa disponibilidad</p>

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

    <!-- Buscador -->
    <form class="toolbar" method="get" action="index.php">
      <input type="hidden" name="ruta" value="usuario/catalogo">
      <div class="toolbar-left">
        <div class="search-box">
          <input type="text" name="q" value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>"
                 placeholder="Buscar por título, autor o ISBN...">
          <i class="fas fa-search"></i>
        </div>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-search"></i> Buscar
        </button>
        <?php if ($q !== ''): ?>
          <a class="btn btn-secondary" href="index.php?ruta=usuario/catalogo"><i class="fas fa-times"></i> Limpiar</a>
        <?php endif; ?>
      </div>
      <div class="toolbar-right">
        <span style="color:var(--text-light);">
          Resultados: <?php echo (int)($pagination['total_items'] ?? 0); ?>
        </span>
      </div>
    </form>

    <!-- Lista (tabla) -->
    <div class="table-container">
      <table class="table" id="catalogoTable">
        <thead>
          <tr>
            <th>Título</th>
            <th>Autor</th>
            <th>ISBN</th>
            <th>Editorial</th>
            <th>Categoría</th>
            <th>Disponibles</th>
          </tr>
        </thead>
        <tbody>
        <?php if (!empty($libros)): ?>
          <?php foreach ($libros as $l): ?>
            <?php
              $titulo   = htmlspecialchars($l['titulo'] ?? 'N/D', ENT_QUOTES, 'UTF-8');
              $autor    = htmlspecialchars($l['autor'] ?? 'N/D', ENT_QUOTES, 'UTF-8');
              $isbn     = htmlspecialchars($l['isbn'] ?? 'N/D', ENT_QUOTES, 'UTF-8');
              $editorial= htmlspecialchars($l['editorial'] ?? 'N/D', ENT_QUOTES, 'UTF-8');
              $cat      = htmlspecialchars($l['categoria'] ?? 'Sin categoría', ENT_QUOTES, 'UTF-8');
              $disp     = (int)($l['cantidad_disponible'] ?? 0);
            ?>
            <tr>
              <td><strong><?php echo $titulo; ?></strong></td>
              <td><?php echo $autor; ?></td>
              <td><?php echo $isbn; ?></td>
              <td><?php echo $editorial; ?></td>
              <td><span class="badge badge-primary"><?php echo $cat; ?></span></td>
              <td>
                <?php if ($disp > 0): ?>
                  <span class="badge badge-success"><?php echo $disp; ?></span>
                <?php else: ?>
                  <span class="badge badge-danger">0</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" style="text-align:center; padding:40px; color: var(--text-light);">
              <i class="fas fa-book" style="font-size:3rem; margin-bottom:15px; display:block; opacity:.3;"></i>
              No hay resultados para tu búsqueda.
            </td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <?php
      $current = (int)($pagination['current'] ?? 1);
      $total   = (int)($pagination['total'] ?? 1);
      $hasPrev = !empty($pagination['has_prev']);
      $hasNext = !empty($pagination['has_next']);
      $prev    = (int)($pagination['prev'] ?? 1);
      $next    = (int)($pagination['next'] ?? $current);
      $qsBase  = 'index.php?ruta=usuario/catalogo' . ($q !== '' ? '&q=' . urlencode($q) : '');
    ?>
    <div class="pagination" style="display:flex; justify-content:center; gap:10px; margin-top:20px;">
      <a class="btn <?php echo $hasPrev ? 'btn-secondary' : 'btn-disabled'; ?>" 
         href="<?php echo $hasPrev ? $qsBase.'&page='.$prev : 'javascript:void(0)'; ?>">
         « Anterior
      </a>
      <span style="padding:8px 12px; color: var(--text-light);">
        Página <?php echo $current; ?> de <?php echo $total; ?>
      </span>
      <a class="btn <?php echo $hasNext ? 'btn-secondary' : 'btn-disabled'; ?>" 
         href="<?php echo $hasNext ? $qsBase.'&page='.$next : 'javascript:void(0)'; ?>">
         Siguiente »
      </a>
    </div>

  </div>
</div>

</body>
</html>
