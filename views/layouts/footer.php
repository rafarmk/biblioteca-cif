<!-- Footer del Sistema -->
<footer style="
    background: var(--bg-secondary);
    color: var(--text-secondary);
    padding: 30px 20px;
    margin-top: 60px;
    border-top: 2px solid var(--border-color);
    text-align: center;
">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 15px;">
            <h3 style="color: var(--text-primary); font-size: 1.3rem; margin-bottom: 10px;">
                🔬 Sistema de Gestión Bibliotecaria
            </h3>
            <p style="font-size: 0.95rem; margin-bottom: 5px;">
                Laboratorio Científico Forense - Policía Nacional Civil de El Salvador
            </p>
            <p style="font-size: 0.9rem; color: var(--accent-primary);">
                Desarrollado por Gestión de Infraestructura
            </p>
        </div>
        <div style="
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
            font-size: 0.85rem;
        ">
            © 2025 - Todos los derechos reservados
        </div>
    </div>
</footer>

<!-- JavaScript de Temas -->
<script src="assets/js/theme_system.js"></script>

<!-- JavaScript adicional específico de la página -->
<?php if (isset($extra_js)): ?>
    <script><?php echo $extra_js; ?></script>
<?php endif; ?>

</body>
</html>