<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo e($title ?? 'WKC - KARATE'); ?></title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="<?php echo e(asset('libs/css/styles.min.css')); ?>" />

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
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos-panel.css')); ?>">
    <script src="<?php echo e(asset('js/funciones.js')); ?>"></script>
    <script src="<?php echo e(asset('js/collapse.js')); ?>"></script>
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/dist/sortablejs.min.js"></script>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

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
                    <a href="<?php echo e(url('/panel')); ?>" class="text-nowrap logo-img text-center mt-5">
                        <img src="<?php echo e(asset('Img/KARATE.png')); ?>" class="logo-panel" alt="WKC - KARATE">
                    </a>
                    
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                        </li>


                        <?php if(auth()->user()->hasRole('supervisor')): ?>
                            
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('ranking')); ?>"><span
                                        class="hide-menu"><i class="bi bi-list-ol"></i>&nbsp;&nbsp;Ranking</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('mis-competidores')); ?>"><span
                                        class="hide-menu"><i class="bi bi-people"></i>&nbsp;&nbsp;Mis
                                        competidores</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(url('/proximos-eventos')); ?>"><span
                                        class="hide-menu"><i class="bi bi-calendar2-event"></i>&nbsp;&nbsp;Próximos
                                        eventos</span></a>
                            </li>
                        <?php elseif(auth()->user()->hasRole('admin')): ?>
                            
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('torneos')); ?>"><span
                                        class="hide-menu"><i
                                            class="bi bi-person-lines-fill"></i>&nbsp;&nbsp;Torneos</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('escuelas')); ?>"><span
                                        class="hide-menu"><i
                                            class="bi bi-houses-fill"></i>&nbsp;&nbsp;Escuelas</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('ranking')); ?>"><span
                                        class="hide-menu"><i class="bi bi-list-ol"></i>&nbsp;&nbsp;Ranking</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('categorias')); ?>"><span
                                        class="hide-menu"><i
                                            class="bi bi-clipboard-data"></i>&nbsp;&nbsp;Categorias</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('competidores')); ?>"><span
                                        class="hide-menu"><i
                                            class="bi bi-people"></i>&nbsp;&nbsp;Competidores</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('profesores')); ?>"><span
                                        class="hide-menu"><i
                                            class="bi bi-person-arms-up"></i>&nbsp;&nbsp;Sensei</span></a>
                            </li>
                        <?php elseif(auth()->user()->hasRole('super-admin')): ?>
                            
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('torneos')); ?>"><span
                                        class="hide-menu"><i
                                            class="bi bi-person-lines-fill"></i>&nbsp;&nbsp;Torneos</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('escuelas')); ?>"><span
                                        class="hide-menu"><i
                                            class="bi bi-houses-fill"></i>&nbsp;&nbsp;Escuelas</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('ranking')); ?>"><span
                                        class="hide-menu"><i class="bi bi-list-ol"></i>&nbsp;&nbsp;Ranking</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('categorias')); ?>"><span
                                        class="hide-menu"><i
                                            class="bi bi-clipboard-data"></i>&nbsp;&nbsp;Categorias</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('competidores')); ?>"><span
                                        class="hide-menu"><i
                                            class="bi bi-people"></i>&nbsp;&nbsp;Competidores</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('profesores')); ?>"><span
                                        class="hide-menu"><i
                                            class="bi bi-person-arms-up"></i>&nbsp;&nbsp;Sensei</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('admin.user-roles.index')); ?>"><span
                                        class="hide-menu"><i
                                            class="bi bi-person-arms-up"></i>&nbsp;&nbsp;Roles</span></a>
                            </li>
                        <?php else: ?>
                            

                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('dashboard-alumno')); ?>"><span
                                        class="hide-menu"><i
                                            class="bi bi-columns-gap"></i>&nbsp;&nbsp;Dashboard</span></a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link texto-panel" href="<?php echo e(route('mis-torneos')); ?>"><span
                                        class="hide-menu"><i class="bi bi-person-lines-fill"></i>&nbsp;&nbsp;Mis
                                        Torneos</span></a>
                            </li>
                        <?php endif; ?>
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
                        
                    </ul>
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('foto-perfil');

$__html = app('livewire')->mount($__name, $__params, 'lw-2305656402-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                </nav>
            </header>
            <script src="<?php echo e(asset('libs/libs/jquery/dist/jquery.min.js')); ?>"></script>
            <script src="<?php echo e(asset('libs/libs/bootstrap/dist/js/bootstrap.bundle.min.js')); ?>"></script>
            <script src="<?php echo e(asset('libs/js/sidebarmenu.js')); ?>"></script>
            <script src="<?php echo e(asset('libs/js/app.min.js')); ?>"></script>
            <div class="container-fluid">
                <?php echo e($slot); ?>

            </div>
            <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

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
<?php /**PATH C:\laragon\www\wkc\resources\views/components/layouts/layout.blade.php ENDPATH**/ ?>