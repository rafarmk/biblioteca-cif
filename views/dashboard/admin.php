<?php
require_once __DIR__ . '/../../config/config.php';

if (!isLoggedIn()) {
    redirect('auth/login');
    exit();
}

$flash = getFlashMessage();

// Estadísticas del usuario
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .user-welcome h1 {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .user-welcome p {
            color: #7f8c8d;
            margin: 0;
        }
        
        .user-avatar {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid #667eea;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 87, 108, 0.4);
            color: white;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 15px;
        }
        
        .stat-icon.blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-icon.orange {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .stat-icon.green {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .stat-card h3 {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }
        
        .stat-card p {
            color: #7f8c8d;
            margin: 0;
            font-size: 14px;
        }
        
        .quick-actions {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .quick-actions h2 {
            font-size: 22px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .action-btn {
            padding: 20px;
            border-radius: 12px;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            color: white;
        }
        
        .action-btn i {
            font-size: 32px;
        }
        
        .action-btn.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .action-btn.success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .action-btn.info {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            color: white;
        }
        
        .alert {
            border-radius: 12px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <div class="header-card">
            <div class="user-welcome">
                <h1>¡Bienvenido, <?php echo $_SESSION['nombre']; ?>! 👋</h1>
                <p>Rol: <?php echo ucfirst($_SESSION['rol_nombre']); ?> | Email: <?php echo $_SESSION['email']; ?></p>
            </div>
            <div class="user-avatar">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre']); ?>&background=667eea&color=fff" alt="Avatar">
                <a href="<?php echo BASE_URL; ?>auth/logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
        
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : $flash['type']; ?>">
                <i class="fas fa-check-circle"></i> <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-book-reader"></i>
                </div>
                <h3><?php echo $librosPrestados; ?></h3>
                <p>Libros Prestados Actualmente</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3><?php echo $librosVencidos; ?></h3>
                <p>Préstamos Vencidos</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-history"></i>
                </div>
                <h3><?php echo $historialTotal; ?></h3>
                <p>Total de Préstamos Realizados</p>
            </div>
        </div>
        
        <div class="quick-actions">
            <h2><i class="fas fa-bolt"></i> Acciones Rápidas</h2>
            <div class="action-grid">
                <a href="<?php echo BASE_URL; ?>libros" class="action-btn primary">
                    <i class="fas fa-search"></i>
                    <span>Buscar Libros</span>
                </a>
                <a href="<?php echo BASE_URL; ?>prestamos/mis-prestamos" class="action-btn success">
                    <i class="fas fa-list"></i>
                    <span>Mis Préstamos</span>
                </a>
                <a href="<?php echo BASE_URL; ?>perfil" class="action-btn info">
                    <i class="fas fa-user-edit"></i>
                    <span>Editar Perfil</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>