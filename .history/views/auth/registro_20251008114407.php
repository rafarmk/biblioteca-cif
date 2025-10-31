<?php
require_once __DIR__ . '/../../config/config.php';

// Si ya está logueado, redirigir
if (isLoggedIn()) {
    $rol = getUserRole();
    if ($rol === 'administrador' || $rol === 'bibliotecario') {
        redirect('views/dashboard/admin.php');
    } else {
        redirect('views/dashboard/usuario.php');
    }
}

$csrf_token = generateCsrfToken();
$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .registro-container {
            max-width: 600px;
            width: 100%;
        }
        .registro-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .btn-registro {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-registro:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        .form-floating { margin-bottom: 20px; }
        .alert { border-radius: 12px; padding: 15px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="registro-container">
        <div class="registro-card">
            <h2 class="text-center mb-4">Registro de Usuario</h2>

            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : $flash['type']; ?>">
                    <?php echo $flash['message']; ?>
                </div>
            <?php endif; ?>

            <form action="/biblioteca-cif/auth/registrar" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <div class="form-floating">
                    <input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
                    <label>Nombre</label>
                </div>

                <div class="form-floating">
                    <input type="text" class="form-control" name="apellidos" placeholder="Apellidos" required>
                    <label>Apellidos</label>
                </div>

                <div class="form-floating">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                    <label>Email</label>
                </div>

                <div class="form-floating">
                    <input type="text" class="form-control" name="documento" placeholder="Documento" required>
                    <label>Documento</label>
                </div>

                <div class="form-floating">
                    <input type="text" class="form-control" name="telefono" placeholder="Teléfono" required>
                    <label>Teléfono</label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                    <label>Contraseña</label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" name="password_confirm" placeholder="Confirmar contraseña" required>
                    <label>Confirmar contraseña</label>
                </div>

                <button type="submit" class="btn btn-registro">
                    <i class="fas fa-user-plus me-2"></i>Registrarse
                </button>
            </form>
        </div>
    </div>
</body>
</html>
