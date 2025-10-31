<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Biblioteca CIF</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #3498db;
            --secondary: #2c3e50;
            --accent: #e74c3c;
            --bg-primary: #ffffff;
            --bg-card: #ffffff;
            --text-primary: #2c3e50;
            --text-secondary: #5a6c7d;
            --border-color: #e1e8ed;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] {
            --bg-primary: #0a0f1e;
            --bg-card: #1a2332;
            --text-primary: #e5e7eb;
            --text-secondary: #9ca3af;
            --border-color: #2d3748;
            --shadow: rgba(0, 0, 0, 0.3);
            --primary: #3b82f6;
        }

        [data-theme="premium"] {
            --bg-primary: #0f1419;
            --bg-card: #1e2533;
            --text-primary: #c9d1d9;
            --text-secondary: #8b93a0;
            --border-color: #30363d;
            --shadow: rgba(56, 189, 248, 0.3);
            --primary: #38bdf8;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            padding: 40px 20px 120px;
        }

        .registro-container {
            width: 100%;
            max-width: 600px;
            z-index: 10;
        }

        .registro-card {
            background: var(--bg-card);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 50px var(--shadow);
            border: 2px solid var(--border-color);
        }

        .registro-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .registro-icon {
            font-size: 3.5rem;
            margin-bottom: 15px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .registro-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .registro-header p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 13px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-registro {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-registro:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
        }

        .alert-error {
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #fecaca;
            font-size: 14px;
        }

        .alert-success {
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            background: #d1fae5;
            color: #065f46;
            border: 2px solid #a7f3d0;
            font-size: 14px;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .theme-toggle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 10000;
            background: var(--bg-card);
            border: 2px solid var(--border-color);
            border-radius: 50px;
            padding: 14px 26px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 30px var(--shadow);
            transition: all 0.4s ease;
        }

        .theme-toggle:hover {
            transform: translateY(-5px);
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            background: var(--bg-card);
            color: var(--text-secondary);
            text-align: center;
            padding: 15px;
            border-top: 3px solid var(--primary);
            box-shadow: 0 -6px 25px var(--shadow);
            z-index: 100;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .registro-card {
                padding: 30px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="registro-container">
        <div class="registro-card">
            <div class="registro-header">
                <div class="registro-icon">📝</div>
                <h1>Crear Cuenta</h1>
                <p>Regístrate para solicitar préstamos de libros</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert-error">
                    ⚠️ <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert-success">
                    ✅ <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?ruta=registro">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">👤 Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" required
                               placeholder="Juan Pérez" value="<?php echo $_POST['nombre'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">📧 Correo Electrónico</label>
                        <input type="email" id="email" name="email" required
                               placeholder="usuario@email.com" value="<?php echo $_POST['email'] ?? ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefono">📱 Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" required
                               placeholder="7777-7777" value="<?php echo $_POST['telefono'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="dui">🪪 DUI</label>
                        <input type="text" id="dui" name="dui" required
                               placeholder="00000000-0" value="<?php echo $_POST['dui'] ?? ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="direccion">🏠 Dirección</label>
                    <input type="text" id="direccion" name="direccion" required
                           placeholder="Calle, Colonia, Ciudad" value="<?php echo $_POST['direccion'] ?? ''; ?>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">🔒 Contraseña</label>
                        <input type="password" id="password" name="password" required
                               placeholder="Mínimo 6 caracteres" minlength="6">
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">🔒 Confirmar Contraseña</label>
                        <input type="password" id="password_confirm" name="password_confirm" required
                               placeholder="Repetir contraseña" minlength="6">
                    </div>
                </div>

                <div class="form-group">
                    <label for="tipo_usuario">👥 Tipo de Usuario</label>
                    <select id="tipo_usuario" name="tipo_usuario" required>
                        <option value="estudiante_mayor">Estudiante (Mayor de edad)</option>
                        <option value="estudiante_menor">Estudiante (Menor de edad)</option>
                        <option value="visitante">Visitante</option>
                        <option value="personal_administrativo">Personal Administrativo</option>
                        <option value="personal_operativo">Personal Operativo</option>
                    </select>
                </div>

                <button type="submit" class="btn-registro">
                    ✅ Crear Cuenta
                </button>

                <div class="login-link">
                    ¿Ya tienes cuenta? <a href="index.php?ruta=login">Iniciar Sesión</a>
                </div>
            </form>
        </div>
    </div>

    <div class="theme-toggle" id="themeToggle">
        <span style="font-size: 22px;">☀️</span>
        <span style="font-weight: 700; font-size: 15px; color: var(--text-primary);">Modo Claro</span>
    </div>

    <footer>
        <p>📚 2025 Sistema de Biblioteca CIF. Todos los derechos reservados.</p>
    </footer>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        const themeIcon = themeToggle.querySelector('span:first-child');
        const themeText = themeToggle.querySelector('span:last-child');

        const themes = {
            light: { icon: '☀️', text: 'Modo Claro', next: 'dark' },
            dark: { icon: '🌙', text: 'Modo Oscuro', next: 'premium' },
            premium: { icon: '✨', text: 'Modo Premium', next: 'light' }
        };

        let currentTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', currentTheme);
        updateTheme(currentTheme);

        themeToggle.addEventListener('click', function() {
            currentTheme = themes[currentTheme].next;
            html.setAttribute('data-theme', currentTheme);
            localStorage.setItem('theme', currentTheme);
            updateTheme(currentTheme);
        });

        function updateTheme(theme) {
            themeIcon.textContent = themes[theme].icon;
            themeText.textContent = themes[theme].text;
        }
    </script>
</body>
</html>