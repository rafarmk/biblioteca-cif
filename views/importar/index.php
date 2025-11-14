<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Libros - Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .resultado-exitoso { background-color: #d4edda; border-left: 4px solid #28a745; }
        .resultado-error { background-color: #f8d7da; border-left: 4px solid #dc3545; }
        .resultado-actualizado { background-color: #fff3cd; border-left: 4px solid #ffc107; }
        .upload-zone {
            border: 3px dashed #dee2e6;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s;
        }
        .upload-zone:hover {
            border-color: #667eea;
            background: #f0f0ff;
        }
        .upload-zone.dragover {
            border-color: #28a745;
            background: #d4edda;
        }
    </style>
</head>
<body>
    <?php require_once 'views/layouts/navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2><i class="fas fa-file-excel text-success"></i> Importar Libros desde Excel</h2>
                <p class="text-muted">Sube un archivo Excel para importar múltiples libros a la vez</p>
                <hr>
            </div>
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['resultado_importacion'])): 
            $resultado = $_SESSION['resultado_importacion'];
            unset($_SESSION['resultado_importacion']);
        ?>
            <div class="alert alert-info">
                <h5><i class="fas fa-chart-bar"></i> Resultados de la Importación</h5>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3><?php echo $resultado['total']; ?></h3>
                                <p class="mb-0">Total procesados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-success">
                            <div class="card-body">
                                <h3 class="text-success"><?php echo $resultado['exitosos']; ?></h3>
                                <p class="mb-0">Nuevos importados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-warning">
                            <div class="card-body">
                                <h3 class="text-warning"><?php echo $resultado['actualizados']; ?></h3>
                                <p class="mb-0">Actualizados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-danger">
                            <div class="card-body">
                                <h3 class="text-danger"><?php echo $resultado['errores']; ?></h3>
                                <p class="mb-0">Errores</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($resultado['detalles'])): ?>
                    <div class="mt-4">
                        <h6>Detalles:</h6>
                        <div style="max-height: 400px; overflow-y: auto;">
                            <?php foreach ($resultado['detalles'] as $detalle): ?>
                                <div class="p-2 mb-2 resultado-<?php echo $detalle['estado']; ?>">
                                    <strong>Fila <?php echo $detalle['fila']; ?>:</strong>
                                    <?php echo htmlspecialchars($detalle['titulo']); ?>
                                    <br>
                                    <small><i class="fas fa-info-circle"></i> <?php echo $detalle['mensaje']; ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-upload"></i> Subir Archivo Excel</h5>
                    </div>
                    <div class="card-body">
                        <form action="index.php?ruta=importar&accion=procesar" method="POST" enctype="multipart/form-data" id="formImportar">
                            <div class="upload-zone" id="uploadZone">
                                <i class="fas fa-cloud-upload-alt fa-4x text-primary mb-3"></i>
                                <h5>Arrastra tu archivo aquí</h5>
                                <p class="text-muted">o haz clic para seleccionar</p>
                                <input type="file" name="archivo" id="archivo" class="d-none" accept=".xlsx,.xls" required>
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('archivo').click()">
                                    <i class="fas fa-folder-open"></i> Seleccionar Archivo
                                </button>
                                <div id="fileName" class="mt-3 text-success fw-bold"></div>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <button type="submit" class="btn btn-success btn-lg" id="btnImportar" disabled>
                                    <i class="fas fa-file-import"></i> Importar Libros
                                </button>
                                <a href="index.php?ruta=libros" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Instrucciones</h5>
                    </div>
                    <div class="card-body">
                        <h6>Formato del Excel:</h6>
                        <ul class="small">
                            <li>Los <strong>encabezados deben estar en la fila 4</strong></li>
                            <li>Los <strong>datos empiezan en la fila 5</strong></li>
                            <li>Columnas requeridas:
                                <ul>
                                    <li>C: ID DE LIBRO</li>
                                    <li>D: TITULO</li>
                                    <li>E: AUTOR</li>
                                    <li>F: GENERO</li>
                                    <li>G: AÑO</li>
                                    <li>I: UBICACIÓN</li>
                                    <li>K: N° EJEMPLARES</li>
                                </ul>
                            </li>
                        </ul>
                        
                        <hr>
                        
                        <h6>Notas importantes:</h6>
                        <ul class="small">
                            <li><i class="fas fa-check text-success"></i> Detecta duplicados automáticamente</li>
                            <li><i class="fas fa-check text-success"></i> Crea categorías si no existen</li>
                            <li><i class="fas fa-check text-success"></i> Actualiza libros existentes</li>
                            <li><i class="fas fa-exclamation-triangle text-warning"></i> Título y autor son obligatorios</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const uploadZone = document.getElementById('uploadZone');
        const fileInput = document.getElementById('archivo');
        const fileName = document.getElementById('fileName');
        const btnImportar = document.getElementById('btnImportar');
        
        // Drag and drop
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });
        
        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('dragover');
        });
        
        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                mostrarNombreArchivo();
            }
        });
        
        fileInput.addEventListener('change', mostrarNombreArchivo);
        
        function mostrarNombreArchivo() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                fileName.innerHTML = `<i class="fas fa-file-excel"></i> ${file.name} (${(file.size / 1024).toFixed(2)} KB)`;
                btnImportar.disabled = false;
            }
        }
        
        document.getElementById('formImportar').addEventListener('submit', function() {
            btnImportar.disabled = true;
            btnImportar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importando...';
        });
    </script>
</body>
</html>