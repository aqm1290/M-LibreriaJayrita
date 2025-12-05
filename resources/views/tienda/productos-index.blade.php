@extends('layouts.shop')

@section('title', 'Catálogo de Productos - Librería Jayrita')

@section('content')
    <main class="main">

        <!-- Page Title -->
        <div class="page-title dark-background" data-aos="fade">
            <div class="container position-relative">
                <h1 class="text-warning fw-bold">Catálogo Completo</h1>
                <p class="lead text-white-75">Explora todos nuestros productos escolares y de oficina</p>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="{{ route('tienda.home') }}">Inicio</a></li>
                        <li class="current">Catálogo</li>
                    </ol>
                </nav>
            </div>
        </div>

        <section class="py-5 bg-black">
            <div class="container">

                <!-- FILTROS + RESULTADOS -->
                <div class="row" x-data="dataCatalogo()">

                    <!-- FILTROS LATERALES -->
                    <div class="col-lg-3 mb-5 mb-lg-0">
                        <div class="sticky-top" style="top: 100px;">
                            <h4 class="text-warning mb-4">
                                <i class="bi bi-funnel-fill me-2"></i> Filtros
                            </h4>

                            <!-- Búsqueda por nombre -->
                            <div class="mb-4">
                                <label class="form-label text-white-75">Buscar producto</label>
                                <input type="text" class="form-control bg-dark text-white border-secondary"
                                    placeholder="Ej: Cuaderno Norma..." x-model="search" @input.debounce.500ms="filtrar()">
                            </div>

                            <!-- Categoría -->
                            <div class="mb-4">
                                <label class="form-label text-white-75">Categoría</label>
                                <select class="form-select bg-dark text-white border-secondary" x-model="categoria"
                                    @change="filtrar()">
                                    <option value="">Todas las categorías</option>
                                    @foreach ($categorias as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Marca -->
                            <div class="mb-4">
                                <label class="form-label text-white-75">Marca</label>
                                <select class="form-select bg-dark text-white border-secondary" x-model="marca"
                                    @change="filtrar()">
                                    <option value="">Todas las marcas</option>
                                    @foreach ($marcas as $m)
                                        <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Modelo -->
                            <div class="mb-4">
                                <label class="form-label text-white-75">Modelo</label>
                                <select class="form-select bg-dark text-white border-secondary" x-model="modelo"
                                    @change="filtrar()">
                                    <option value="">Todos los modelos</option>
                                    @foreach ($modelos as $mod)
                                        <option value="{{ $mod->id }}">{{ $mod->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Rango de precio -->
                            <div class="mb-4">
                                <label class="form-label text-white-75">
                                    Precio:
                                    <span class="text-warning" x-text="`Bs ${precioMin} - Bs ${precioMax}`"></span>
                                </label>
                                <input type="range" class="form-range" min="0" max="500" step="10"
                                    x-model="precioMax" @input="filtrar()">
                            </div>

                            <!-- Solo con stock -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="conStock" x-model="soloStock"
                                    @change="filtrar()">
                                <label class="form-check-label text-white-75" for="conStock">
                                    Solo productos con stock
                                </label>
                            </div>

                            <!-- Botón limpiar -->
                            <button class="btn btn-outline-danger w-100" @click="limpiarFiltros()">
                                <i class="bi bi-arrow-clockwise"></i> Limpiar filtros
                            </button>
                        </div>
                    </div>

                    <!-- LISTADO DE PRODUCTOS -->
                    <div class="col-lg-9">

                        <!-- Contador de resultados -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <p class="text-white-75 mb-0">
                                Mostrando
                                <strong x-text="productosFiltrados.length"></strong>
                                de {{ $productos->count() }} productos
                            </p>
                            <select class="form-select w-auto bg-dark text-white border-secondary" x-model="orden"
                                @change="filtrar()">
                                <option value="nuevo">Más nuevos primero</option>
                                <option value="precio_asc">Precio: menor a mayor</option>
                                <option value="precio_desc">Precio: mayor a menor</option>
                                <option value="nombre">Nombre A-Z</option>
                            </select>
                        </div>

                        <!-- Grid de productos -->
                        <div class="row g-4" x-show="productosFiltrados.length > 0">
                            <template x-for="producto in productosFiltrados" :key="producto.id">
                                <div class="col-md-6 col-lg-4">
                                    <div
                                        class="card bg-dark border border-warning-subtle h-100 text-white overflow-hidden hover-lift transition position-relative">
                                        <!-- Imagen -->
                                        <div class="position-relative">
                                            <img :src="producto.imagen_url || '{{ asset('shop/assets/img/no-image.jpg') }}'"
                                                class="card-img-top" style="height: 220px; object-fit: cover;"
                                                :alt="producto.nombre">
                                            {{-- Nota: $loop no funciona dentro de x-for, se puede quitar o manejar distinto --}}
                                        </div>

                                        <div class="card-body d-flex flex-column p-4">
                                            <h5 class="text-warning fw-bold mb-2" x-text="producto.nombre"></h5>

                                            <div class="small text-white-50 mb-3">
                                                <span x-text="producto.marca?.nombre || 'Sin marca'"></span>
                                                <span x-show="producto.categoria">
                                                    • <span x-text="producto.categoria?.nombre"></span>
                                                </span>
                                            </div>

                                            <p class="text-white-70 small flex-grow-1" x-show="producto.descripcion"
                                                x-text="producto.descripcion.substring(0, 80) + '...'"></p>

                                            <div class="mt-auto">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="fs-4 fw-bold text-warning">
                                                        Bs <span x-text="Number(producto.precio).toFixed(2)"></span>
                                                    </span>
                                                    <span class="text-success small">
                                                        <i class="bi bi-box"></i>
                                                        <span x-text="producto.stock"></span>
                                                    </span>
                                                </div>

                                                <a :href="`/tienda/productos/${producto.id}`"
                                                    class="btn btn-outline-warning w-100">
                                                    <i class="bi bi-eye me-1"></i> Ver Detalle
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Sin resultados -->
                        <div class="text-center py-5" x-show="productosFiltrados.length === 0">
                            <i class="bi bi-search display-1 text-white-50"></i>
                            <p class="text-white-50 mt-4 fs-3">
                                No se encontraron productos con los filtros seleccionados
                            </p>
                            <button class="btn btn-outline-warning mt-3" @click="limpiarFiltros()">
                                Mostrar todos los productos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        function dataCatalogo() {
            return {
                search: '',
                categoria: '',
                marca: '',
                modelo: '',
                precioMin: 0,
                precioMax: 500,
                soloStock: false,
                orden: 'nuevo',

                // aquí usamos la variable preparada en el controlador
                productos: @json($productosJson),

                get productosFiltrados() {
                    let filtrados = this.productos;

                    // Búsqueda
                    if (this.search) {
                        filtrados = filtrados.filter(p =>
                            p.nombre.toLowerCase().includes(this.search.toLowerCase())
                        );
                    }

                    // Categoría
                    if (this.categoria) {
                        filtrados = filtrados.filter(p => p.categoria?.id == this.categoria);
                    }

                    // Marca
                    if (this.marca) {
                        filtrados = filtrados.filter(p => p.marca?.id == this.marca);
                    }

                    // Modelo
                    if (this.modelo) {
                        filtrados = filtrados.filter(p => p.modelo?.id == this.modelo);
                    }

                    // Precio máximo
                    filtrados = filtrados.filter(p => p.precio <= this.precioMax);

                    // Solo con stock
                    if (this.soloStock) {
                        filtrados = filtrados.filter(p => p.stock > 0);
                    }

                    // Ordenamiento
                    filtrados.sort((a, b) => {
                        switch (this.orden) {
                            case 'precio_asc':
                                return a.precio - b.precio;
                            case 'precio_desc':
                                return b.precio - a.precio;
                            case 'nombre':
                                return a.nombre.localeCompare(b.nombre);
                            default:
                                return 0; // "nuevo"
                        }
                    });

                    return filtrados;
                },

                filtrar() {
                    // Alpine recalcula productosFiltrados automáticamente
                },

                limpiarFiltros() {
                    this.search = '';
                    this.categoria = '';
                    this.marca = '';
                    this.modelo = '';
                    this.precioMin = 0;
                    this.precioMax = 500;
                    this.soloStock = false;
                    this.orden = 'nuevo';
                }
            }
        }
    </script>

@endsection
