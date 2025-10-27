<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Biblioteca CIF</title>
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
            padding-bottom: 100px;
        }

        .login-container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            z-index: 10;
        }

        .login-card {
            background: var(--bg-card);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 10px 50px var(--shadow);
            border: 2px solid var(--border-color);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-icon {
            font-size: 4.5rem;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .login-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
        }

        .alert-error {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #fecaca;
            text-align: center;
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
            padding: 20px;
            border-top: 3px solid var(--primary);
            box-shadow: 0 -6px 25px var(--shadow);
            z-index: 100;
        }

        footer p:first-child {
            margin: 0;
            font-size: 17px;
            font-weight: 600;
            color: var(--text-primary);
        }

        footer p:last-child {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.85;
        }

        @media (max-width: 600px) {
            .login-card {
                padding: 40px 30px;
            }
            
            body {
                padding-bottom: 120px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon"></div>
                <h1>Biblioteca CIF</h1>
                <p>Sistema de Gestión de Préstamos</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?ruta=login">
                <div class="form-group">
                    <label for="email"> Correo Electrónico</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="admin@biblioteca.com" autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password"> Contraseña</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="" autocomplete="current-password">
                </div>

                <button type="submit" class="btn-login">
                     Iniciar Sesión
                </button>
            </form>
        </div>
    </div>

    <div class="theme-toggle" id="themeToggle">
        <span style="font-size: 22px;"></span>
        <span style="font-weight: 700; font-size: 15px; color: var(--text-primary);">Modo Claro</span>
    </div>

    <footer>
        <p> Gestión de Infraestructura</p>
        <p> 2025 Sistema de Biblioteca CIF. Todos los derechos reservados.</p>
    </footer>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        const themeIcon = themeToggle.querySelector('span:first-child');
        const themeText = themeToggle.querySelector('span:last-child');

        const themes = {
            light: { icon: '', text: 'Modo Claro', next: 'dark' },
            dark: { icon: '', text: 'Modo Oscuro', next: 'premium' },
            premium: { icon: '', text: 'Modo Premium', next: 'light' }
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
