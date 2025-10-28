/* ============================================
   SISTEMA DE TEMAS - JavaScript
   Biblioteca CIF - Control de temas
   ============================================ */

const themeToggle = document.getElementById('themeToggle');
const html = document.documentElement;
const themeIcon = document.querySelector('.theme-toggle-icon');
const themeText = document.querySelector('.theme-toggle-text');

const themes = {
    light: {
        icon: '',
        text: 'Modo Claro',
        next: 'dark'
    },
    dark: {
        icon: '',
        text: 'Modo Oscuro',
        next: 'original'
    },
    original: {
        icon: '',
        text: 'Modo Original',
        next: 'premium'
    },
    premium: {
        icon: '',
        text: 'Modo Premium',
        next: 'light'
    }
};

// Cargar tema guardado o usar 'light' por defecto
let currentTheme = localStorage.getItem('theme') || 'light';
html.setAttribute('data-theme', currentTheme);
updateThemeToggle(currentTheme);

// Event listener para cambiar tema
if (themeToggle) {
    themeToggle.addEventListener('click', function(e) {
        const rect = themeToggle.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        // Crear partículas
        for (let i = 0; i < 15; i++) {
            createParticle(x, y);
        }

        const nextTheme = themes[currentTheme].next;
        currentTheme = nextTheme;
        html.setAttribute('data-theme', nextTheme);
        localStorage.setItem('theme', nextTheme);
        updateThemeToggle(nextTheme);
    });
}

function updateThemeToggle(theme) {
    if (!themeIcon || !themeText) return;
    
    const themeConfig = themes[theme];
    themeIcon.textContent = themeConfig.icon;
    themeText.textContent = themeConfig.text;
}

function createParticle(x, y) {
    const particle = document.createElement('div');
    particle.className = 'particle';
    particle.style.cssText = \
        position: absolute;
        width: 8px;
        height: 8px;
        background: var(--primary);
        border-radius: 50%;
        pointer-events: none;
        box-shadow: 0 0 10px var(--primary);
    \;

    const angle = Math.random() * Math.PI * 2;
    const velocity = 60 + Math.random() * 60;
    const tx = Math.cos(angle) * velocity;
    const ty = Math.sin(angle) * velocity;

    particle.style.left = x + 'px';
    particle.style.top = y + 'px';
    particle.style.setProperty('--tx', tx + 'px');
    particle.style.setProperty('--ty', ty + 'px');
    
    particle.style.animation = 'particle-explosion 1.2s ease-out forwards';

    themeToggle.appendChild(particle);
    setTimeout(() => particle.remove(), 1200);
}

// Animación de partículas
const style = document.createElement('style');
style.textContent = \
    @keyframes particle-explosion {
        to {
            transform: translate(var(--tx), var(--ty)) scale(0);
            opacity: 0;
        }
    }
\;
document.head.appendChild(style);

console.log(' Sistema de temas cargado correctamente');
