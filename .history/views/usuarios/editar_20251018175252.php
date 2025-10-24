<?php
// views/usuarios/editar.php

if (!isset($usuario)) {
    echo "<p>❌ Usuario no encontrado</p>";
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tiposUsuario = [
    'policia' => 'Policía',
    'administrativo' => 'Administrativo',
    'estudiante' => 'Estudiante'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Biblioteca CIF</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); min-height:100vh; padding:20px;}
        .container { max-width: 900px; margin:0 auto; background:white; border-radius:15px; box-shadow:0 10px 40px rgba(0,0,0,0.2); padding:30px;}
        h1 { margin-bottom:25px; color:#333; display:flex; align-items:center; gap:10px;}
        h1::before { content:"✏️"; font-size:40px;}
        form { display:flex; flex-direction:column; gap:20px; }
        label { font-weight:600; margin-bottom:5px; }
        input, select { padding:12px 15px; border-radius:8px; border:2px solid #e2e8f0; font-size:14px; width:100%; }
        input:focus, select:focus { outline:none; border-color:#667eea; box-shadow:0 0 0 3px rgba(102,126,234,0.1);}
        .btn { padding:12px 20px; border:none; border-radius:8px; cursor:pointer; font-size:14px; font-weight:600; transition:all 0.3s ease; text-decoration:none; text-align:center;}
        .btn-success { background:#48bb78; color:white; }
        .btn-success:hover { background:#38a169; transform:translateY(-2px); box-shadow:0 5px 15px rgba(72,187,120,0.4);}
        .btn-back { background:#718096; color:white; display:inline-block; text-decoration:none; padding:12px 20px; border-radius:8px; margin-top:10px;}
        .btn-back:hover { background:#4a5568; transform:translateY(-2px);}
        .alert { padding:12px 20px; border-radius:8px; margin-bottom:15px; font-weight:500; display:flex; align-items:center; gap:10px;}
        .alert-success { background:#c6f6d5; color:#22543d; border-left:4px solid #48bb78;}
        .alert-error { background:#fed7d7; color:#742a2a; border-left:4px solid #f56565;}
        .badge { display:inline-block; padding:6px 12px; border-radius:20px; font-size:12px; font-weight:600; text-align:center; }
        .badge-policia { background:#bee3f8; color:#2c5282; }
        .badge-administrativo { background:#faf089; color:#744210; }
        .badge-estudiante { background:#c6f6d5; color:#22543d; }
        @media(max-width:768px){ form{gap:15px;} }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Usuario</h1>

        <?php if(isset($_SESSION['mensaje'])):
            $tipo = $_SESSION['mensaje_tipo'] ?? 'success';
        ?>
            <div class="alert alert-<?php echo $tipo; ?>">
                <?php echo $tipo==='success' ? '✅' : '❌'; ?>
                <?php echo $_SESSION['mensaje']; ?>
            </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['mensaje_tipo']); endif; ?>

        <form action="?ruta=usuarios/editar&id=<?php echo $usuario['id']; ?>" method="POST" id="formUsuario">
            <div>
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>

            <div>
                <label for="documento">Documento</label>
                <input type="text" id="documento" name="documento" value="<?php echo htmlspecialchars($usuario['documento']); ?>">
            </div>

            <div>
                <label for="tipo">Tipo de Usuario</label>
                <select id="tipo" name="tipo" required>
                    <?php foreach($tiposUsuario as $key => $valor): ?>
                        <option value="<?php echo $key; ?>" <?php echo $usuario['tipo']==$key?'selected':''; ?>>
                            <?php echo $valor; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="carnet_policial">Carnet Policial</label>
                <input type="text" id="carnet_policial" name="carnet_policial" value="<?php echo htmlspecialchars($usuario['carnet_policial']); ?>" placeholder="Solo para policías">
            </div>

            <div>
                <label for="token_temporal">Token Temporal</label>
                <input type="text" id="token_temporal" name="token_temporal" value="<?php echo htmlspecialchars($usuario['token_temporal']); ?>" placeholder="Opcional">
            </div>

            <div>
                <?php
                $badgeClass = 'badge-' . $usuario['tipo'];
                $tipoTexto = $tiposUsuario[$usuario['tipo']] ?? $usuario['tipo'];
                ?>
                <span class="badge <?php echo $badgeClass; ?>">
                    <?php echo $tipoTexto; ?>
                </span>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
                <a href="?ruta=usuarios" class="btn-back">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        const tipoSelect = document.getElementById('tipo');
        const carnetInput = document.getElementById('carnet_policial');

        function actualizarCarnet() {
            if(tipoSelect.value === 'policia') {
                carnetInput.removeAttribute('readonly');
                carnetInput.placeholder = "Ingrese carnet policial";
            } else {
                carnetInput.value = '';
                carnetInput.setAttribute('readonly', 'readonly');
                carnetInput.placeholder = "Solo para policías";
            }
        }

        tipoSelect.addEventListener('change', actualizarCarnet);

        // Inicializar al cargar la página
        window.addEventListener('DOMContentLoaded', actualizarCarnet);
    </script>
</body>
</html>


