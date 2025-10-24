<?php
// views/usuarios/editar.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Biblioteca CIF</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 30px;
            animation: slideIn 0.4s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .header h1 {
            color: #333;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header h1::before {
            content: "✏️";
            font-size: 30px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-back {
            background: #718096;
            color: white;
        }

        .btn-back:hover {
            background: #4a5568;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: #667eea;
            color: white;
            width: 100%;
            justify-content: center;
            font-size: 16px;
            padding: 15px;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
            width: 100%;
            justify-content: center;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border-left: 4px solid #48bb78;
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border-left: 4px solid #f56565;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 14px;
        }

        label .required {
            color: #e53e3e;
            font-weight: bold;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        input[type="text"]:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        input[type="text"]:disabled {
            background-color: #f7fafc;
            cursor: not-allowed;
        }

        .help-text {
            display: block;
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
        }

        .help-text.info {
            color: #4299e1;
        }

        .help-text.success {
            color: #48bb78;
        }

        .campo-oculto {
            display: none;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
            }
            to {
                opacity: 1;
                max-height: 200px;
            }
        }

        .form-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
        }

        .info-card {
            background: #edf2f7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #4299e1;
        }

        .info-card p {
            margin: 0;
            color: #2d3748;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .form-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Editar Usuario</h1>
            <a href="index.php?ruta=usuarios" class="btn btn-back">
                ⬅️ Volver
            </a>
        </div>

        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['mensaje'])):
            $tipo = isset($_SESSION['mensaje_tipo']) ? $_SESSION['mensaje_tipo'] : 'success';
        ?>
            <div class="alert alert-<?php echo $tipo; ?>">
                <?php echo $tipo === 'success' ? '✅' : '❌'; ?>
                <?php echo $_SESSION['mensaje']; ?>
            </div>
        <?php
            unset($_SESSION['mensaje']);
            unset($_SESSION['mensaje_tipo']);
        endif;
        ?>

        <div class="info-card">
            <p>
                <strong>ℹ️ Información:</strong> 
                Usuario registrado el <?php echo date('d/m/Y', strtotime($usuarioData['fecha_registro'])); ?>
                <?php if (!empty($usuarioData['token_temporal'])): ?>
                    | Token: <code><?php echo htmlspecialchars($usuarioData['token_temporal']); ?></code>
                <?php endif; ?>
            </p>
        </div>

        <form method="POST" action="index.php?ruta=usuarios/editar&id=<?php echo $usuarioData['id']; ?>" id="formUsuario">
            
            <input type="hidden" name="id" value="<?php echo $usuarioData['id']; ?>">
            
            <div class="form-group">
                <label for="nombre">
                    Nombre Completo <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="nombre" 
                    name="nombre" 
                    placeholder="Ej: Juan Carlos Pérez"
                    required
                    value="<?php echo htmlspecialchars($usuarioData['nombre']); ?>"
                >
            </div>

            <div class="form-group">
                <label for="tipo">
                    Tipo de Usuario <span class="required">*</span>
                </label>
                <select id="tipo" name="tipo" required>
                    <option value="">-- Seleccionar tipo --</option>
                    <?php foreach ($tiposUsuario as $valor => $texto): ?>
                        <option value="<?php echo $valor; ?>"
                            <?php echo ($usuarioData['tipo'] === $valor) ? 'selected' : ''; ?>>
                            <?php echo $texto; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="help-text">Selecciona el tipo según su rol en la institución</small>
            </div>

            <div id="campoCarnet" class="campo-oculto">
                <div class="form-group">
                    <label for="carnet_policial">
                        Carnet Policial <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="carnet_policial" 
                        name="carnet_policial" 
                        placeholder="POL-2024-001"
                        value="<?php echo htmlspecialchars($usuarioData['carnet_policial'] ?? ''); ?>"
                    >
                    <small class="help-text info" id="formatoCarnet">
                        💡 Formato: POL-2024-001
                    </small>
                </div>
            </div>

            <div id="campoDUI" class="campo-oculto">
                <div class="form-group">
                    <label for="documento">
                        DUI (Opcional)
                    </label>
                    <input 
                        type="text" 
                        id="documento" 
                        name="documento" 
                        placeholder="12345678-9"
                        value="<?php echo htmlspecialchars($usuarioData['documento'] ?? ''); ?>"
                    >
                    <small class="help-text">
                        ℹ️ Dejar vacío si el visitante no tiene DUI
                    </small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    💾 Actualizar Usuario
                </button>
                <a href="index.php?ruta=usuarios" class="btn btn-secondary">
                    ❌ Cancelar
                </a>
            </div>

        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const tipoSelect = document.getElementById('tipo');
            const campoCarnet = document.getElementById('campoCarnet');
            const campoDUI = document.getElementById('campoDUI');
            const carnetInput = document.getElementById('carnet_policial');
            const formatoCarnet = document.getElementById('formatoCarnet');
            
            function actualizarCampos() {
                const tipoSeleccionado = tipoSelect.value;
                
                campoCarnet.classList.remove('campo-oculto');
                campoCarnet.style.display = 'none';
                campoDUI.classList.remove('campo-oculto');
                campoDUI.style.display = 'none';
                
                carnetInput.removeAttribute('required');
                
                if (tipoSeleccionado === 'policia') {
                    campoCarnet.style.display = 'block';
                    carnetInput.setAttribute('required', 'required');
                    carnetInput.placeholder = 'POL-2024-001';
                    formatoCarnet.innerHTML = '💡 Formato: POL-2024-001 (POL-AÑO-NÚMERO)';
                    
                } else if (tipoSeleccionado === 'administrativo') {
                    campoCarnet.style.display = 'block';
                    carnetInput.setAttribute('required', 'required');
                    carnetInput.placeholder = 'PH00221';
                    formatoCarnet.innerHTML = '💡 Formato: PH00221 (PH seguido de 5 dígitos)';
                    
                } else if (tipoSeleccionado === 'estudiante') {
                    campoDUI.style.display = 'block';
                }
            }
            
            tipoSelect.addEventListener('change', actualizarCampos);
            
            // Ejecutar al cargar para mostrar campos según tipo guardado
            actualizarCampos();
            
            document.getElementById('formUsuario').addEventListener('submit', function(e) {
                const tipo = tipoSelect.value;
                const carnet = carnetInput.value.trim();
                
                if (tipo === 'policia' && carnet) {
                    const formatoPolicia = /^POL-\d{4}-\d{3,4}$/;
                    if (!formatoPolicia.test(carnet)) {
                        e.preventDefault();
                        alert('❌ Formato de carnet policial inválido.\nFormato correcto: POL-2024-001');
                        carnetInput.focus();
                        return false;
                    }
                }
                
                if (tipo === 'administrativo' && carnet) {
                    const formatoAdmin = /^PH\d{5}$/;
                    if (!formatoAdmin.test(carnet)) {
                        e.preventDefault();
                        alert('❌ Formato de carnet administrativo inválido.\nFormato correcto: PH00221');
                        carnetInput.focus();
                        return false;
                    }
                }
                
                return true;
            });
            
            carnetInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
            
        });
    </script>
</body>
</html>
