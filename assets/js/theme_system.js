// ============================================
// SISTEMA DE TEMAS PROFESIONALES
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Elementos
    const themeToggleBtn = document.getElementById('themeToggleBtn');
    const themeDropdown = document.getElementById('themeDropdown');
    const themeOptions = document.querySelectorAll('.theme-option');
    const html = document.documentElement;

    // Cargar tema guardado o usar Dark por defecto
    const savedTheme = localStorage.getItem('biblioteca-theme') || 'dark';
    setTheme(savedTheme);

    // Toggle dropdown
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            themeDropdown.classList.toggle('active');
        });
    }

    // Cerrar dropdown al hacer click fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.theme-selector-wrapper')) {
            if (themeDropdown) {
                themeDropdown.classList.remove('active');
            }
        }
    });

    // Cambiar tema al hacer click en una opción
    themeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const theme = this.getAttribute('data-theme');
            setTheme(theme);
            if (themeDropdown) {
                themeDropdown.classList.remove('active');
            }
        });
    });

    // Función para establecer el tema
    function setTheme(theme) {
        // Aplicar tema al HTML
        html.setAttribute('data-theme', theme);
        
        // Guardar en localStorage
        localStorage.setItem('biblioteca-theme', theme);
        
        // Actualizar checks en el dropdown
        themeOptions.forEach(option => {
            const check = option.querySelector('.theme-check');
            if (option.getAttribute('data-theme') === theme) {
                option.classList.add('active');
                if (check) check.style.display = 'block';
            } else {
                option.classList.remove('active');
                if (check) check.style.display = 'none';
            }
        });

        // Actualizar icono del botón según el tema
        updateThemeIcon(theme);
    }

    // Actualizar icono del botón de temas
    function updateThemeIcon(theme) {
        const themeIcon = document.querySelector('.theme-icon');
        if (!themeIcon) return;

        const icons = {
            'dark': '🌙',
            'midnight': '🌌',
            'carbon': '⚫'
        };

        themeIcon.textContent = icons[theme] || '🎨';
    }
});