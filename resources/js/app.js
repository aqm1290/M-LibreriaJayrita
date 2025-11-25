import './bootstrap';
import '../css/app.css';
import Swal from 'sweetalert2';


import { createIcons, Home, Box, Boxes, Layers, Wallet, Package, Store, Users, FolderTree } from 'lucide';

createIcons({
    icons: {
        Home, Box, Boxes, Layers, Wallet, Package, Store, Users, FolderTree
    }
});

/* import Alpine from 'alpinejs';
 */
window.Swal = Swal;
/* 
window.Alpine = Alpine;

Alpine.start(); */