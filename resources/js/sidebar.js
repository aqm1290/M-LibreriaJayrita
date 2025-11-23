// resources/js/sidebar.js
document.addEventListener('DOMContentLoaded', function () {
    // Cargar iconos al inicio
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const btnCollapse = document.getElementById('btn-collapse');
    let collapseIcon = btnCollapse.querySelector('i'); // Inicial

    // Botón colapsar/expandir
    btnCollapse.addEventListener('click', function () {
        const isCollapsed = sidebar.classList.toggle('sidebar-collapsed');
        mainContent.classList.toggle('main-collapsed', isCollapsed);

        // Cambiar flecha (CORREGIDO: Reemplazar todo el contenido del botón con nuevo <i> y refrescar Feather)
        btnCollapse.innerHTML = `<i data-feather="${isCollapsed ? 'chevrons-right' : 'chevrons-left'}" class="w-6 h-6"></i>`;
        feather.replace(); // Refrescar inmediatamente después de cambiar

        // Actualizar referencia a collapseIcon después del cambio
        collapseIcon = btnCollapse.querySelector('i');

        // Ocultar/mostrar textos (con opacity para transición suave)
        const texts = document.querySelectorAll('.nav-text, .header-text, .user-info, .gestion-title');
        texts.forEach(text => {
            text.classList.toggle('hidden', isCollapsed);
            text.style.opacity = isCollapsed ? '0' : '1';
        });

        // Refrescar iconos adicionales
        setTimeout(() => feather.replace(), 50);
        setTimeout(() => feather.replace(), 150);
        setTimeout(() => feather.replace(), 300);
    });

    // CERRAR SESIÓN
    const logoutBtn = document.querySelector('button:has([data-feather="log-out"])');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
            document.getElementById('logout-form').submit();
        });
    }

    // Refrescar iconos al inicio
    setTimeout(() => feather.replace(), 100);

    // Compatibilidad con Livewire
    if (typeof Livewire !== 'undefined') {
        Livewire.hook('morph.updated', () => {
            feather.replace();
        });
    }
});