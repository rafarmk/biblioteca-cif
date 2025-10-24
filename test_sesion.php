<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test de Sesión</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #0a0e1a; color: white; }
        .success { color: #4ade80; font-size: 24px; }
        .error { color: #f87171; font-size: 24px; }
        pre { background: #1e293b; padding: 20px; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>🔍 Test de Sesión - Biblioteca CIF</h1>
    
    <h2>Contenido de $_SESSION:</h2>
    <pre><?php print_r($_SESSION); ?></pre>
    
    <?php if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true): ?>
        <p class="success">✅ SESIÓN ACTIVA - Usuario logueado correctamente</p>
        <p>Admin ID: <?php echo $_SESSION['admin_id'] ?? 'N/A'; ?></p>
        <p>Admin Nombre: <?php echo $_SESSION['admin_nombre'] ?? 'N/A'; ?></p>
        <p>Admin Email: <?php echo $_SESSION['admin_email'] ?? 'N/A'; ?></p>
    <?php else: ?>
        <p class="error">❌ NO HAY SESIÓN ACTIVA</p>
    <?php endif; ?>
    
    <hr>
    <p><a href="index.php?ruta=login" style="color: #38bdf8;">← Volver al Login</a></p>
    <p><a href="index.php?ruta=home" style="color: #38bdf8;">→ Ir al Home</a></p>
</body>
</html>