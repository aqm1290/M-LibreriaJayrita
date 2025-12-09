<footer class="jayrita-footer">
    <div class="jayrita-footer-main">
        <div class="container">
            <div class="row gy-4 align-items-start">

                {{-- Columna 1: logo + descripción --}}
                <div class="col-lg-4 col-md-12">
                    <div class="jayrita-footer-brand">
                        <span class="jayrita-footer-logo">LIBRERÍA JAYRITA</span>
                        <p class="jayrita-footer-text mt-3">
                            Tu librería de confianza en Sacaba. Libros, papelería escolar, oficina y regalos,
                            con atención cercana.
                        </p>
                    </div>

                    <div class="jayrita-footer-social mt-3">
                        <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="https://wa.me/59170707070" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                        <a href="#" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>

                {{-- Columna 2: enlaces --}}
                <div class="col-lg-3 col-6">
                    <h5 class="jayrita-footer-title">Navegación</h5>
                    <ul class="jayrita-footer-list">
                        <li><a href="{{ url('/') }}">Inicio</a></li>
                        <li><a href="{{ url('/catalogo') }}">Catálogo</a></li>
                        <li><a href="{{ url('/marcas') }}">Marcas</a></li>
                        <li><a href="{{ url('/ofertas') }}">Ofertas</a></li>
                        <li><a href="{{ url('/contacto') }}">Contacto</a></li>
                    </ul>
                </div>

                {{-- Columna 3: categorías rápidas --}}
                <div class="col-lg-3 col-6">
                    <h5 class="jayrita-footer-title">Categorías</h5>
                    <ul class="jayrita-footer-list">
                        <li><a href="#">Útiles escolares</a></li>
                        <li><a href="#">Libros y novelas</a></li>
                        <li><a href="#">Oficina y escritorio</a></li>
                        <li><a href="#">Arte y dibujo</a></li>
                        <li><a href="#">Regalos</a></li>
                    </ul>
                </div>

                {{-- Columna 4: contacto --}}
                <div class="col-lg-2 col-md-12">
                    <h5 class="jayrita-footer-title">Contacto</h5>
                    <ul class="jayrita-footer-contact">
                        <li><i class="bi bi-geo-alt-fill"></i> Sacaba, Cochabamba</li>
                        <li><i class="bi bi-telephone-fill"></i> +591 7070-7070</li>
                        <li><i class="bi bi-whatsapp"></i> 7070-7070</li>
                        <li><i class="bi bi-envelope-fill"></i> libreriajayrita@gmail.com</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <div class="jayrita-footer-bottom">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0">
                © {{ date('Y') }} <strong>Librería Jayrita</strong>. Cochabamba-Bolivia.
            </p>
            <p class="mb-0 jayrita-footer-mini">
                <i class="bi bi-shield-check me-1"></i> Compras seguras ·
            </p>
        </div>
    </div>
</footer>
