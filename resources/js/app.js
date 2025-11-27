
import './bootstrap';
import '../css/app.css';
import Swal from 'sweetalert2';
import { createIcons, FileText, Edit, Trash, Truck } from 'lucide';
window.Swal = Swal;

import {
    X,
    ChevronDown,
    ShoppingCart,
    Home,
    Wallet,
    DoorOpen,
    DoorClosed,
    Search,
    Boxes,
    Menu,
    CheckCircle,
    tag,
    FilePlus,
    History,
    FolderTree,
    Package,
    Layers,
    percent,
    Tag,

} from 'lucide';

createIcons({
    icons: {
        X,
        ChevronDown,
        ShoppingCart,
        tag,
        percent,
        Home,
        Wallet,
        DoorOpen,
        DoorClosed,
        Search,
        Boxes,
        FilePlus,
        History,
        FolderTree,
        Package,
        Layers,
        Tag,

        FileText,
        Truck,
        Menu,
        CheckCircle,

    }
});
// ------------------------------------


window.toggleSidebar = function () {
    const sidebar = document.getElementById('sidebar');
    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
    } else {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('translate-x-0');
    }
}