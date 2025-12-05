<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Tienda')</title>

    {{-- Favicons --}}
    <link rel="icon" href="{{ asset('shop/assets/img/Logo-favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('shop/assets/img/favicon.png') }}">



    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700,1,900&family=Raleway:wght@400;500;600;700;800;900&family=Nunito+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">

    {{-- Vendor CSS --}}
    <link rel="stylesheet" href="{{ asset('shop/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('shop/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('shop/assets/vendor/aos/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('shop/assets/vendor/swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('shop/assets/vendor/glightbox/css/glightbox.min.css') }}">

    {{-- Main CSS --}}
    <link rel="stylesheet" href="{{ asset('shop/assets/css/main.css') }}">

    <style>
        .hover-lift {
            transition: all 0.4s ease;
        }

        .hover-lift:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(255, 215, 0, 0.2) !important;
        }

        .hover-lift:hover img {
            transform: scale(1.08);
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 60px;
            opacity: 0.8;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            opacity: 1;
        }
    </style>
    @stack('styles')
</head>

<body class="index-page">

    @include('partials.tienda-header')
    <main class="main">
        @yield('content')
    </main>

    @include('partials.tienda-footer')

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>


    <div id="preloader"></div>

    {{-- Vendor JS --}}
    <script src="{{ asset('shop/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('shop/assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('shop/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('shop/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('shop/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('shop/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('shop/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

    {{-- Main JS --}}
    <script src="{{ asset('shop/assets/js/main.js') }}"></script>

    @stack('scripts')




    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function() {

            const modalElement = document.getElementById('modalProductoAjax');
            const modal = new bootstrap.Modal(modalElement);

            $('.btn-ver-producto').on('click', function() {
                const id = $(this).data('id');

                // Reset
                $('#modalImg').addClass('d-none').attr('src', '');
                $('#modalNombre, #modalMarca, #modalModelo, #modalDescripcion, #modalPrecio')
                    .addClass('d-none').empty();
                $('#pMarca, #pModelo').addClass('d-none');

                $('#skeletonImg, #skeletonTitulo, #skeletonCat, #skeletonDesc, #skeletonPrecio')
                    .removeClass('d-none');

                modal.show();

                $.get('/producto/' + id, function(data) {

                    console.log(data); // verifica que traiga imagen_url, marca y modelo

                    setTimeout(() => {
                        $('#skeletonImg, #skeletonTitulo, #skeletonCat, #skeletonDesc, #skeletonPrecio')
                            .addClass('d-none');

                        $('#modalImg').attr('src', data.imagen_url).removeClass('d-none');
                        $('#modalNombre').text(data.nombre).removeClass('d-none');

                        if (data.marca) {
                            $('#modalMarca').text(data.marca);
                            $('#pMarca').removeClass('d-none');
                        }

                        if (data.modelo) {
                            $('#modalModelo').text(data.modelo);
                            $('#pModelo').removeClass('d-none');
                        }

                        $('#modalDescripcion').text(data.descripcion).removeClass('d-none');
                        $('#modalPrecio').text('Bs. ' + parseFloat(data.precio).toFixed(2))
                            .removeClass('d-none');

                        $('#modalLink').attr('href', '/tienda/productos/' + data.id);

                    }, 400);
                }).fail(function() {
                    modal.hide();
                    alert('Error al cargar el producto');
                });
            });

        });
    </script>

</body>

</html>
