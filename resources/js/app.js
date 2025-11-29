// resources/js/app.js

// Bootstrap de Laravel/Vite
import './bootstrap';
import '../css/app.css';

// SweetAlert2 disponible en window
import Swal from 'sweetalert2';
window.Swal = Swal;

// Lucide (iconos)
// Si quieres usar SOLO la versión UMD desde el layout (script de CDN),
// puedes comentar toda esta sección y dejar que se ejecute lucide.createIcons() en Blade.
// Pero si prefieres manejarlo aquí con Vite, usa este bloque:

import { createIcons } from 'lucide';
import {
    Eye, Pencil, XCircle, CheckCircle, Search, Plus, Trash2, Package,
    ShoppingCart, Home, Wallet, DoorOpen, DoorClosed, Menu, ChevronDown,
    Tag, Percent, History, FolderTree, Layers, FilePlus, Boxes, Truck,
    FileText, Edit, X, AlertCircle
} from 'lucide';

// Inicializar iconos Lucide
function initLucideIcons() {
    createIcons({
        icons: {
            Eye, Pencil, XCircle, CheckCircle, Search, Plus, Trash2, Package,
            ShoppingCart, Home, Wallet, DoorOpen, DoorClosed, Menu, ChevronDown,
            Tag, Percent, History, FolderTree, Layers, FilePlus, Boxes, Truck,
            FileText, Edit, X, AlertCircle
        }
    });
}

// Primera carga
document.addEventListener('DOMContentLoaded', () => {
    initLucideIcons();
});

// Hooks Livewire (por si usas navegación/modales)
document.addEventListener('livewire:update', initLucideIcons);
document.addEventListener('livewire:navigated', initLucideIcons);

if (typeof Livewire !== 'undefined') {
    Livewire.hook('morph.updated', () => {
        initLucideIcons();
    });
}

// SIDEBAR (móvil)
// IMPORTANTE: esto solo controla la clase -translate-x-full,
// el overlay lo manejas en el layout Blade.
/* window.toggleSidebar = function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (!sidebar || !overlay) return;

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
};
 */