<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'WKC - KARATE' }}</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.2/font/bootstrap-icons.min.css"
        integrity="sha512-D1liES3uvDpPrgk7vXR/hR/sukGn7EtDWEyvpdLsyalQYq6v6YUsTUJmku7B4rcuQ21rf0UTksw2i/2Pdjbd3g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css"
        integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw=="
        crossOrigin="anonymous" referrerPolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossOrigin="anonymous" referrerPolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <script src="{{ asset('js/funciones.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg text-white">
            <div class="container-fluid text-center">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('Img/KARATE.png') }}" class="logo" alt="WKC - KARATE">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon" style="filter: invert(1) !important;"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/eventos') }}">Eventos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Noticias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/atletas-grid') }}">Competidores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('clasificaciones.index') }}">Ranking</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mx-auto align-item-center">
                        @auth
                            <li class="nav-item">
                                <a class="btn btn2" href="{{ url('/panel') }}">
                                    Dashboard
                                </a>
                            </li>
                        @else
                            <li>
                                <a class="btn btn2" href="{{ route('login') }}">
                                    Iniciar Sesión
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    {{ $slot }}
    <br>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
</body>
<footer class="footer mt-5">
    <section class="py-4 py-md-5 py-xl-8 fondo text-white">
        <div class="container-fluid overflow-hidden text-center">
            <div class="row gy-4 gy-lg-0 d-flex justify-content-center">
                <div class="col-8 col-md">
                    <a href="{{ url('/') }}"
                        class="d-flex align-items-center justify-content-center mb-3 link-body-emphasis text-decoration-none">
                        <img src="{{ asset('Img/KARATE.png') }}" class="logo" alt="WKC - KARATE">
                    </a>
                </div>
                <div class="col-8 col-sm-8 col-md-3">
                    <div class="widget">
                        <h5 class="widget-title mb-4">Torneos</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ url('/eventos') }}" class="text-white footer">Eventos</a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ url('/clasificaciones') }}" class="text-white footer">Ranking</a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ url('/atletas-grid') }}" class="text-white footer">Competidores</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-8 col-sm-8 col-md-3">
                    <div class="widget">
                        <h5 class="widget-title mb-4">Nosotros</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ asset('Reglamento.pdf') }}" class="text-white footer"
                                    target="_blank">Reglamento Oficial</a>
                            </li>
                            <li class="mb-2">
                                <a href="#!" class="text-white footer">Términos y condiciones</a>
                            </li>
                            <li class="mb-2">
                                <a href="#!" class="text-white footer">Aviso de Privacidad</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-8 col-sm-8 col-md-3">
                    <div class="widget">
                        <h5 class="widget-title mb-4">Noticias</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ url('/blog') }}" class="text-white footer">Blog</a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ url('/register') }}" class="text-white footer">Registrate</a>
                            </li>
                            <li class="mb-2">
                                <a href="https://www.instagram.com/wkcmexicoficial/" class="insta"
                                    target="_blank"><i class="bi bi-instagram"></i></a>&nbsp;
                                <a href="https://www.facebook.com/wkcmexicoficial" class="facebook"
                                    target="_blank"><i class="bi bi-facebook"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</footer>

</html>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countryCodeToEmoji = (countryCode) => {
            const codePoints = countryCode
                .toUpperCase()
                .split('')
                .map(char => 127397 + char.charCodeAt());
            return String.fromCodePoint(...codePoints);
        };

        document.querySelectorAll('.flag-emoji').forEach(span => {
            const countryCode = span.getAttribute('data-country-code');
            span.textContent = countryCodeToEmoji(countryCode);
        });
    });
</script>
