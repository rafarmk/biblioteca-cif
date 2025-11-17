<footer class="modern-footer">
    <div class="footer-content">
        <div class="footer-text">
            <strong>🔬 Sistema de Gestión Bibliotecaria</strong>
            <span class="footer-separator">|</span>
            <span>Laboratorio Científico Forense - PNC El Salvador</span>
        </div>
        <div class="footer-credits">
            Desarrollado por <strong>Gestión de Infraestructura</strong>
            <span class="footer-separator">|</span>
            © <?= date('Y') ?> - Todos los derechos reservados
        </div>
    </div>
</footer>

<style>
.modern-footer {
    background: var(--bg-card);
    border-top: 3px solid var(--primary);
    padding: 25px 20px;
    margin-top: auto;
    box-shadow: 0 -6px 20px var(--shadow);
    transition: var(--transition);
}

.footer-content {
    max-width: 1400px;
    margin: 0 auto;
    text-align: center;
    font-size: 0.9rem;
    line-height: 2;
}

.footer-text {
    margin-bottom: 10px;
    color: var(--text-primary);
    font-weight: 600;
}

.footer-text strong {
    color: var(--primary);
    font-weight: 700;
    font-size: 1.05rem;
}

.footer-credits {
    font-size: 0.85rem;
    color: var(--text-secondary);
    font-weight: 500;
}

.footer-credits strong {
    color: var(--primary);
    font-weight: 700;
}

.footer-separator {
    margin: 0 12px;
    opacity: 0.6;
    font-weight: 300;
}

@media (max-width: 768px) {
    .footer-content {
        font-size: 0.8rem;
    }
    .footer-separator {
        display: none;
    }
    .footer-text, .footer-credits {
        display: block;
    }
}
</style>