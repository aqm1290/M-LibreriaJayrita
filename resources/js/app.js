// resources/js/app.js

// Bootstrap de Laravel/Vite
import './bootstrap';
import '../css/app.css';
import 'alpinejs';

// SweetAlert2 disponible en window
import Swal from 'sweetalert2';
window.Swal = Swal;


// Lucide (iconos)
import { createIcons } from 'lucide';
import {
    Eye, Pencil, XCircle, CheckCircle, Search, Plus, Trash2, Package,
    ShoppingCart, Home, Wallet, DoorOpen, DoorClosed, Menu, ChevronDown,
    Tag, Percent, History, FolderTree, Layers, FilePlus, Boxes, Truck,
    FileText, Edit, X, AlertCircle,
    UserPlus, LogOut, Lock, AlarmCheck, Download, Unlock
} from 'lucide';

// Inicializar iconos Lucide
function initLucideIcons() {
    createIcons({
        icons: {
            Eye, Pencil, XCircle, CheckCircle, Search, Plus, Trash2, Package,
            ShoppingCart, Home, Wallet, DoorOpen, DoorClosed, Menu, ChevronDown,
            Tag, Percent, History, FolderTree, Layers, FilePlus, Boxes, Truck,
            FileText, Edit, X, AlertCircle, Unlock,
            UserPlus, LogOut, Lock, AlarmCheck, Download
        }
    });
}

// Primera carga
document.addEventListener('DOMContentLoaded', () => {
    initLucideIcons();
});

// Hooks Livewire
document.addEventListener('livewire:update', initLucideIcons);
document.addEventListener('livewire:navigated', initLucideIcons);

if (typeof Livewire !== 'undefined') {
    Livewire.hook('morph.updated', () => {
        initLucideIcons();
    });
}

// SIDEBAR (móvil)
window.toggleSidebar = function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (!sidebar || !overlay) return;

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
};
