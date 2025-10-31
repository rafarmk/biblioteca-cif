<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Biblioteca CIF</title>
</head>
<body>
    <div class="container" style="max-width: 1200px; margin: 50px auto; padding: 20px;">
        <?php if ($esAdmin): ?>
            <!-- Vista de bienvenida para ADMINISTRADORES -->
            <div class="welcome-section" style="text-align: center; padding: 60px 20px;">
                <h1 style="font-size: 3rem; margin-bottom: 20px; color: var(--text-primary);">
                    <i class="fas fa-user-shield"></i> Bienvenido, Administrador
                </h1>
                <p style="font-size: 1.3rem; color: var(--text-secondary); margin-bottom: 40px;">
                    Panel de gestión del Sistema de Biblioteca CIF
                </p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 50px;">
                    <a href="index.php?ruta=home" style="text-decoration: none;">
                        <div style="background: var(--bg-card); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px var(--shadow); transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-8px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="fas fa-tachometer-alt" style="font-size: 3rem; color: var(--primary); margin-bottom: 15px;"></i>
                            <h3 style="color: var(--text-primary);">Dashboard</h3>
                            <p style="color: var(--text-secondary); margin: 15px 0;">Estadísticas completas del sistema</p>
                        </div>
                    </a>
                    
                    <a href="index.php?ruta=libros" style="text-decoration: none;">
                        <div style="background: var(--bg-card); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px var(--shadow); transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-8px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="fas fa-book" style="font-size: 3rem; color: #10b981; margin-bottom: 15px;"></i>
                            <h3 style="color: var(--text-primary);">Gestionar Libros</h3>
                            <p style="color: var(--text-secondary); margin: 15px 0;">Agregar, editar o eliminar libros</p>
                        </div>
                    </a>
                    
                    <a href="index.php?ruta=usuarios" style="text-decoration: none;">
                        <div style="background: var(--bg-card); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px var(--shadow); transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-8px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="fas fa-users" style="font-size: 3rem; color: #f59e0b; margin-bottom: 15px;"></i>
                            <h3 style="color: var(--text-primary);">Gestionar Usuarios</h3>
                            <p style="color: var(--text-secondary); margin: 15px 0;">Aprobar y gestionar usuarios</p>
                        </div>
                    </a>
                    
                    <a href="index.php?ruta=prestamos" style="text-decoration: none;">
                        <div style="background: var(--bg-card); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px var(--shadow); transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-8px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="fas fa-handshake" style="font-size: 3rem; color: #8b5cf6; margin-bottom: 15px;"></i>
                            <h3 style="color: var(--text-primary);">Gestionar Préstamos</h3>
                            <p style="color: var(--text-secondary); margin: 15px 0;">Crear y gestionar préstamos</p>
                        </div>
                    </a>
                    
                    <a href="index.php?ruta=importar" style="text-decoration: none;">
                        <div style="background: var(--bg-card); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px var(--shadow); transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-8px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="fas fa-file-upload" style="font-size: 3rem; color: #ec4899; margin-bottom: 15px;"></i>
                            <h3 style="color: var(--text-primary);">Importar Libros</h3>
                            <p style="color: var(--text-secondary); margin: 15px 0;">Cargar libros desde Excel</p>
                        </div>
                    </a>
                </div>
                
                <div style="margin-top: 60px; padding: 40px; background: var(--bg-secondary); border-radius: 15px;">
                    <h2 style="margin-bottom: 20px;"><i class="fas fa-chart-line"></i> Resumen Rápido</h2>
                    <div style="display: flex; justify-content: center; gap: 50px; flex-wrap: wrap;">
                        <div>
                            <p style="font-size: 3rem; font-weight: bold; color: var(--primary); margin: 0;"><?php echo $totalLibros; ?></p>
                            <p style="color: var(--text-secondary);">Libros Totales</p>
                        </div>
                        <div>
                            <p style="font-size: 3rem; font-weight: bold; color: #10b981; margin: 0;"><?php echo $librosDisponibles; ?></p>
                            <p style="color: var(--text-secondary);">Libros Disponibles</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Vista de bienvenida para USUARIOS COMUNES -->
            <div class="welcome-section" style="text-align: center; padding: 60px 20px;">
                <h1 style="font-size: 3rem; margin-bottom: 20px; color: var(--text-primary);">
                    <i class="fas fa-book-reader"></i> Bienvenido a Biblioteca CIF
                </h1>
                <p style="font-size: 1.3rem; color: var(--text-secondary); margin-bottom: 40px;">
                    Explora nuestra colección de libros y gestiona tus préstamos
                </p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 50px;">
                    <a href="index.php?ruta=catalogo" style="text-decoration: none;">
                        <div style="background: var(--bg-card); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px var(--shadow); transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-8px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="fas fa-books" style="font-size: 3rem; color: var(--primary); margin-bottom: 15px;"></i>
                            <h3 style="color: var(--text-primary);">Catálogo de Libros</h3>
                            <p style="color: var(--text-secondary); margin: 15px 0;">Explora nuestra amplia colección</p>
                        </div>
                    </a>
                    
                    <a href="index.php?ruta=mis-prestamos" style="text-decoration: none;">
                        <div style="background: var(--bg-card); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px var(--shadow); transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-8px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="fas fa-bookmark" style="font-size: 3rem; color: #10b981; margin-bottom: 15px;"></i>
                            <h3 style="color: var(--text-primary);">Mis Préstamos</h3>
                            <p style="color: var(--text-secondary); margin: 15px 0;">Revisa tus libros prestados</p>
                        </div>
                    </a>
                    
                    <a href="index.php?ruta=perfil" style="text-decoration: none;">
                        <div style="background: var(--bg-card); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px var(--shadow); transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-8px)'" onmouseout="this.style.transform='translateY(0)'">
                            <i class="fas fa-user-circle" style="font-size: 3rem; color: #f59e0b; margin-bottom: 15px;"></i>
                            <h3 style="color: var(--text-primary);">Mi Perfil</h3>
                            <p style="color: var(--text-secondary); margin: 15px 0;">Gestiona tu información</p>
                        </div>
                    </a>
                </div>
                
                <div style="margin-top: 60px; padding: 40px; background: var(--bg-secondary); border-radius: 15px;">
                    <h2 style="margin-bottom: 20px;"><i class="fas fa-chart-line"></i> Estadísticas</h2>
                    <div style="display: flex; justify-content: center; gap: 50px; flex-wrap: wrap;">
                        <div>
                            <p style="font-size: 3rem; font-weight: bold; color: var(--primary); margin: 0;"><?php echo $totalLibros; ?></p>
                            <p style="color: var(--text-secondary);">Libros Totales</p>
                        </div>
                        <div>
                            <p style="font-size: 3rem; font-weight: bold; color: #10b981; margin: 0;"><?php echo $librosDisponibles; ?></p>
                            <p style="color: var(--text-secondary);">Libros Disponibles</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>