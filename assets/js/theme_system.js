/* ===================================================================
   JAVASCRIPT PARA MANEJO DE TEMAS - BIBLIOTECA CIF
   Solución para problemas 2 y 3: Aplicación global y dropdown mejorado
   =================================================================== */

// Configuración de temas disponibles
const themes = {
    dark: {
        name: 'Oscuro',
        colors: ['#667eea', '#764ba2'],
        icon: '🌙'
    },
    light: {
        name: 'Claro',
        colors: ['#667eea', '#764ba2'],
        icon: '☀️'
    },
    blue: {
        name: 'Azul',
        colors: ['#0ea5e9', '#0284c7'],
        icon: '🌊'
    },
    green: {
        name: 'Verde',
        colors: ['#10b981', '#059669'],
        icon: '🌿'
    },
    purple: {
        name: 'Morado',
        colors: ['#a855f7', '#9333ea'],
        icon: '🔮'
    }
};

// Variables globales
let currentTheme = 'dark';
let dropdownTimeout = null;

/**
 * SOLUCIÓN PROBLEMA 2: Aplicar tema a TODO el sistema
 */
function applyThemeGlobally(themeName) {
    // Guardar el tema actual
    currentTheme = themeName;
    
    // Aplicar el atributo data-theme al elemento root
    document.documentElement.setAttribute('data-theme', themeName);
    
    // Guardar en localStorage para persistencia
    localStorage.setItem('bibliotecaCifTheme', themeName);
    
    // Aplicar a todos los iframes (si existen)
    const iframes = document.querySelectorAll('iframe');
    iframes.forEach(iframe => {
        try {
            if (iframe.contentDocument) {
                iframe.contentDocument.documentElement.setAttribute('data-theme', themeName);
            }
        } catch (e) {
            console.warn('No se pudo aplicar tema al iframe:', e);
        }
    });
    
    // Disparar evento personalizado para módulos que lo necesiten
    const event = new CustomEvent('themeChanged', { 
        detail: { theme: themeName } 
    });
    document.dispatchEvent(event);
    
    // Actualizar la UI del selector
    updateThemeSelector(themeName);
    
    console.log(`Tema ${themeName} aplicado globalmente`);
}

/**
 * Actualizar la interfaz del selector de temas
 */
function updateThemeSelector(themeName) {
    // Actualizar botón principal
    const themeBtn = document.getElementById('themeToggleBtn');
    if (themeBtn) {
        const themeIcon = themeBtn.querySelector('.theme-icon');
        if (themeIcon) {
            themeIcon.textContent = themes[themeName].icon;
        }
    }
    
    // Actualizar opciones (marcar la activa)
    document.querySelectorAll('.theme-option').forEach(option => {
        option.classList.remove('active');
        const checkIcon = option.querySelector('.theme-check');
        if (checkIcon) {
            checkIcon.style.display = 'none';
        }
    });
    
    const activeOption = document.querySelector(`.theme-option[data-theme="${themeName}"]`);
    if (activeOption) {
        activeOption.classList.add('active');
        const checkIcon = activeOption.querySelector('.theme-check');
        if (checkIcon) {
            checkIcon.style.display = 'block';
        }
    }
}

/**
 * SOLUCIÓN PROBLEMA 3: Dropdown que no se oculta rápidamente
 */
function initThemeSelector() {
    const themeBtn = document.getElementById('themeToggleBtn');
    const themeDropdown = document.getElementById('themeDropdown');
    const themeWrapper = document.querySelector('.theme-selector-wrapper');
    
    if (!themeBtn || !themeDropdown) {
        console.error('Elementos del selector de temas no encontrados');
        return;
    }
    
    // Toggle del dropdown al hacer clic en el botón
    themeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const isActive = themeDropdown.classList.contains('active');
        
        if (isActive) {
            closeThemeDropdown();
        } else {
            openThemeDropdown();
        }
    });
    
    // Mantener abierto cuando el mouse está sobre el wrapper o dropdown
    themeWrapper.addEventListener('mouseenter', function() {
        if (dropdownTimeout) {
            clearTimeout(dropdownTimeout);
            dropdownTimeout = null;
        }
    });
    
    themeWrapper.addEventListener('mouseleave', function() {
        // Esperar 500ms antes de cerrar (tiempo suficiente para que el usuario mueva el mouse)
        dropdownTimeout = setTimeout(() => {
            closeThemeDropdown();
        }, 3000);
    });
    
    // Mantener abierto cuando el mouse está sobre el dropdown
    themeDropdown.addEventListener('mouseenter', function() {
        if (dropdownTimeout) {
            clearTimeout(dropdownTimeout);
            dropdownTimeout = null;
        }
    });
    
    themeDropdown.addEventListener('mouseleave', function() {
        dropdownTimeout = setTimeout(() => {
            closeThemeDropdown();
        }, 500);
    });
    
    // Event listeners para cada opción de tema
    document.querySelectorAll('.theme-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.stopPropagation();
            const themeName = this.getAttribute('data-theme');
            applyThemeGlobally(themeName);
            
            // Cerrar dropdown después de seleccionar (con pequeño delay)
            setTimeout(() => {
                closeThemeDropdown();
            }, 300);
        });
    });
    
    // Cerrar dropdown al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!themeWrapper.contains(e.target)) {
            closeThemeDropdown();
        }
    });
    
    // Cerrar con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeThemeDropdown();
        }
    });
}

/**
 * Abrir dropdown de temas
 */
function openThemeDropdown() {
    const themeDropdown = document.getElementById('themeDropdown');
    if (themeDropdown) {
        themeDropdown.classList.add('active');
    }
}

/**
 * Cerrar dropdown de temas
 */
function closeThemeDropdown() {
    const themeDropdown = document.getElementById('themeDropdown');
    if (themeDropdown) {
        themeDropdown.classList.remove('active');
    }
}

/**
 * Cargar tema guardado al iniciar
 */
function loadSavedTheme() {
    const savedTheme = localStorage.getItem('bibliotecaCifTheme');
    if (savedTheme && themes[savedTheme]) {
        applyThemeGlobally(savedTheme);
    } else {
        applyThemeGlobally('dark'); // Tema por defecto
    }
}

/**
 * Detectar cambios de tema del sistema (opcional)
 */
function detectSystemTheme() {
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        return 'dark';
    } else {
        return 'light';
    }
}

/**
 * Escuchar cambios de tema del sistema (opcional)
 */
function watchSystemTheme() {
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            const systemTheme = e.matches ? 'dark' : 'light';
            // Solo aplicar si el usuario no ha seleccionado un tema manualmente
            if (!localStorage.getItem('bibliotecaCifTheme')) {
                applyThemeGlobally(systemTheme);
            }
        });
    }
}

/**
 * Inicializar sistema de temas cuando el DOM esté listo
 */
document.addEventListener('DOMContentLoaded', function() {
    loadSavedTheme();
    initThemeSelector();
    watchSystemTheme();
    
    console.log('Sistema de temas inicializado correctamente');
});

/**
 * API pública para uso en otros scripts
 */
window.BibliotecaCifThemes = {
    setTheme: applyThemeGlobally,
    getTheme: () => currentTheme,
    getAvailableThemes: () => Object.keys(themes),
    resetToSystemTheme: () => {
        localStorage.removeItem('bibliotecaCifTheme');
        applyThemeGlobally(detectSystemTheme());
    }
};
