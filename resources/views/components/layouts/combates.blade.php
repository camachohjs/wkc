<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'WKC - KARATE' }}</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="{{ asset('libs/css/styles.min.css') }}" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.2/font/bootstrap-icons.min.css"
        integrity="sha512-D1liES3uvDpPrgk7vXR/hR/sukGn7EtDWEyvpdLsyalQYq6v6YUsTUJmku7B4rcuQ21rf0UTksw2i/2Pdjbd3g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/estilos-panel.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
    <script src="{{ asset('js/funciones.js') }}"></script>
    <script src="{{ asset('js/collapse.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <!-- CSS de jQuery Bracket -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bracket/0.11.1/jquery.bracket.min.css">

    @livewireStyles
</head>

<body>
    <script src="{{ asset('libs/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('libs/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('libs/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('libs/js/app.min.js') }}"></script>
    <!--  Header Start -->
    <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light d-flex justify-content-between"
            style=" min-height: 30px !important; margin-bottom: 2px; margin-top: 2px;">
            @role('admin')
                <a class="btn btn2" href="{{ url('/torneos') }}">
                    Regresar
                </a>
            @else
                <a></a>
            @endrole
            <form action="{{ route('logout') }}" method="post" class="mx-3 d-block text-center" style="margin: 1px;">
                @csrf
                <button type="submit" class="btn btn-outline-danger" data-bs-toggle="tooltip"
                    data-bs-custom-class="delete-tooltip" data-bs-placement="bottom" data-bs-title="Cerrar sesión"
                    style="padding: 5px;">
                    <i class="bi bi-box-arrow-left"></i>
                </button>
            </form>
        </nav>
    </header>
    <div class="container-fluid">
        {{ $slot }}
    </div>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- JS de jQuery Bracket -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bracket/0.11.1/jquery.bracket.min.js"></script>

</body>

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
