<?php
/**
 * DashboardController
 * Maneja los dashboards de administrador y usuario
 * Sistema de Biblioteca CIF
 */

require_once __DIR__ . '/../config/config.php';

class DashboardController {
    
    public function admin() {
        // Verificar que el usuario esté autenticado y tenga rol de admin
        if (!isLoggedIn()) {
            setFlashMessage('warning', 'Debes iniciar sesión para acceder');
            redirect('auth/login');
            exit();
        }
        
        $rol = getUserRole();
        if ($rol !== 'administrador' && $rol !== 'bibliotecario') {
            setFlashMessage('error', 'No tienes permisos para acceder a esta sección');
            redirect('dashboard/usuario');
            exit();
        }
        
        require_once __DIR__ . '/../views/dashboard/admin.php';
    }
    
    public function usuario() {
        // Verificar que el usuario esté autenticado
        if (!isLoggedIn()) {
            setFlashMessage('warning', 'Debes iniciar sesión para acceder');
            redirect('auth/login');
            exit();
        }
        
        require_once __DIR__ . '/../views/dashboard/usuario.php';
    }
}