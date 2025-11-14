<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - Biblioteca CIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        .registro-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 2rem;
            text-align: center;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="registro-container w-100">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-2">
                    <i class="fas fa-book-reader"></i> Registro de Usuario
                </h3>
                <p class="mb-0">Crea tu cuenta para acceder a la Biblioteca CIF</p>
            </div>
            <div class="card-body p-4">
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?> alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['mensaje']['texto']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['mensaje']); ?>
                <?php endif; ?>

                <form action="controllers/SolicitudController.php" method="POST" id="formRegistro">
                    <input type="hidden" name="accion" value="crear">

                    <!-- Nombre Completo -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Nombre Completo <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="nombre" 
                               placeholder="Tu nombre completo"
                               required>
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Correo Electrónico <span class="text-danger">*</span>
                        </label>
                        <input type="email" 
                               class="form-control" 
                               name="email" 
                               placeholder="ejemplo@correo.com"
                               required>
                    </div>

                    <!-- Carnet/Código -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Carnet/Código
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="carnet" 
                               placeholder="Tu número de carnet (opcional)">
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Teléfono
                        </label>
                        <input type="tel" 
                               class="form-control" 
                               name="telefono" 
                               placeholder="0000-0000">
                    </div>

                    <!-- Tipo de Usuario -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Tipo de Usuario <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" name="tipo_usuario" required>
                            <option value="">Seleccione...</option>
                            <option value="visitante">Visitante</option>
                            <option value="personal_operativo">Personal Operativo</option>
                            <option value="personal_administrativo">Personal Administrativo</option>
                        </select>
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-lock"></i> Crear Contraseña
                        </label>
                        <small class="text-muted d-block mb-2">La contraseña debe tener al menos 6 caracteres</small>
                        <input type="password" 
                               class="form-control" 
                               name="password" 
                               id="password"
                               placeholder="Mínimo 6 caracteres"
                               minlength="6"
                               required>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            Confirmar Contraseña <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                               class="form-control" 
                               name="password_confirm" 
                               id="password_confirm"
                               placeholder="Repite tu contraseña"
                               minlength="6"
                               required>
                        <div class="invalid-feedback">Las contraseñas no coinciden</div>
                    </div>

                    <!-- Botones -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus"></i> Crear Cuenta
                        </button>
                        <a href="index.php?ruta=landing" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Inicio
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-3">
            <p class="text-white">
                ¿Ya tienes cuenta? 
                <a href="index.php?ruta=login" class="text-white fw-bold">Inicia Sesión</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validar que las contraseñas coincidan
        document.getElementById('formRegistro').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            
            if (password !== passwordConfirm) {
                e.preventDefault();
                document.getElementById('password_confirm').classList.add('is-invalid');
                alert('Las contraseñas no coinciden');
                return false;
            }
        });

        // Remover clase de error al escribir
        document.getElementById('password_confirm').addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    </script>
</body>
</html>