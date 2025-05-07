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
    <script src="{{ asset('js/funciones.js') }}"></script>
    <script src="{{ asset('js/collapse.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/dist/sortablejs.min.js"></script>
    @livewireStyles
</head>

<body>

    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="{{ url('/panel') }}" class="text-nowrap logo-img text-center mt-5">
                        <img src="{{ asset('Img/KARATE.png') }}" class="logo-panel" alt="WKC - KARATE">
                    </a>
                    {{-- <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                            <i class="ti ti-x fs-8 text-white"></i>
                        </div> --}}
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                        </li>
                        @if (auth()->user()->hasRole('supervisor'))
                            {{-- maestro --}}
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="{{ route('ranking') }}"><span
                                        class="hide-menu"><i class="bi bi-list-ol"></i>&nbsp;&nbsp;Ranking</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="{{ route('mis-competidores') }}"><span
                                        class="hide-menu"><i class="bi bi-people"></i>&nbsp;&nbsp;Mis
                                        competidores</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="{{ url('/proximos-eventos') }}"><span
                                        class="hide-menu"><i class="bi bi-calendar2-event"></i>&nbsp;&nbsp;Próximos
                                        eventos</span></a>
                            </li>
                        @elseif(auth()->user()->hasRole('admin'))
                            {{-- admin --}}
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="{{ route('torneos') }}"><span
                                        class="hide-menu"><i
                                            class="bi bi-person-lines-fill"></i>&nbsp;&nbsp;Torneos</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="{{ route('escuelas') }}"><span
                                        class="hide-menu"><i
                                            class="bi bi-houses-fill"></i>&nbsp;&nbsp;Escuelas</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="{{ route('ranking') }}"><span
                                        class="hide-menu"><i class="bi bi-list-ol"></i>&nbsp;&nbsp;Ranking</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="{{ route('categorias') }}"><span
                                        class="hide-menu"><i
                                            class="bi bi-clipboard-data"></i>&nbsp;&nbsp;Categorias</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="{{ route('competidores') }}"><span
                                        class="hide-menu"><i
                                            class="bi bi-people"></i>&nbsp;&nbsp;Competidores</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="{{ route('profesores') }}"><span
                                        class="hide-menu"><i
                                            class="bi bi-person-arms-up"></i>&nbsp;&nbsp;Sensei</span></a>
                            </li>
                        @else
                            {{-- alumno --}}
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="{{ route('dashboard-alumno') }}"><span
                                        class="hide-menu"><i
                                            class="bi bi-columns-gap"></i>&nbsp;&nbsp;Dashboard</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="{{ route('mis-torneos') }}"><span
                                        class="hide-menu"><i class="bi bi-person-lines-fill"></i>&nbsp;&nbsp;Mis
                                        Torneos</span></a>
                            </li>
                        @endif
                    </ul>
                    <div class="position-relative rounded">
                        <div class="d-flex text-center" style="place-content: center; font-size: x-large;">
                            <button class="rounded-circle border-0 sidebartoggler mobile-no-tooltip"
                                id="sidebarCollapse" style="background-color: #000;" data-bs-toggle="tooltip"
                                data-bs-custom-class="custom-tooltip" data-bs-placement="top"
                                data-bs-title="Cerrar menú">
                                <i class="text-amarillo bi bi-arrow-left-circle"></i>
                            </button>
                        </div>
                    </div>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse"
                                href="javascript:void(0)" style="display: flex;">
                                <i class="ti ti-menu-2" style="color: #EBC010 !important"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebartoggler nav-icon-hover" id="sidebarCollapse1"
                                style="display: none;" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                data-bs-custom-class="custom-tooltip" data-bs-title="Abrir menú">
                                <i class="ti ti-menu-2" style="color: #EBC010 !important"></i>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                                    <i class="ti ti-bell-ringing"></i>
                                    <div class="notification bg-primary rounded-circle"></div>
                                </a>
                            </li> --}}
                    </ul>
                    @livewire('foto-perfil')
                </nav>
            </header>
            <script src="{{ asset('libs/libs/jquery/dist/jquery.min.js') }}"></script>
            <script src="{{ asset('libs/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
            <script src="{{ asset('libs/js/sidebarmenu.js') }}"></script>
            <script src="{{ asset('libs/js/app.min.js') }}"></script>
            <div class="container-fluid">
                {{ $slot }}
            </div>
            @livewireScripts
            <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
        </div>
    </div>
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
