// resources/js/script.js (para Laravel 12, asegúrate de que Vite lo compile en app.js o inclúyelo directamente)

document.addEventListener('DOMContentLoaded', () => {
    // Datos simulados (categorías y productos) - En producción, usa API o Livewire
    const categories = [
        { id: 1, nombre: '' },
        { id: 2, nombre: '' },
        { id: 3, nombre: '' },
        { id: 4, nombre: '' }
    ];

    const products = [
        { id: 1, name: '', price: 500, category: 1 },
        { id: 2, name: '', price: 1200, category: 1 },
        { id: 3, name: '', price: 20, category: 2 },
        { id: 4, name: '', price: 50, category: 2 },
        { id: 5, name: '', price: 800, category: 3 },
        { id: 6, name: '', price: 100, category: 3 },
        { id: 7, name: '', price: 15, category: 4 },
        { id: 8, name: '', price: 40, category: 4 }
    ];

    let cartCount = 0;
    let selectedCategory = null;
    let maxPrice = 1500;
    let searchTerm = '';

    const cartCountElement = document.getElementById('cart-count');
    const categoriesList = document.getElementById('categories-list');
    const productsGrid = document.getElementById('products-grid');
    const searchInput = document.getElementById('search-input');
    const maxPriceInput = document.getElementById('max-price');
    const priceValue = document.getElementById('price-value');

    // Cargar categorías dinámicamente
    categories.forEach(cat => {
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.href = '#';
        a.textContent = cat.nombre;
        a.classList.add('block', 'p-2', 'rounded', 'hover:bg-blue-100', 'text-blue-700', 'font-medium', 'transition-colors');
        a.addEventListener('click', (e) => {
            e.preventDefault();
            selectedCategory = cat.id;
            document.querySelectorAll('#categories-list a').forEach(link => link.classList.remove('bg-blue-200', 'font-bold'));
            a.classList.add('bg-blue-200', 'font-bold');
            filterProducts();
        });
        li.appendChild(a);
        categoriesList.appendChild(li);
    });

    // Renderizar productos
    function renderProducts(filteredProducts) {
        productsGrid.innerHTML = '';
        filteredProducts.forEach(product => {
            const card = document.createElement('div');
            card.classList.add('bg-gray-50', 'p-4', 'rounded-lg', 'shadow', 'hover:shadow-md', 'transition-shadow', 'duration-300', 'animate-fade-in');
            card.innerHTML = `
                <h3 class="text-lg font-bold mb-2">${product.name}</h3>
                <p class="text-green-600 font-semibold mb-4">$${product.price}</p>
                <button class="add-to-cart bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-transform hover:scale-105">Agregar al Carrito</button>
            `;
            card.querySelector('.add-to-cart').addEventListener('click', () => {
                cartCount++;
                cartCountElement.textContent = cartCount;
                alert(`¡${product.name} agregado al carrito!`);
            });
            productsGrid.appendChild(card);
        });
    }

    // Filtrar productos
    function filterProducts() {
        let filtered = products;
        if (selectedCategory) {
            filtered = filtered.filter(p => p.category === selectedCategory);
        }
        filtered = filtered.filter(p => p.price <= maxPrice);
        if (searchTerm) {
            filtered = filtered.filter(p => p.name.toLowerCase().includes(searchTerm.toLowerCase()));
        }
        renderProducts(filtered);
    }

    // Eventos
    searchInput.addEventListener('input', (e) => {
        searchTerm = e.target.value;
        filterProducts();
    });

    maxPriceInput.addEventListener('input', (e) => {
        maxPrice = parseInt(e.target.value);
        priceValue.textContent = maxPrice;
        filterProducts();
    });

    // Inicializar
    filterProducts();
});