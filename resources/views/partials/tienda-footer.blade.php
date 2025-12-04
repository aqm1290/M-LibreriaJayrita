<footer id="footer" class="footer-top bg-dark ">
    <div class="container footer-top py-5">
        <div class="row gy-4">
            <div class="col-lg-5 col-md-12 footer-about">
                <a href="{{ url('/') }}" class="logo d-flex align-items-center mb-3">
                    <span class="sitename fw-bold">LIBRERÍA JAYRITA</span>
                </a>
                <p class="text-light">
                    En nuestra librería encontrarás miles de libros, papelería escolar, accesorios de oficina y todo lo que necesitas para estudiar, trabajar o disfrutar de la lectura. ¡Con amor desde Sacaba para todo Bolivia!
                </p>
                <div class="social-links d-flex mt-4 gap-3">
                    <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-whatsapp"></i></a>
                    <a href="#" class="text-white fs-4"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-6 footer-links">
                <h4 class="text-white fw-bold">Menú</h4>
                <ul class="list-unstyled">
                    <li><a href="{{ url('/') }}" class="text-light">Inicio</a></li>
                    <li><a href="{{ url('/nosotros') }}" class="text-light">Nosotros</a></li>
                    <li><a href="{{ url('/catalogo') }}" class="text-light">Catálogo</a></li>
                    <li><a href="{{ url('/ofertas') }}" class="text-light">Ofertas</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-6 footer-links">
                <h4 class="text-white fw-bold">Categorías</h4>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-light">Novelas</a></li>
                    <li><a href="#" class="text-light">Infantiles</a></li>
                    <li><a href="#" class="text-light">Escolares</a></li>
                    <li><a href="#" class="text-light">Papelería</a></li>
                    <li><a href="#" class="text-light">Autoayuda</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                <h4 class="text-white fw-bold">Contáctanos</h4>
                <p class="text-light"><strong>Dirección:</strong> Sacaba, Cochabamba</p>
                <p class="text-light"><strong>Teléfono:</strong> +591 7070-7070</p>
                <p class="text-light"><strong>WhatsApp:</strong> 7070-7070</p>
                <p class="text-light"><strong>Email:</strong> libreriajayrita@gmail.com</p>
            </div>
        </div>
    </div>

    <div class="container copyright text-center py-4 border-top border-secondary">
        <p class="mb-0 text-light">
            © {{ date('Y') }} <strong>Librería Jayrita</strong> - Todos los derechos reservados
        </p>
    </div>
</footer>