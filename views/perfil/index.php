<?php require_once 'views/layouts/navbar.php'; ?>

<style>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.profile-card {
    background: var(--bg-card);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--border-color);
}

.profile-avatar {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
    margin: 0 auto 25px;
    font-weight: 700;
    box-shadow: 0 8px 30px rgba(59, 130, 246, 0.4);
}

.profile-name {
    text-align: center;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.profile-type {
    text-align: center;
    font-size: 1rem;
    color: var(--text-secondary);
    margin-bottom: 30px;
}

.profile-info {
    display: grid;
    gap: 20px;
}

.info-row {
    display: flex;
    align-items: center;
    padding: 18px;
    background: var(--bg-secondary);
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.info-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-right: 20px;
}

.info-content {
    flex: 1;
}

.info-label {
    font-size: 0.85rem;
    color: var(--text-secondary);
    font-weight: 600;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 1.05rem;
    color: var(--text-primary);
    font-weight: 500;
}

.profile-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.profile-actions a {
    flex: 1;
}
</style>

<div class="container">
    <div class="page-header">
        <h1 class="page-title"> Mi Perfil</h1>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success">
            <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
            <div class="alert-content"><?php echo htmlspecialchars($_SESSION['mensaje']); unset($_SESSION['mensaje']); ?></div>
        </div>
    <?php endif; ?>

    <div class="profile-card">
        <div class="profile-avatar">
            <?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?>
        </div>
        <div class="profile-name"><?php echo htmlspecialchars($usuario['nombre']); ?></div>
        <div class="profile-type">
            <span class="badge badge-<?php echo $usuario['estado']; ?>" style="font-size: 0.95rem;">
                <?php echo ucfirst(str_replace('_', ' ', $usuario['tipo_usuario'])); ?>
            </span>
        </div>

        <div class="profile-info">
            <div class="info-row">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo htmlspecialchars($usuario['email']); ?></div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Teléfono</div>
                    <div class="info-value"><?php echo htmlspecialchars($usuario['telefono'] ?? 'No especificado'); ?></div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">DUI</div>
                    <div class="info-value"><?php echo htmlspecialchars($usuario['dui'] ?? 'No especificado'); ?></div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Dirección</div>
                    <div class="info-value"><?php echo htmlspecialchars($usuario['direccion'] ?? 'No especificada'); ?></div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Fecha de Registro</div>
                    <div class="info-value"><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Estado de la Cuenta</div>
                    <div class="info-value">
                        <span class="badge badge-<?php echo $usuario['estado']; ?>">
                            <?php echo ucfirst($usuario['estado']); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-actions">
            <a href="index.php?ruta=perfil&accion=editar" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar Perfil
            </a>
            <a href="index.php?ruta=catalogo" class="btn btn-info">
                <i class="fas fa-book"></i> Ver Catálogo
            </a>
        </div>
    </div>
</div>

</body>
</html>
