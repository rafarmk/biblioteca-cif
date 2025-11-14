<?php
$pageTitle = 'Importar Usuarios - Biblioteca CIF';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="page-container fade-in">
    <div class="content-wrapper">
        <h1 class="page-title">📊 Importar Usuarios desde Excel</h1>
        <p class="page-subtitle">Carga múltiples usuarios desde un archivo Excel (.xlsx o .xls)</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Formulario de importación -->
            <div class="col-2">
                <div class="card">
                    <h2 class="section-title">📁 Cargar Archivo</h2>
                    
                    <form action="index.php?ruta=usuarios&accion=importar&procesar=1" 
                          method="POST" 
                          enctype="multipart/form-data"
                          id="importForm">
                        
                        <div class="form-group">
                            <label class="form-label required">Seleccionar Archivo Excel</label>
                            <div class="file-upload">
                                <input type="file" 
                                       name="excel_file" 
                                       id="excel_file"
                                       accept=".xlsx,.xls"
                                       required>
                                <label for="excel_file" class="file-upload-label" id="fileLabel">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Haz clic o arrastra un archivo aquí</span>
                                    <span class="file-info">Formatos permitidos: .xlsx, .xls (Máx. 5MB)</span>
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <strong>El archivo debe contener las siguientes columnas:</strong>
                                <ul style="margin: 10px 0 0 20px;">
                                    <li>nombre</li>
                                    <li>email</li>
                                    <li>tipo_usuario (estudiante, docente, personal_administrativo, personal_operativo)</li>
                                    <li>carnet (opcional)</li>
                                    <li>telefono (opcional)</li>
                                    <li>password (opcional, si no se proporciona se generará automáticamente)</li>
                                </ul>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-full btn-lg mt-3">
                            <i class="fas fa-upload"></i> Importar Usuarios
                        </button>
                    </form>
                </div>
            </div>

            <!-- Instrucciones y plantilla -->
            <div class="col-2">
                <div class="card">
                    <h2 class="section-title">📝 Instrucciones</h2>
                    
                    <div style="color: var(--text-light); line-height: 1.8;">
                        <p style="margin-bottom: 15px;">
                            <strong style="color: var(--text-white);">Paso 1:</strong> Descarga la plantilla de ejemplo
                        </p>
                        <a href="assets/plantillas/plantilla_usuarios.xlsx" 
                           class="btn btn-success btn-full mb-3"
                           download>
                            <i class="fas fa-download"></i> Descargar Plantilla Excel
                        </a>

                        <p style="margin-bottom: 15px;">
                            <strong style="color: var(--text-white);">Paso 2:</strong> Completa la plantilla con los datos de los usuarios
                        </p>

                        <p style="margin-bottom: 15px;">
                            <strong style="color: var(--text-white);">Paso 3:</strong> Guarda el archivo y súbelo usando el formulario
                        </p>

                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Importante:</strong>
                            <ul style="margin: 10px 0 0 20px;">
                                <li>Los emails deben ser únicos</li>
                                <li>No modifiques los nombres de las columnas</li>
                                <li>La primera fila debe contener los encabezados</li>
                                <li>Si no se proporciona contraseña, se generará automáticamente: <code>usuario123</code></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Ejemplo visual -->
                <div class="card mt-3">
                    <h2 class="section-title">👀 Ejemplo de Formato</h2>
                    <div class="table-container">
                        <table class="table" style="font-size: 0.85rem;">
                            <thead>
                                <tr>
                                    <th>nombre</th>
                                    <th>email</th>
                                    <th>tipo_usuario</th>
                                    <th>carnet</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Juan Pérez</td>
                                    <td>juan@mail.com</td>
                                    <td>estudiante</td>
                                    <td>20240001</td>
                                </tr>
                                <tr>
                                    <td>María García</td>
                                    <td>maria@mail.com</td>
                                    <td>docente</td>
                                    <td>DOC001</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-group mt-4">
            <a href="index.php?ruta=usuarios" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left"></i> Volver a Usuarios
            </a>
        </div>
    </div>
</div>

<script>
// Mostrar nombre del archivo seleccionado
document.getElementById('excel_file').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || 'Ningún archivo seleccionado';
    const fileLabel = document.getElementById('fileLabel');
    
    if (e.target.files[0]) {
        fileLabel.innerHTML = `
            <i class="fas fa-file-excel" style="color: var(--success-color);"></i>
            <span style="color: var(--success-color); font-weight: 600;">${fileName}</span>
            <span class="file-info">Haz clic para cambiar el archivo</span>
        `;
    }
});

// Validar tamaño del archivo antes de enviar
document.getElementById('importForm').addEventListener('submit', function(e) {
    const file = document.getElementById('excel_file').files[0];
    
    if (file) {
        const fileSize = file.size / 1024 / 1024; // en MB
        
        if (fileSize > 5) {
            e.preventDefault();
            alert('El archivo es demasiado grande. El tamaño máximo permitido es 5MB.');
            return false;
        }
    }
});
</script>

</body>
</html>