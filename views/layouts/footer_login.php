<footer style="
    background: var(--bg-card);
    color: var(--text-secondary);
    text-align: center;
    padding: 45px 30px;
    margin-top: 80px;
    border-top: 4px solid var(--primary);
    box-shadow: 0 -8px 35px var(--shadow);
    position: relative;
    z-index: 100;
">
    <div style="max-width: 1200px; margin: 0 auto; line-height: 1.8;">
        <p style="margin: 0; font-size: 20px; font-weight: 700; color: var(--text-primary); letter-spacing: 0.5px;">
             Gestión de Infraestructura
        </p>
        <p style="margin: 12px 0 0 0; font-size: 16px; opacity: 0.9; font-weight: 500;">
             2025 Sistema de Biblioteca CIF. Todos los derechos reservados.
        </p>
    </div>
</footer>

<script>
// Mantener el tema sincronizado con localStorage en todas las páginas
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
});
</script>
