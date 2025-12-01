document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggleBtn = document.getElementById('toggle-sidebar');

    let isOpen = window.innerWidth >= 1024;

    function updateSidebar() {
        if (window.innerWidth >= 1024) {
            isOpen = true;
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('hidden');
        } else {
            isOpen = false;
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }

    window.toggleSidebar = function () {
        isOpen = !isOpen;
        sidebar.classList.toggle('-translate-x-full', !isOpen);
        overlay.classList.toggle('hidden', !isOpen);
    };

    toggleBtn?.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', toggleSidebar);
    window.addEventListener('resize', updateSidebar);

    // Inicializar
    updateSidebar();

    // Crear iconos de Lucide
    if (window.lucide) {
        lucide.createIcons();
    }
});







