<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Biblioteca CIF</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

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
    --success: #27ae60;
    --transition: all 0.3s ease;

    /* Light mode colors */
    --bg-primary: #ffffff;
    --bg-secondary: #f8f9fa;
    --bg-card: #ffffff;
    --text-primary: #2c3e50;
    --text-secondary: #5a6c7d;
    --border-color: #e1e8ed;
    --shadow: rgba(0, 0, 0, 0.1);
}

/* Dark mode */
[data-theme="dark"] {
    --bg-primary: #0a0f1e;
    --bg-secondary: #121829;
    --bg-card: #1a2332;
    --text-primary: #e5e7eb;
    --text-secondary: #9ca3af;
    --border-color: #2d3748;
    --shadow: rgba(0, 0, 0, 0.3);
    --primary: #3b82f6;
    --accent: #ef4444;
}

/* Original mode */
[data-theme="original"] {
    --bg-primary: #f0f4f8;
    --bg-secondary: #e3e8ef;
    --bg-card: #ffffff;
    --text-primary: #2c3e50;
    --text-secondary: #5a6c7d;
    --border-color: #d1dce5;
    --shadow: rgba(52, 152, 219, 0.15);
    --primary: #3498db;
    --secondary: #2c3e50;
    --accent: #e74c3c;
}

/* Premium mode */
[data-theme="premium"] {
    --bg-primary: #0f1419;
    --bg-secondary: #1a1f29;
    --bg-card: #1e2533;
    --text-primary: #c9d1d9;
    --text-secondary: #8b93a0;
    --border-color: #30363d;
    --shadow: rgba(56, 189, 248, 0.3);
    --primary: #38bdf8;
    --secondary: #0ea5e9;
    --accent: #60a5fa;
}

body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg-primary);
    color: var(--text-primary);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    transition: var(--transition);
}

/* Premium mode effects */
[data-theme="premium"] body {
    background: linear-gradient(135deg, #0f1419 0%, #1a1f29 50%, #0f1419 100%);
}

[data-theme="premium"] body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background:
        radial-gradient(circle at 20% 50%, rgba(56, 189, 248, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(96, 165, 250, 0.15) 0%, transparent 50%);
    animation: premium-glow 15s ease-in-out infinite alternate;
    pointer-events: none;
}

@keyframes premium-glow {
    0%, 100% { opacity: 0.8; }
    50% { opacity: 1; }
}

.login-container {
    width: 100%;
    max-width: 450px;
    padding: 20px;
    position: relative;
    z-index: 10;
}

.login-card {
    background: var(--bg-card);
    border-radius: 20px;
    padding: 50px 40px;
    box-shadow: 0 8px 40px var(--shadow);
    border: 2px solid var(--border-color);
    transition: var(--transition);
}

[data-theme="premium"] .login-card {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: rgba(56, 189, 248, 0.2);
    box-shadow: 0 8px 40px rgba(56, 189, 248, 0.3);
}

.login-header {
    text-align: center;
    margin-bottom: 40px;
}

.login-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.login-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.login-header p {
    color: var(--text-secondary);
    font-size: 0.95rem;
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
    opacity: 0.95;
}

.alert {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    font-weight: 500;
    text-align: center;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 2px solid #fecaca;
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
    transform: translateY(-5px) scale(1.05);
}

.theme-toggle-icon {
    font-size: 22px;
}

.theme-toggle-text {
    font-weight: 700;
    font-size: 15px;
    color: var(--text-primary);
}

@media (max-width: 500px) {
    .login-card {
        padding: 40px 30px;
    }

    .login-header h1 {
        font-size: 1.8rem;
    }

    .theme-toggle {
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
    }
}
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon"></div>
            <h1>Biblioteca CIF</h1>
            <p>Sistema de Gestión de Préstamos</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
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

<!-- Theme Toggle -->
<div class="theme-toggle" id="themeToggle">
    <span class="theme-toggle-icon"></span>
    <span class="theme-toggle-text">Modo Claro</span>
</div>

<script>
const themeToggle = document.getElementById('themeToggle');
const html = document.documentElement;
const themeIcon = themeToggle.querySelector('.theme-toggle-icon');
const themeText = themeToggle.querySelector('.theme-toggle-text');

const themes = {
    light: { icon: '', text: 'Modo Claro', next: 'dark' },
    dark: { icon: '', text: 'Modo Oscuro', next: 'original' },
    original: { icon: '', text: 'Modo Original', next: 'premium' },
    premium: { icon: '', text: 'Modo Premium', next: 'light' }
};

let currentTheme = localStorage.getItem('theme') || 'light';
html.setAttribute('data-theme', currentTheme);
updateThemeToggle(currentTheme);

themeToggle.addEventListener('click', function() {
    const nextTheme = themes[currentTheme].next;
    currentTheme = nextTheme;
    html.setAttribute('data-theme', nextTheme);
    localStorage.setItem('theme', nextTheme);
    updateThemeToggle(nextTheme);
});

function updateThemeToggle(theme) {
    const themeConfig = themes[theme];
    themeIcon.textContent = themeConfig.icon;
    themeText.textContent = themeConfig.text;
}
</script>

</body>
</html>
