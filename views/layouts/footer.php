</div> <!-- Cierre del contenedor principal si existe -->

<footer class="footer">
    <div class="footer-content">
        <p>&copy; <?php echo date('Y'); ?> Sistema de Biblioteca CIF. Todos los derechos reservados.</p>
        <p class="footer-author">Elaborado por Gestión de Infraestructura</p>
    </div>
</footer>

<style>
/* Hacer que el body ocupe toda la altura */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

/* Wrapper para empujar el footer abajo */
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* El contenido principal debe crecer para empujar el footer abajo */
body > *:not(.footer):not(.modern-navbar):not(.premium-effects):not(.premium-particles):not(.theme-toggle):not(.navbar-spacer) {
    flex: 1;
}

/* Footer pegado en la parte inferior */
.footer {
    background: var(--bg-card);
    border-top: 2px solid var(--border-color);
    padding: 20px;
    text-align: center;
    margin-top: auto;
    width: 100%;
    position: relative;
    z-index: 100;
}

[data-theme="premium"] .footer {
    background: linear-gradient(135deg, #1e2533 0%, #0f1419 100%);
    border-top: 2px solid rgba(56, 189, 248, 0.3);
    box-shadow: 0 -4px 20px rgba(56, 189, 248, 0.2);
}

.footer-content p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin: 5px 0;
    font-weight: 500;
}

.footer-author {
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--primary);
    margin-top: 8px;
}

[data-theme="premium"] .footer-content p {
    color: #e5e7eb;
}

[data-theme="premium"] .footer-author {
    color: #38bdf8;
    text-shadow: 0 0 10px rgba(56, 189, 248, 0.5);
    font-weight: 700;
}
</style>

</body>
</html>
