<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Libros - Biblioteca CIF</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 0;
        }
        
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .content-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 0;
            overflow: hidden;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }
        
        .content-body {
            padding: 40px;
        }
        
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            padding: 25px;
            border-radius: 15px;
            color: white;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-card.green {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .stat-card.orange {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .stat-card.purple {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .stat-card h3 {
            font-size: 2.5rem;
            margin: 10px 0 5px 0;
            font-weight: 700;
        }
        
        .stat-card p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .stat-card i {
            font-size: 2rem;
            opacity: 0.8;
        }
        
        .toolbar {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .search-container {
            flex: 1;
            min-width: 300px;
        }
        
        .search-input {
            position: relative;
        }
        
        .search-input i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .search-input input {
            width: 100%;
            padding: 12px 20px 12px 45px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .search-input input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .filter-select {
            padding: 12px 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            min-width: 200px;
        }
        
        .btn-custom {
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }
        
        .custom-table {
            width: 100%;
            margin: 0;
        }
        
        .custom-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .custom-table th {
            padding: 15px;
            font-weight: 600;
            text-align: left;
            white-space: nowrap;
        }
        
        .custom-table td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }
        
        .custom-table tbody tr {
            transition: background 0.3s;
        }
        
        .custom-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-disponible {
            background: #28a745;
            color: white;
        }
        
        .badge-agotado {
            background: #dc3545;
            color: white;
        }
        
        .badge-fisico {
            background: #17a2b8;
            color: white;
        }
        
        .badge-digital {
            background: #6f42c1;
            color: white;
        }
        
        .badge-ambos {
            background: #fd7e14;
            color: white;
        }
        
        .btn-action {
            padding: 8px 12px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.9rem;
            margin: 0 2px;
        }
        
        .btn-ver {
            background: #17a2b8;
            color: white;
        }
        
        .btn-editar {
            background: #ffc107;
            color: #000;
        }
        
        .btn-eliminar {
            background: #dc3545;
            color: white;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 5rem;
            color: #667eea;
            opacity: 0.3;
            margin-bottom: 20px;
        }
        
        .libro-title {
            font-weight: 600;
            color: #212529;
            margin-bottom: 5px;
        }
        
        .libro-subtitle {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .libro-categoria {
            font-size: 0.85rem;
            color: #667eea;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .toolbar {
                flex-direction: column;
            }
            
            .search-container {
                width: 100%;
            }
            
            .filter-select {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="content-card">
        
        <!-- HEADER -->
        <div class="page-header">
            <div>
                <h1><i class="fas fa-book"></i> Catálogo de Libros</h1>
                <p class="mb-0 mt-2" style="opacity: 0.9;">Sistema de Gestión Bibliotecaria CIF</p>
            </div>
            <a href="index.php" class="btn btn-light">
                <i class="fas fa-home"></i> Inicio
            </a>
        </div>
        
        <!-- BODY -->
        <div class="content-body">
            
            <!-- MENSAJES -->
            <?php if (isset($_SESSION['exito'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <strong>¡Éxito!</strong> <?= htmlspecialchars($_SESSION['exito']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['exito']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <strong>Error:</strong> <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <!-- ESTADÍSTICAS -->
            <div class="stats-row">
                <div class="stat-card blue">
                    <i class="fas fa-books"></i>
                    <h3><?= $total_libros ?></h3>
                    <p>Total de Libros</p>
                </div>
                <div class="stat-card green">
                    <i class="fas fa-book-open"></i>
                    <h3><?= $disponibles ?></h3>
                    <p>Copias Disponibles</p>
                </div>
                <div class="stat-card orange">
                    <i class="fas fa-bookmark"></i>
                    <h3><?= count(array_unique(array_column($libros, 'categoria_id'))) ?></h3>
                    <p>Categorías</p>
                </div>
                <div class="stat-card purple">
                    <i class="fas fa-users"></i>
                    <h3><?= $total_libros - $disponibles ?></h3>
                    <p>Prestados</p>
                </div>
            </div>
            
            <!-- TOOLBAR -->
            <div class="toolbar">
                <!-- Búsqueda -->
                <div class="search-container">
                    <div class="search-input">
                        <i class="fas fa-search"></i>
                        <input 
                            type="text" 
                            id="searchInput" 
                            placeholder="Buscar por título, autor, ISBN..."
                            value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
                    </div>
                </div>
                
                <!-- Filtro por Categoría -->
                <select class="filter-select" id="categoriaFilter">
                    <option value="">Todas las Categorías</option>
                    <?php
                    $categorias_unicas = [];
                    foreach ($libros as $libro) {
                        if (!empty($libro['categoria_nombre']) && !in_array($libro['categoria_nombre'], $categorias_unicas)) {
                            $categorias_unicas[] = $libro['categoria_nombre'];
                        }
                    }
                    sort($categorias_unicas);
                    foreach ($categorias_unicas as $cat):
                    ?>
                        <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                    <?php endforeach; ?>
                </select>
                
                <!-- Botón Agregar -->
                <a href="index.php?ruta=libros/crear" class="btn-custom btn-primary-custom">
                    <i class="fas fa-plus-circle"></i> Agregar Libro
                </a>
            </div>
            
            <!-- TABLA DE LIBROS -->
            <?php if (empty($libros)): ?>
                <div class="empty-state">
                    <i class="fas fa-book"></i>
                    <h2>No hay libros registrados</h2>
                    <p class="mb-4">Comienza agregando tu primer libro al catálogo</p>
                    <a href="index.php?ruta=libros/crear" class="btn-custom btn-primary-custom">
                        <i class="fas fa-plus-circle"></i> Agregar Primer Libro
                    </a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="custom-table" id="librosTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ISBN</th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Editorial</th>
                                <th>Categoría</th>
                                <th>Año</th>
                                <th>Tipo</th>
                                <th>Copias</th>
                                <th>Disponibles</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="librosTableBody">
                            <?php foreach ($libros as $libro): ?>
                                <tr data-titulo="<?= htmlspecialchars(strtolower($libro['titulo'])) ?>" 
                                    data-autor="<?= htmlspecialchars(strtolower($libro['autor'])) ?>"
                                    data-isbn="<?= htmlspecialchars(strtolower($libro['isbn'] ?? '')) ?>"
                                    data-categoria="<?= htmlspecialchars($libro['categoria_nombre'] ?? '') ?>">
                                    <td><?= $libro['id'] ?></td>
                                    <td>
                                        <?php if (!empty($libro['isbn'])): ?>
                                            <small class="text-muted"><?= htmlspecialchars($libro['isbn']) ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="libro-title"><?= htmlspecialchars($libro['titulo']) ?></div>
                                        <?php if (!empty($libro['subtitulo'])): ?>
                                            <div class="libro-subtitle"><?= htmlspecialchars($libro['subtitulo']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($libro['autor']) ?></td>
                                    <td>
                                        <?php if (!empty($libro['editorial'])): ?>
                                            <?= htmlspecialchars($libro['editorial']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($libro['categoria_nombre'])): ?>
                                            <span class="libro-categoria">
                                                <i class="fas fa-tag"></i> <?= htmlspecialchars($libro['categoria_nombre']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">Sin categoría</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $libro['anio_publicacion'] ?? '-' ?></td>
                                    <td>
                                        <?php 
                                        $tipo = $libro['tipo'] ?? 'fisico';
                                        $badge_class = $tipo === 'digital' ? 'badge-digital' : ($tipo === 'ambos' ? 'badge-ambos' : 'badge-fisico');
                                        ?>
                                        <span class="badge-custom <?= $badge_class ?>">
                                            <?= ucfirst($tipo) ?>
                                        </span>
                                    </td>
                                    <td class="text-center"><?= $libro['total_copias'] ?></td>
                                    <td class="text-center">
                                        <?php if ($libro['copias_disponibles'] > 0): ?>
                                            <span class="badge-custom badge-disponible">
                                                <?= $libro['copias_disponibles'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-custom badge-agotado">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <button 
                                            class="btn-action btn-ver" 
                                            onclick="verDetalle(<?= $libro['id'] ?>)"
                                            title="Ver Detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a 
                                            href="index.php?ruta=libros/editar&id=<?= $libro['id'] ?>" 
                                            class="btn-action btn-editar"
                                            title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button 
                                            class="btn-action btn-eliminar"
                                            onclick="eliminarLibro(<?= $libro['id'] ?>, '<?= htmlspecialchars(addslashes($libro['titulo'])) ?>')"
                                            title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3 text-muted text-center" id="resultadosInfo">
                    Mostrando <?= count($libros) ?> libros
                </div>
            <?php endif; ?>
            
        </div>
        
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ========================================
// BÚSQUEDA EN TIEMPO REAL
// ========================================
const searchInput = document.getElementById('searchInput');
const categoriaFilter = document.getElementById('categoriaFilter');
const tableBody = document.getElementById('librosTableBody');
const resultadosInfo = document.getElementById('resultadosInfo');

function filtrarTabla() {
    const searchTerm = searchInput.value.toLowerCase();
    const categoriaSeleccionada = categoriaFilter.value;
    const rows = tableBody.getElementsByTagName('tr');
    let visibleCount = 0;
    
    Array.from(rows).forEach(row => {
        const titulo = row.getAttribute('data-titulo') || '';
        const autor = row.getAttribute('data-autor') || '';
        const isbn = row.getAttribute('data-isbn') || '';
        const categoria = row.getAttribute('data-categoria') || '';
        
        const matchSearch = titulo.includes(searchTerm) || 
                          autor.includes(searchTerm) || 
                          isbn.includes(searchTerm);
        
        const matchCategoria = !categoriaSeleccionada || categoria === categoriaSeleccionada;
        
        if (matchSearch && matchCategoria) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    resultadosInfo.textContent = `Mostrando ${visibleCount} de ${rows.length} libros`;
}

searchInput.addEventListener('keyup', filtrarTabla);
categoriaFilter.addEventListener('change', filtrarTabla);

// ========================================
// VER DETALLE DE LIBRO
// ========================================
function verDetalle(id) {
    window.location.href = `index.php?ruta=libros/ver&id=${id}`;
}

// ========================================
// ELIMINAR LIBRO
// ========================================
function eliminarLibro(id, titulo) {
    if (confirm(`¿Estás seguro de eliminar este libro?\n\nTítulo: ${titulo}\n\n Esta acción dará de baja el libro.`)) {
        window.location.href = `index.php?ruta=libros/eliminar&id=${id}`;
    }
}

// ========================================
// AUTO-CERRAR ALERTAS
// ========================================
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>

</body>
</html>