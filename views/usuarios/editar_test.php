<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div style="padding: 40px;">
    <h1>Editar Usuario - TEST</h1>
    
    <?php 
    echo '<pre>';
    echo 'GET: ';
    print_r($_GET);
    echo '\nPOST: ';
    print_r($_POST);
    echo '\nUsuario: ';
    print_r($usuario ?? 'NO DEFINIDO');
    echo '</pre>';
    ?>
    
    <?php if (isset($error)): ?>
        <div style="background: red; color: white; padding: 20px;">
            ERROR: <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($usuario)): ?>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
            
            <div style="margin: 20px 0;">
                <label>Nombre:</label><br>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" style="width: 100%; padding: 10px;">
            </div>
            
            <div style="margin: 20px 0;">
                <label>Email:</label><br>
                <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" style="width: 100%; padding: 10px;">
            </div>
            
            <button type="submit" style="padding: 15px 30px; background: blue; color: white; border: none;">
                Actualizar
            </button>
        </form>
    <?php else: ?>
        <p style="color: red;">USUARIO NO DEFINIDO</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>