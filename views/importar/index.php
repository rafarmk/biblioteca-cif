<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<style>
.main-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px 40px 20px;
}

.page-header {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px var(--shadow);
    border: 2px solid var(--border-color);
}

[data-theme="premium"] .page-header {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: rgba(56, 189, 248, 0.2);
}

.page-header h1 {
    font-size: 2rem;
    color: var(--text-primary);
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 15px;
}

.content-card {
    background: var(--bg-card);
    border-radius: 16px;
    box-shadow: 0 4px 20px var(--shadow);
    border: 2px solid var(--border-color);
    padding: 40px;
}

[data-theme="premium"] .content-card {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: rgba(56, 189, 248, 0.2);
}

.upload-area {
    border: 3px dashed var(--border-color);
    border-radius: 12px;
    padding: 60px 20px;
    text-align: center;
    background: var(--bg-secondary);
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: var(--primary);
    background: var(--bg-primary);
}

.upload-icon {
    font-size: 4rem;
    color: var(--primary);
    margin-bottom: 20px;
}

.upload-text {
    font-size: 1.2rem;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.upload-hint {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.btn-primary {
    background: var(--primary);
    color: white;
    width: 100%;
    justify-content: center;
    padding: 15px;
    font-size: 16px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-primary:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 2px solid #a7f3d0;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 2px solid #fecaca;
}

.info-box {
    background: rgba(59, 130, 246, 0.1);
    border-left: 4px solid var(--primary);
    padding: 20px;
    border-radius: 8px;
    margin-top: 30px;
}

.info-box h3 {
    color: var(--text-primary);
    margin-bottom: 10px;
}

.info-box ul {
    color: var(--text-secondary);
    margin-left: 20px;
}

#archivo {
    display: none;
}

.file-name {
    margin-top: 15px;
    padding: 10px;
    background: var(--primary);
    color: white;
    border-radius: 8px;
    display: none;
}
</style>

<div class="main-container">
    <div class="page-header">
        <h1> Importar Libros desde Excel</h1>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['errores_importacion']) && count($_SESSION['errores_importacion']) > 0): ?>
        <div class="alert alert-error">
            <strong> Errores durante la importación:</strong>
            <ul style="margin: 10px 0 0 20px;">
                <?php foreach (array_slice($_SESSION['errores_importacion'], 0, 10) as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
                <?php if (count($_SESSION['errores_importacion']) > 10): ?>
                    <li>... y <?php echo count($_SESSION['errores_importacion']) - 10; ?> errores más</li>
                <?php endif; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errores_importacion']); ?>
    <?php endif; ?>

    <div class="content-card">
        <form action="index.php?ruta=importar&accion=procesar" method="POST" enctype="multipart/form-data" id="formImportar">
            
            <div class="upload-area" onclick="document.getElementById('archivo').click()">
                <div class="upload-icon">
                    
                </div>
                <div class="upload-text">
                    Haz clic para seleccionar el archivo Excel
                </div>
                <div class="upload-hint">
                    Formatos permitidos: .xlsx, .xls
                </div>
                <div class="file-name" id="fileName"></div>
            </div>

            <input type="file" 
                   name="archivo" 
                   id="archivo" 
                   accept=".xlsx,.xls"
                   required
                   onchange="mostrarNombre()">

            <button type="submit" class="btn-primary" style="margin-top: 20px;">
                 Importar Libros
            </button>
        </form>

        <div class="info-box">
            <h3>ℹ Formato del Excel</h3>
            <p>El archivo debe tener las siguientes columnas (en orden):</p>
            <ul>
                <li><strong>Columna B:</strong> ID de Libro (ejemplo: DCH-001)</li>
                <li><strong>Columna C:</strong> Título del Libro</li>
                <li><strong>Columna D:</strong> Autor</li>
                <li><strong>Columna E:</strong> Género/Categoría</li>
                <li><strong>Columna F:</strong> Año de Publicación</li>
                <li><strong>Columna G:</strong> Estado</li>
                <li><strong>Columna H:</strong> Ubicación</li>
                <li><strong>Columna I:</strong> Observaciones</li>
                <li><strong>Columna J:</strong> Número de Ejemplares</li>
            </ul>
            <p style="margin-top: 15px;">
                <strong>Nota:</strong> La primera fila debe contener los encabezados. Los datos deben empezar en la fila 2.
            </p>
        </div>
    </div>
</div>

<script>
function mostrarNombre() {
    const input = document.getElementById('archivo');
    const fileName = document.getElementById('fileName');
    
    if (input.files.length > 0) {
        fileName.textContent = ' ' + input.files[0].name;
        fileName.style.display = 'block';
    }
}

document.getElementById('formImportar').addEventListener('submit', function(e) {
    const input = document.getElementById('archivo');
    if (!input.files.length) {
        e.preventDefault();
        alert('Por favor selecciona un archivo Excel');
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
