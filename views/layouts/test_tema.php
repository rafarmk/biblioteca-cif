<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Tema</title>
    <style>
        body { font-family: Arial; padding: 40px; background: #1a1a2e; color: white; }
        .info { background: #16213e; padding: 20px; border-radius: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>🔍 Diagnóstico de Tema</h1>
    
    <div class="info">
        <h2>Tema Actual:</h2>
        <p style="font-size: 24px; color: #4ade80;">
            <?php echo $_SESSION['tema'] ?? '❌ NO DEFINIDO'; ?>
        </p>
    </div>

    <div class="info">
        <h2>Todas las variables de sesión:</h2>
        <pre><?php print_r($_SESSION); ?></pre>
    </div>

    <div class="info">
        <h2>¿Existe $_SESSION['tema']?</h2>
        <p style="font-size: 20px;">
            <?php echo isset($_SESSION['tema']) ? '✅ SÍ EXISTE' : '❌ NO EXISTE'; ?>
        </p>
    </div>

    <hr>
    <a href="index.php?ruta=home" style="color: #4ade80;">← Volver al sistema</a>
</body>
</html>