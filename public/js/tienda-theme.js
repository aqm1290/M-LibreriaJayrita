document.addEventListener('DOMContentLoaded', function () {
    /* ========== 1. TEMA LIGHT / DARK ========== */
    const root = document.documentElement;
    const themeBtn = document.getElementById('themeToggleBtn');
    const THEME_KEY = 'jayrita-theme';

    const savedTheme = localStorage.getItem(THEME_KEY);
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const currentTheme = savedTheme || (prefersDark ? 'dark' : 'light');

    root.setAttribute('data-theme', currentTheme);

    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            const newTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', newTheme);
            localStorage.setItem(THEME_KEY, newTheme);

            themeBtn.style.transform = 'scale(0.9)';
            setTimeout(() => (themeBtn.style.transform = ''), 150);
        });
    }

    /* ========== 2. HEADER SCROLL EFFECT ========== */
    const header = document.getElementById('header');

    window.addEventListener('scroll', () => {
        if (!header) return;
        if (window.scrollY > 60) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    /* ========== 3. MENÚ MÓVIL ========== */
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            mobileToggle.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
            if (!header.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('active');
                mobileToggle.classList.remove('active');
            }
        });
    }

    /* ========== 4. ANIMACIONES DE ENTRADA (opcional) ========== */
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px',
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    document
        .querySelectorAll('.jayrita-hero, .product-card, .section-title')
        .forEach((el) => {
            el.classList.add('animate-out');
            observer.observe(el);
        });
});
/* ========== 5. BOTÓN SCROLL TOP ========== */
const scrollTopBtn = document.getElementById('scroll-top');

if (scrollTopBtn) {
    // Mostrar / ocultar según el scroll
    window.addEventListener('scroll', () => {
        if (window.scrollY > 200) {
            scrollTopBtn.style.opacity = '1';
            scrollTopBtn.style.pointerEvents = 'auto';
        } else {
            scrollTopBtn.style.opacity = '0';
            scrollTopBtn.style.pointerEvents = 'none';
        }
    });

    // Hacer scroll suave hacia arriba
    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    });
}
