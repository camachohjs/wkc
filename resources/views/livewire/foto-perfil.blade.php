<div class="navbar-collapse justify-content-end px-0" id="navbarNav">
    <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end"> 
        <h5 class="text-white ms-2 mb-0">Hola, {{ $usuario->nombre }}</h5>
        <li class="nav-item dropdown">
            <a class="nav-link nav-icon-hover" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ !$usuario->foto ? asset('libs/images/profile/user-1.png') : asset($usuario->foto) }}" alt="foto_de_perfil" width="35" height="35" class="rounded-circle">
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up text-center" aria-labelledby="drop2">
                <div class="message-body">
                    <a href="{{ url('/panel') }}" class="d-flex align-items-center gap-2 dropdown-item perfil">
                        <i class="ti ti-user" style="font-size: 1.2rem!important;"></i>
                        <p class="mb-0 " style="font-size: 1.2rem!important;">
                            Mi Perfil
                        </p>
                    </a>
                    <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 dropdown-item perfil">
                        <i class="bi bi-house-door-fill" style="font-size: 1.2rem!important;"></i>
                        <p class="mb-0" style="font-size: 1.2rem!important;">
                            Home
                        </p>
                    </a>
                    <form action="{{ route('logout') }}" method="post" class="mx-3 mt-2 d-block text-center">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" style="width: 100%">
                            <i class="bi bi-box-arrow-left"></i> Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </li>
    </ul>
</div>