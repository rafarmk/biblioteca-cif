<?php
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header('Location: index.php?ruta=login');
    exit();
}

require_once __DIR__ . '/../layouts/navbar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario - Biblioteca CIF</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1f3a 0%, #2d3561 100%);
            min-height: 100vh;
            color: #fff;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 80px 20px 40px;
        }
        
        .card {
            background: rgba(45, 53, 97, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(91, 143, 215, 0.2);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        h1 {
            color: #5b8fd7;
            margin-bottom: 30px;
            font-size: 2rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #b8c5d6;
            font-weight: 600;
        }
        
        label small {
            color: #7f8c8d;
            font-weight: 400;
            font-size: 0.85em;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            background: rgba(26, 31, 58, 0.5);
            border: 1px solid rgba(91, 143, 215, 0.3);
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #5b8fd7;
        }
        
        .btn {
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #5b8fd7 0%, #4a7bc4 100%);
            color: white;
        }
        
        .btn-secondary {
            background: rgba(91, 143, 215, 0.2);
            color: #5b8fd7;
            border: 2px solid #5b8fd7;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid #e74c3c;
            color: #e74c3c;
        }
        
        .alert-info {
            background: rgba(52, 152, 219, 0.2);
            border: 1px solid #3498db;
            color: #3498db;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 0.85em;
            margin-top: 5px;
            display: none;
        }
        
        input.invalid {
            border-color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>➕ Nuevo Usuario</h1>
            
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?>">
                    <?= htmlspecialchars($_SESSION['mensaje']['texto']) ?>
                </div>
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?>
            
            <form method="POST" action="index.php?ruta=usuarios&accion=crear" id="formUsuario">
                <div class="row">
                    <div class="form-group">
                        <label>Nombre Completo *</label>
                        <input type="text" name="nombre" id="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Tipo de Usuario *</label>
                    <select name="tipo_usuario" id="tipo_usuario" required>
                        <option value="">Seleccione...</option>
                        <option value="administrador">Administrador</option>
                        <option value="gestionador">Gestionador</option>
                        <option value="personal_administrativo">Personal Administrativo</option>
                        <option value="personal_operativo">Personal Operativo</option>
                        <option value="estudiante_mayor">Estudiante Mayor</option>
                        <option value="estudiante_menor">Estudiante Menor</option>
                        <option value="visitante">Visitante</option>
                    </select>
                </div>
                
                <!-- Campo DUI/ONI/Código (dinámico) -->
                <div class="form-group" id="campo_dui">
                    <label id="label_dui">DUI / ONI / Código</label>
                    <input type="text" name="dui" id="dui" placeholder="">
                    <small class="error-message" id="error_dui"></small>
                </div>
                
                <div class="alert alert-info" id="info_validacion" style="display:none;">
                    <span id="texto_validacion"></span>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="tel" name="telefono" id="telefono" placeholder="0000-0000">
                    </div>
                    
                    <div class="form-group">
                        <label>Dirección</label>
                        <textarea name="direccion" id="direccion" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label>Contraseña * <small>(mínimo 6 caracteres)</small></label>
                        <input type="password" name="password" id="password" minlength="6" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Confirmar Contraseña *</label>
                        <input type="password" name="password_confirm" id="password_confirm" minlength="6" required>
                        <small class="error-message" id="error_password">Las contraseñas no coinciden</small>
                    </div>
                </div>
                
                <div style="display:flex;gap:15px;margin-top:30px;">
                    <button type="submit" class="btn btn-primary">
                        ✅ Guardar Usuario
                    </button>
                    <a href="index.php?ruta=usuarios" class="btn btn-secondary">
                        ❌ Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const tipoUsuario = document.getElementById('tipo_usuario');
        const campoDui = document.getElementById('dui');
        const labelDui = document.getElementById('label_dui');
        const errorDui = document.getElementById('error_dui');
        const infoValidacion = document.getElementById('info_validacion');
        const textoValidacion = document.getElementById('texto_validacion');
        
        // Validaciones por tipo de usuario
        const validaciones = {
            'visitante': {
                label: 'DUI *',
                placeholder: '00000000-0',
                regex: /^\d{8}-\d$/,
                mensaje: '📋 Formato: 8 dígitos - 1 dígito (ejemplo: 12345678-9)',
                error: 'DUI inválido. Formato: 00000000-0'
            },
            'personal_administrativo': {
                label: 'ONI *',
                placeholder: 'AB123456',
                regex: /^[A-Z]{2}\d+$/,
                mensaje: '📋 Formato: 2 letras mayúsculas + números (ejemplo: AB123456)',
                error: 'ONI inválido. Debe iniciar con 2 letras mayúsculas seguidas de números'
            },
            'personal_operativo': {
                label: 'Código *',
                placeholder: '123456',
                regex: /^\d{6,}$/,
                mensaje: '📋 Formato: Mínimo 6 dígitos numéricos',
                error: 'Código inválido. Debe tener al menos 6 dígitos'
            }
        };
        
        // Cambiar validación según tipo de usuario
        tipoUsuario.addEventListener('change', function() {
            const tipo = this.value;
            
            if (validaciones[tipo]) {
                const validacion = validaciones[tipo];
                labelDui.textContent = validacion.label;
                campoDui.placeholder = validacion.placeholder;
                campoDui.required = true;
                textoValidacion.textContent = validacion.mensaje;
                infoValidacion.style.display = 'block';
                campoDui.dataset.regex = validacion.regex.source;
                campoDui.dataset.error = validacion.error;
            } else {
                labelDui.textContent = 'DUI / ONI / Código';
                campoDui.placeholder = '';
                campoDui.required = false;
                infoValidacion.style.display = 'none';
                campoDui.dataset.regex = '';
            }
            
            // Limpiar errores
            campoDui.classList.remove('invalid');
            errorDui.style.display = 'none';
        });
        
        // Validar en tiempo real
        campoDui.addEventListener('input', function() {
            const tipo = tipoUsuario.value;
            
            if (validaciones[tipo] && this.value) {
                const regex = new RegExp(this.dataset.regex);
                
                if (!regex.test(this.value)) {
                    this.classList.add('invalid');
                    errorDui.textContent = this.dataset.error;
                    errorDui.style.display = 'block';
                } else {
                    this.classList.remove('invalid');
                    errorDui.style.display = 'none';
                }
            }
        });
        
        // Validar contraseñas
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');
        const errorPassword = document.getElementById('error_password');
        
        passwordConfirm.addEventListener('input', function() {
            if (this.value && this.value !== password.value) {
                this.classList.add('invalid');
                errorPassword.style.display = 'block';
            } else {
                this.classList.remove('invalid');
                errorPassword.style.display = 'none';
            }
        });
        
        // Validar al enviar
        document.getElementById('formUsuario').addEventListener('submit', function(e) {
            const tipo = tipoUsuario.value;
            
            // Validar contraseñas
            if (password.value !== passwordConfirm.value) {
                e.preventDefault();
                passwordConfirm.classList.add('invalid');
                errorPassword.style.display = 'block';
                alert('Las contraseñas no coinciden');
                return false;
            }
            
            // Validar DUI/ONI/Código según tipo
            if (validaciones[tipo]) {
                const regex = new RegExp(validaciones[tipo].regex);
                if (!regex.test(campoDui.value)) {
                    e.preventDefault();
                    campoDui.classList.add('invalid');
                    errorDui.textContent = validaciones[tipo].error;
                    errorDui.style.display = 'block';
                    alert(validaciones[tipo].error);
                    return false;
                }
            }
        });
    </script>
</body>
</html>