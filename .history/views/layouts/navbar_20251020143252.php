<?php
/**
 * Componente: Barra de Navegación
 * Se incluye en todas las páginas después del login
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
.top-navbar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 0;
}

.navbar-brand {
    font-size: 1.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    color: white;
}

.navbar-menu {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.navbar-btn {
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    background: rgba(255,255,255,0.2);
    transition: all 0.3s;
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.navbar-btn:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    transform: translateY(-2px);
    text-decoration: none;
}

.navbar-btn.logout {
    background: #dc3545;
}

.navbar-btn.logout:hover {
    background: #c82333;
}

.navbar-user {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    padding: 8px 12px;
    background: rgba(255,255,255,0.15);
    border-radius: 8px;
}

@media (max-width: 768px) {
    .top-navbar {
        flex-direction: column;
        gap: 15px;
    }
    
    .navbar-menu {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="top-navbar">
    <div class="navbar-brand">
        <i class="fas fa-book-reader"></i>
        Biblioteca CIF
    </div>
    
    <div class="navbar-menu">
        <?php if (isset($_SESSION['admin_nombre'])): ?>
            <span class="navbar-user">
                <i class="fas fa-user-circle"></i>
                <?php echo htmlspecialchars($_SESSION['admin_nombre']); ?>
                <?php if (isset($_SESSION['admin_rol'])): ?>
                    <span style="opacity: 0.8; font-size: 12px;">(<?php echo ucfirst($_SESSION['admin_rol']); ?>)</span>
                <?php endif; ?>
            </span>
        <?php endif; ?>
        
        <a href="index.php?ruta=home" class="navbar-btn">
            <i class="fas fa-home"></i> Inicio
        </a>
        
        <a href="index.php?ruta=libros" class="navbar-btn">
            <i class="fas fa-book"></i> Libros
        </a>
        
        <a href="index.php?ruta=usuarios" class="navbar-btn">
            <i class="fas fa-users"></i> Usuarios
        </a>
        
        <a href="index.php?ruta=logout" class="navbar-btn logout" onclick="return confirm('¿Está seguro de cerrar sesión?')">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </a>
    </div>
</div>