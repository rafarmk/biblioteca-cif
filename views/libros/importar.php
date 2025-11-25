<?php
$page_title = "Importar Libros - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../layouts/navbar.php';
?>

<style>
.container {
    max-width: 800px;
    margin: 40px auto;
    padding: 0 20px;
}

.import-card {
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 8px 25px var(--shadow-color);
}

.import-header {
    text-align: center;
    margin-bottom: 30px;
}

.import-header h1 {
    color: var(--text-primary);
    font-size: 2rem;
    margin-bottom: 10px;
}

.import-header p {
    color: var(--text-secondary);
}

.upload-zone {
    border: 3px dashed var(--border-color);
    border-radius: 12px;
    padding: 60px 20px;
    text-align: center;
    background: var(--bg-tertiary);
    transition: all 0.3s;
    cursor: pointer;
}

.upload-zone:hover {
    border-color: var(--accent-primary);
    background: var(--bg-secondary);
}

.upload-icon {
    font-size: 4rem;
    color: var(--accent-primary);
    margin-bottom: 20px;
}

.upload-text {
    color: var(--text-primary);
    font-size: 1.2rem;
    margin-bottom: 10px;
}

.upload-subtext {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.file-input {
    display: none;
}

.info-box {
    background: rgba(59, 130, 246, 0.1);
    border: 2px solid var(--accent-primary);
    border-radius: 12px;
    padding: 20px;
    margin: 30px 0;
}

.info-box h3 {
    color: var(--accent-primary);
    margin-bottom: 15px;
}

.info-box ul {
    color: var(--text-secondary);
    margin-left: 20px;
}

.info-box li {
    margin: 8px 0;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
    color: white;
    width: 100%;
    margin-top: 20px;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--shadow-color);
}

.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-secondary {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    border: 2px solid var(--border-color);
    width: 100%;
    margin-top: 10px;
}

.btn-secondary:hover {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.selected-file {
    background: var(--bg-tertiary);
    border: 2px solid var(--accent-primary);
    border-radius: 8px;
    padding: 15px;
    margin-top: 20px;
    display: none;
}

.selected-file.show {
    display: block;
}

.selected-file-name {
    color: var(--text-primary);
    font-weight: 600;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-weight: 600;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 2px solid #10b981;
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

<div class="container">
    <div class="import-card">
        <div class="import-header">
            <h1>📥 Importar Libros</h1>
            <p>Carga un archivo CSV o Excel para importar múltiples libros a la vez</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="index.php?ruta=libros&accion=importar" method="POST" enctype="multipart/form-data" id="importForm">
            <div class="upload-zone" onclick="document.getElementById('fileInput').click()">
                <div class="upload-icon">📄</div>
                <div class="upload-text">Haz clic aquí o arrastra tu archivo</div>
                <div class="upload-subtext">Archivos CSV o Excel (.xlsx) - Máximo 10 MB</div>
                <input type="file" 
                       id="fileInput" 
                       name="archivo" 
                       class="file-input" 
                       accept=".csv,.xlsx,.xls"
                       required
                       onchange="showFileName(this)">
            </div>

            <div id="selectedFile" class="selected-file">
                <i class="fas fa-file-excel" style="color: var(--accent-primary);"></i>
                <span class="selected-file-name" id="fileName"></span>
            </div>

            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                <i class="fas fa-upload"></i> Importar Libros 🚀
            </button>

            <a href="index.php?ruta=libros" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Libros
            </a>
        </form>

        <div class="info-box">
            <h3>⚡ Formato requerido del archivo:</h3>
            <ul>
                <li><strong>ISBN</strong> - Código único internacional del libro</li>
                <li><strong>Título</strong> - El nombre del libro</li>
                <li><strong>Autor</strong> - Nombre del autor</li>
                <li><strong>Editorial</strong> - Editorial (opcional)</li>
                <li><strong>Año</strong> - Año de publicación (opcional)</li>
                <li><strong>Categoría</strong> - Categoría del libro (opcional)</li>
                <li><strong>Cantidad</strong> - Cantidad de copias disponibles</li>
            </ul>
        </div>

        <div class="info-box" style="border-color: #f59e0b; background: rgba(245, 158, 11, 0.1);">
            <h3 style="color: #f59e0b;">⚠️ Importante:</h3>
            <ul>
                <li>El archivo debe tener encabezados en la primera fila</li>
                <li>Los libros duplicados (mismo ISBN) serán ignorados</li>
                <li>Revisa que los datos sean correctos antes de importar</li>
            </ul>
        </div>
    </div>
</div>

<script>
function showFileName(input) {
    const fileName = input.files[0]?.name;
    const fileNameDisplay = document.getElementById('fileName');
    const selectedFileDiv = document.getElementById('selectedFile');
    const submitBtn = document.getElementById('submitBtn');

    if (fileName) {
        fileNameDisplay.textContent = fileName;
        selectedFileDiv.classList.add('show');
        submitBtn.disabled = false;
    } else {
        selectedFileDiv.classList.remove('show');
        submitBtn.disabled = true;
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
