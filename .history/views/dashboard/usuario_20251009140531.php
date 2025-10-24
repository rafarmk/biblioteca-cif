
<?php
require_once __DIR__ . '/../../config/config.php';

if (!isLoggedIn()) {
    redirect('auth/login');
    exit();
}

$flash = getFlashMessage();
$librosPrestados = 0;
$librosVencidos = 0;
$historialTotal = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Biblioteca - Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px;
        }
        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .header-card h1 {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
        }
        .logout-btn {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <div class="header-card">
            <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?>!</h1>
            <p>Rol: <?php echo ucfirst($_SESSION['rol_nombre']); ?> | Email: <?php echo $_SESSION['email']; ?></p>
            <a href="<?php echo BASE_URL; ?>auth/logout" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </div>
        
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : $flash['type']; ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>
        
        <div class="stat-card">
            <h3>Libros Prestados: <?php echo $librosPrestados; ?></h3>
            <h3>Préstamos Vencidos: <?php echo $librosVencidos; ?></h3>
            <h3>Historial Total: <?php echo $historialTotal; ?></h3>
        </div>
    </div>
</body>
</html>
'@ | Out-File -FilePath "C:\laragon\www\biblioteca-cif\views\dashboard\usuario.php" -Encoding UTF8