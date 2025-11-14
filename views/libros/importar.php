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
    <title>Importar Libros - Biblioteca CIF</title>
    <style>
        body {
            background: #f1f5f9;
            padding-top: 80px;
        }

        .import-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .import-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .import-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .import-header h1 {
            font-size: 2rem;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .import-header p {
            color: #6b7280;
            font-size: 1rem;
        }

        .file-upload-area {
            border: 3px dashed #667eea;
            border-radius: 12px;
            padding: 60px 20px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 30px;
        }

        .file-upload-area:hover {
            background: #f1f5f9;
            border-color: #764ba2;
        }

        .file-upload-area.active {
            background: #e0e7ff;
            border-color: #4f46e5;
        }

        .file-upload-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .file-upload-text {
            font-size: 1.2rem;
            color: #4b5563;
            margin-bottom: 10px;
        }

        .file-upload-hint {
            color: #9ca3af;
            font-size: 0.9rem;
        }

        input[type="file"] {
            display: none;
        }

        .file-info {
            background: #e0e7ff;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .file-info.show {
            display: block;
        }

        .btn-import {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-import:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-import:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }

        .instructions {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }

        .instructions h3 {
            color: #92400e;
            margin-bottom: 10px;
        }

        .instructions ul {
            color: #78350f;
            margin-left: 20px;
        }

        .instructions li {
            margin-bottom: 8px;
        }

        .results {
            margin-top: 30px;
            padding: 20px;
            border-radius: 8px;
        }

        .results.success {
            background: #d1fae5;
            border: 2px solid #10b981;
        }

        .results.error {
            background: #fee2e2;
            border: 2px solid #ef4444;
        }

        .results h3 {
            margin-bottom: 15px;
        }

        .results ul {
            margin-left: 20px;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #6b7280;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: #4b5563;
        }
    </style>
</head>
<body>

<div class="import-container">
    <a href="index.php?ruta=libros" class="back-btn">← Volver a Libros</a>

    <div class="import-card">
        <div class="import-header">
            <h1>📥 Importar Libros</h1>
            <p>Carga un archivo CSV o Excel para importar múltiples libros a la vez</p>
        </div>

        <form method="POST" action="index.php?ruta=libros&accion=importar" enctype="multipart/form-data" id="importForm">
            <div class="file-upload-area" id="dropArea">
                <div class="file-upload-icon">📄</div>
                <div class="file-upload-text">
                    Haz clic aquí o arrastra tu archivo
                </div>
                <div class="file-upload-hint">
                    Archivos CSV o Excel (.xlsx) - Máximo 10 MB
                </div>
                <input type="file" id="fileInput" name="archivo" accept=".csv,.xlsx,.xls" required>
            </div>

            <div class="file-info" id="fileInfo">
                <strong>Archivo seleccionado:</strong> <span id="fileName"></span>
            </div>

            <button type="submit" class="btn-import" id="submitBtn" disabled>
                Importar Libros 🚀
            </button>
        </form>

        <div class="instructions">
            <h3>📋 Instrucciones:</h3>
            <ul>
                <li><strong>CSV:</strong> El archivo debe tener columnas: ISBN, TITULO, AUTOR</li>
                <li><strong>Excel (.xlsx):</strong> Puede tener columnas como: ID DE LIBRO, TITULO, AUTOR, UBICACION, N° DE EJEMPLARES</li>
                <li>El sistema detecta automáticamente las columnas</li>
                <li>La primera fila debe contener los nombres de las columnas</li>
                <li>Columnas opcionales: EDITORIAL, GENERO, AÑO DE PUBLICACION, UBICACION, CANTIDAD</li>
            </ul>
        </div>

        <?php if (isset($this->resultados)): ?>
            <?php if ($this->resultados['importados'] > 0): ?>
                <div class="results success">
                    <h3>✅ Importación Exitosa</h3>
                    <p><strong><?php echo $this->resultados['importados']; ?></strong> libro(s) importado(s) correctamente.</p>
                </div>
            <?php endif; ?>

            <?php if (!empty($this->resultados['errores'])): ?>
                <div class="results error">
                    <h3>⚠️ Errores Encontrados:</h3>
                    <ul>
                        <?php foreach ($this->resultados['errores'] as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const submitBtn = document.getElementById('submitBtn');

    // Click para abrir selector de archivos
    dropArea.addEventListener('click', () => {
        fileInput.click();
    });

    // Cuando se selecciona un archivo
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            fileName.textContent = file.name;
            fileInfo.classList.add('show');
            submitBtn.disabled = false;
        }
    });

    // Drag and drop
    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.classList.add('active');
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('active');
    });

    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.classList.remove('active');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileName.textContent = files[0].name;
            fileInfo.classList.add('show');
            submitBtn.disabled = false;
        }
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

</body>
</html>