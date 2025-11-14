<style>
.navbar-usuario {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

.navbar-brand-usuario {
    display: flex;
    align-items: center;
    gap: 15px;
    color: white;
    font-size: 1.3rem;
    font-weight: 700;
    text-decoration: none;
}

.navbar-menu-usuario {
    display: flex;
    gap: 20px;
    align-items: center;
}

.navbar-link-usuario {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

.navbar-link-usuario:hover {
    background: rgba(255,255,255,0.1);
    color: white;
}

.navbar-link-usuario.active {
    background: var(--primary-color);
    color: white;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 8px 15px;
    background: rgba(255,255,255,0.1);
    border-radius: 50px;
    color: white;
}

.btn-logout-usuario {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-logout-usuario:hover {
    background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
    transform: translateY(-2px);
}
</style>

<nav class="navbar-usuario">
    <a href="index.php?ruta=usuario/dashboard" class="navbar-brand-usuario">
        <i class="fas fa-book-reader"></i>
        Biblioteca CIF
    </a>
    
    <div class="navbar-menu-usuario">
        <a href="index.php?ruta=usuario/dashboard" class="navbar-link-usuario active">
            <i class="fas fa-home"></i> Mi Biblioteca
        </a>
        
        <a href="index.php?ruta=usuario/prestamos" class="navbar-link-usuario">
            <i class="fas fa-book"></i> Mis Préstamos
        </a>
        
        <a href="index.php?ruta=usuario/catalogo" class="navbar-link-usuario">
            <i class="fas fa-list"></i> Catálogo
        </a>
    </div>
    
    <div style="display: flex; align-items: center; gap: 15px;">
        <div class="user-info">
            <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
            <span><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></span>
        </div>
        
        <a href="index.php?ruta=logout" class="btn-logout-usuario">
            <i class="fas fa-sign-out-alt"></i> Salir
        </a>
    </div>
</nav>