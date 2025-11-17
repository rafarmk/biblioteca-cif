<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si ya está logueado, redirigir
if (isset($_SESSION['logueado'])) {
    if ($_SESSION['tipo_usuario'] === 'admin') {
        header('Location: index.php?ruta=home');
    } else {
        header('Location: index.php?ruta=catalogo');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Biblioteca CIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .register-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .register-header p {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .register-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .register-body {
            padding: 40px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-group label .required {
            color: #ef4444;
            margin-left: 3px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #f7fafc;
            font-family: inherit;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group small {
            display: block;
            margin-top: 5px;
            color: #64748b;
            font-size: 0.85rem;
        }

        .btn-register {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            margin-top: 10px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #fca5a5;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 2px solid #6ee7b7;
        }

        .register-footer {
            text-align: center;
            padding: 20px 30px;
            background: #f7fafc;
            border-top: 1px solid #e2e8f0;
        }

        .register-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }

        .info-box {
            background: #e0e7ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 0.9rem;
            color: #4338ca;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .register-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <div class="register-icon">🔬</div>
            <h1>Registro de Usuario</h1>
            <p>Laboratorio Científico Forense - PNC El Salvador</p>
        </div>

        <div class="register-body">
            <div class="info-box">
                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Su cuenta será revisada y aprobada por un administrador antes de poder acceder al sistema.
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form method="POST" action="index.php?ruta=registro">
                <div class="form-row">
                    <div class="form-group">
                        <label>
                            <i class="fas fa-user"></i> Nombre
                            <span class="required">*</span>
                        </label>
                        <input type="text" name="nombre" required placeholder="Ej: Juan">
                        <small>Nombre completo o primer nombre</small>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-user"></i> Apellido
                            <span class="required">*</span>
                        </label>
                        <input type="text" name="apellido" required placeholder="Ej: Pérez García">
                        <small>Apellidos completos</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>
                            <i class="fas fa-envelope"></i> Correo Electrónico
                            <span class="required">*</span>
                        </label>
                        <input type="email" name="email" required placeholder="correo@ejemplo.com">
                        <small>Será su usuario para iniciar sesión</small>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-phone"></i> Teléfono
                        </label>
                        <input type="tel" name="telefono" placeholder="2234-5678">
                        <small>Número de contacto (opcional)</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>
                            <i class="fas fa-id-card"></i> DUI
                        </label>
                        <input type="text" name="dui" placeholder="12345678-9" maxlength="10">
                        <small>Documento Único de Identidad (opcional)</small>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-lock"></i> Contraseña
                            <span class="required">*</span>
                        </label>
                        <input type="password" name="password" required minlength="6" placeholder="Mínimo 6 caracteres">
                        <small>Debe tener al menos 6 caracteres</small>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label>
                        <i class="fas fa-map-marker-alt"></i> Dirección
                    </label>
                    <textarea name="direccion" placeholder="Dirección completa (opcional)"></textarea>
                    <small>Dirección de residencia</small>
                </div>

                <div class="form-group full-width">
                    <label>
                        <i class="fas fa-users"></i> Tipo de Usuario
                        <span class="required">*</span>
                    </label>
                    <select name="tipo_usuario" required>
                        <option value="">Seleccione su tipo de usuario...</option>
                        <option value="visitante">👥 Visitas en General - Acceso a consulta de materiales</option>
                        <option value="personal_operativo">👮 Personal Policial Operativo - Personal de campo y técnico especializado</option>
                        <option value="personal_administrativo">📋 Personal Administrativo - Personal de oficina y gestión</option>
                    </select>
                    <small>Seleccione el tipo que mejor describa su rol en la institución</small>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i> Registrarse Ahora
                </button>
            </form>
        </div>

        <div class="register-footer">
            <p style="margin-bottom: 10px; color: #4a5568;">
                ¿Ya tienes una cuenta?
            </p>
            <a href="index.php?ruta=login">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </a>
        </div>
    </div>

    <div style="
        position: fixed;
        bottom: 10px;
        left: 0;
        right: 0;
        text-align: center;
        color: white;
        font-size: 0.8rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    ">
        Desarrollado por <strong>Gestión de Infraestructura</strong> | © <?= date('Y') ?> Laboratorio Científico Forense - PNC
    </div>
</body>
</html>