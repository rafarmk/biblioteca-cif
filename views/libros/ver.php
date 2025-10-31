<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Libro - Biblioteca CIF</title>
    
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
        
        .detail-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .detail-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .detail-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 40px;
        }
        
        .detail-header h1 {
            margin: 0 0 10px 0;
            font-size: 2rem;
            font-weight: 700;
        }
        
        .detail-header p {
            margin: 0;
            opacity: 0.9;
        }
        
        .detail-body {
            padding: 40px;
        }
        
        .book-cover-section {
            display: flex;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .book-cover {
            flex-shrink: 0;
            width: 250px;
            height: 350px;
            background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #e9ecef;
        }
        
        .book-cover i {
            font-size: 5rem;
            color: #667eea;
            opacity: 0.3;
        }
        
        .book-info {
            flex: 1;
        }
        
        .book-title {
            font-size: 2rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 10px;
        }
        
        .book-subtitle {
            font-size: 1.3rem;
            color: #6c757d;
            margin-bottom: 20px;
        }
        
        .book-author {
            font-size: 1.2rem;
            color: #495057;
            margin-bottom: 30px;
        }
        
        .book-author i {
            color: #667eea;
            margin-right: 10px;
        }
        
        .info-section {
            margin-bottom: 40px;
        }
        
        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .info-item {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        
        .info-label {
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .info-value {
            font-size: 1.1rem;
            color: #212529;
            font-weight: 600;
        }
        
        .badge-custom {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
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
        
        .description-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            line-height: 1.8;
            color: #495057;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
        }
        
        .btn-custom {
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-warning-custom {
            background: #ffc107;
            color: #000;
        }
        
        .btn-warning-custom:hover {
            background: #e0a800;
            transform: translateY(-2px);
            color: #000;
        }
        
        .btn-secondary-custom {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary-custom:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
        }
        
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-box h3 {
            font-size: 2rem;
            margin: 10px 0 5px 0;
            font-weight: 700;
        }
        
        .stat-box p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .book-cover-section {
                flex-direction: column;
            }
            
            .book-cover {
                width: 100%;
                height: 300px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="detail-container">
    <div class="detail-card">
        
        <!-- HEADER -->
        <div class="detail-header">
            <h1><i class="fas fa-book-open"></i> Detalle del Libro</h1>
            <p>Información completa del libro</p>
        </div>
        
        <!-- BODY -->
        <div class="detail-body">
            
            <!-- SECCIÓN: PORTADA Y DATOS PRINCIPALES -->
            <div class="book-cover-section">
                
                <!-- Portada (Placeholder) -->
                <div class="book-cover">
                    <i class="fas fa-book"></i>
                </div>
                
                <!-- Información Principal -->
                <div class="book-info">
                    <div class="book-title">
                        <?= htmlspecialchars($libro['titulo']) ?>
                    </div>
                    
                    <?php if (!empty($libro['subtitulo'])): ?>
                        <div class="book-subtitle">
                            <?= htmlspecialchars($libro['subtitulo']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="book-author">
                        <i class="fas fa-user-edit"></i>
                        <?= htmlspecialchars($libro['autor']) ?>
                    </div>
                    
                    <!-- Estadísticas Rápidas -->
                    <div class="stats-row">
                        <div class="stat-box">
                            <i class="fas fa-copy"></i>
                            <h3><?= $libro['total_copias'] ?></h3>
                            <p>Total Copias</p>
                        </div>
                        <div class="stat-box">
                            <i class="fas fa-check-circle"></i>
                            <h3><?= $libro['copias_disponibles'] ?></h3>
                            <p>Disponibles</p>
                        </div>
                        <div class="stat-box">
                            <i class="fas fa-hand-holding"></i>
                            <h3><?= $libro['veces_prestado'] ?? 0 ?></h3>
                            <p>Préstamos</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN: INFORMACIÓN BIBLIOGRÁFICA -->
            <div class="info-section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Información Bibliográfica
                </div>
                
                <div class="info-grid">
                    <!-- ISBN -->
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-barcode"></i> ISBN
                        </div>
                        <div class="info-value">
                            <?= !empty($libro['isbn']) ? htmlspecialchars($libro['isbn']) : 'No especificado' ?>
                        </div>
                    </div>
                    
                    <!-- Editorial -->
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-building"></i> Editorial
                        </div>
                        <div class="info-value">
                            <?= !empty($libro['editorial']) ? htmlspecialchars($libro['editorial']) : 'No especificada' ?>
                        </div>
                    </div>
                    
                    <!-- Año de Publicación -->
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-alt"></i> Año de Publicación
                        </div>
                        <div class="info-value">
                            <?= $libro['anio_publicacion'] ?? 'No especificado' ?>
                        </div>
                    </div>
                    
                    <!-- Categoría -->
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-tag"></i> Categoría
                        </div>
                        <div class="info-value">
                            <?= !empty($libro['categoria_nombre']) ? htmlspecialchars($libro['categoria_nombre']) : 'Sin categoría' ?>
                        </div>
                    </div>
                    
                    <!-- Idioma -->
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-language"></i> Idioma
                        </div>
                        <div class="info-value">
                            <?= !empty($libro['idioma']) ? htmlspecialchars($libro['idioma']) : 'No especificado' ?>
                        </div>
                    </div>
                    
                    <!-- Páginas -->
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-file-alt"></i> Páginas
                        </div>
                        <div class="info-value">
                            <?= !empty($libro['paginas']) ? htmlspecialchars($libro['paginas']) : 'No especificado' ?>
                        </div>
                    </div>
                    
                    <!-- Tipo -->
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-book"></i> Tipo de Libro
                        </div>
                        <div class="info-value">
                            <?php 
                            $tipo = $libro['tipo'] ?? 'fisico';
                            $badge_class = $tipo === 'digital' ? 'badge-digital' : ($tipo === 'ambos' ? 'badge-ambos' : 'badge-fisico');
                            ?>
                            <span class="badge-custom <?= $badge_class ?>">
                                <?= ucfirst($tipo) ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Estado -->
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-check-circle"></i> Estado
                        </div>
                        <div class="info-value">
                            <?php if ($libro['copias_disponibles'] > 0): ?>
                                <span class="badge-custom badge-disponible">
                                    <i class="fas fa-check"></i> Disponible
                                </span>
                            <?php else: ?>
                                <span class="badge-custom badge-agotado">
                                    <i class="fas fa-times"></i> No Disponible
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN: DESCRIPCIÓN -->
            <?php if (!empty($libro['descripcion'])): ?>
                <div class="info-section">
                    <div class="section-title">
                        <i class="fas fa-align-left"></i>
                        Descripción / Sinopsis
                    </div>
                    
                    <div class="description-box">
                        <?= nl2br(htmlspecialchars($libro['descripcion'])) ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- SECCIÓN: DATOS DEL SISTEMA -->
            <div class="info-section">
                <div class="section-title">
                    <i class="fas fa-database"></i>
                    Datos del Sistema
                </div>
                
                <div class="info-grid">
                    <!-- ID -->
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-hashtag"></i> ID del Sistema
                        </div>
                        <div class="info-value">
                            #<?= $libro['id'] ?>
                        </div>
                    </div>
                    
                    <!-- Fecha de Registro -->
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-clock"></i> Fecha de Registro
                        </div>
                        <div class="info-value">
                            <?= date('d/m/Y H:i', strtotime($libro['fecha_registro'])) ?>
                        </div>
                    </div>
                    
                    <!-- Estado del Sistema -->
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-power-off"></i> Estado del Sistema
                        </div>
                        <div class="info-value">
                            <?= ucfirst($libro['estado']) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- BOTONES DE ACCIÓN -->
            <div class="action-buttons">
                <a href="index.php?ruta=libros" class="btn-custom btn-secondary-custom">
                    <i class="fas fa-arrow-left"></i> Volver a la Lista
                </a>
                <a href="index.php?ruta=libros/editar&id=<?= $libro['id'] ?>" class="btn-custom btn-warning-custom">
                    <i class="fas fa-edit"></i> Editar Libro
                </a>
                <?php if ($libro['copias_disponibles'] > 0): ?>
                    <a href="index.php?ruta=prestamos/crear&libro_id=<?= $libro['id'] ?>" class="btn-custom btn-primary-custom">
                        <i class="fas fa-hand-holding"></i> Prestar Libro
                    </a>
                <?php endif; ?>
            </div>
            
        </div>
        
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>