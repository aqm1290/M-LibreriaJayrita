<section id="contact" class="contact section">

    {{-- Título --}}
    <div class="container section-title">
        <h2>Contacto</h2>
        <div></div>
    </div>

    <div class="container">

        {{-- Cajas de información --}}
        <div class="row gy-4 mb-5">
            <div class="col-lg-4">
                <div class="contact-info-box">
                    <div class="icon-box">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div class="info-content">
                        <h4>Nuestra dirección</h4>
                        <p>Escribe aquí la dirección de tu librería</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="contact-info-box">
                    <div class="icon-box">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="info-content">
                        <h4>Correo electrónico</h4>
                        <p>info@tulibreria.com</p>
                        <p>contacto@tulibreria.com</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="contact-info-box">
                    <div class="icon-box">
                        <i class="bi bi-headset"></i>
                    </div>
                    <div class="info-content">
                        <h4>Horario de atención</h4>
                        <p>Lunes a Viernes: 9:00 - 18:00</p>
                        <p>Sábado: 9:00 - 13:00</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Mapa --}}
    <div class="map-section">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3807.119220525268!2d-66.0420999!3d-17.4060653!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x93e37b002c22fd9d%3A0x3c19ddaa648d8be6!2sLibrer%C3%ADa%20Jayra!5e0!3m2!1ses!2sbo!4v1765250017620!5m2!1ses!2sbo"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    {{-- Formulario superpuesto (Livewire) --}}
    <div class="container form-container-overlap">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @livewire('tienda.contacto-form')
            </div>
        </div>
    </div>

</section>
