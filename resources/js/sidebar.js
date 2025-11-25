document.addEventListener('DOMContentLoaded', function () {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const btnCollapse = document.getElementById('btn-collapse');
    const btnToggleSidebar = document.getElementById('btn-toggle-sidebar');
    const btnCloseSidebar = document.getElementById('btn-close-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const darkModeToggle = document.getElementById('dark-mode-toggle');

    function toggleMobileSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        sidebarOverlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }

    if (btnToggleSidebar) {
        btnToggleSidebar.addEventListener('click', toggleMobileSidebar);
    }

    if (btnCloseSidebar) {
        btnCloseSidebar.addEventListener('click', toggleMobileSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', toggleMobileSidebar);
    }

    if (btnCollapse) {
        btnCollapse.addEventListener('click', function () {
            const isCollapsed = sidebar.classList.toggle('sidebar-collapsed');
            mainContent.classList.toggle('main-collapsed', isCollapsed);

            btnCollapse.innerHTML = `<i data-feather="${isCollapsed ? 'chevrons-right' : 'chevrons-left'}" class="w-6 h-6"></i>`;
            feather.replace();

            const texts = document.querySelectorAll('.nav-text, .header-text, .user-info, .gestion-title');
            texts.forEach(text => {
                text.classList.toggle('hidden', isCollapsed);
                text.style.opacity = isCollapsed ? '0' : '1';
                text.style.transform = isCollapsed ? 'translateX(-10px)' : 'translateX(0)'; // Animación slide out
            });

            setTimeout(() => feather.replace(), 100);
        });
    }

    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function () {
            document.documentElement.classList.toggle('dark');
            if (document.documentElement.classList.contains('dark')) {
                localStorage.theme = 'dark';
            } else {
                localStorage.theme = 'light';
            }
            feather.replace();
        });

        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    const logoutBtn = document.querySelector('button:has([data-feather="log-out"])');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
            document.getElementById('logout-form').submit();
        });
    }

    if (typeof Livewire !== 'undefined') {
        Livewire.hook('morph.updated', () => {
            feather.replace();
        });
    }
});