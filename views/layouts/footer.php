<footer style="
    background: transparent;
    color: var(--text-secondary);
    text-align: center;
    padding: 25px 20px;
    margin-top: 60px;
    border-top: none;
    box-shadow: none;
    position: relative;
    z-index: 100;
    width: 100%;
">
    <div style="max-width: 1200px; margin: 0 auto; line-height: 1.6;">
        <p style="margin: 0; font-size: 17px; font-weight: 600; color: var(--text-primary);">
             Gestión de Infraestructura
        </p>
        <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.85; font-weight: 500;">
             2025 Sistema de Biblioteca CIF. Todos los derechos reservados.
        </p>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
});
</script>

</body>
</html>
