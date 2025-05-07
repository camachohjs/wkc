<div class="container py-2 text-white">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="text-left">
            <h2 class="text-white">📱 Dashboard</h2>
        </div>
    </div>
    <br>
    <div class="row mb-4">
        <div class="col-md-6">
            <a href="{{ url('/dashboard/mis-puntos') }}" class="dashboard-card-link">
                <div class="dashboard-card p-4 row" style="margin-right: 0px">
                    <div class="col-md-8">
                        <h3>Mis Puntos</h3><br>
                        <p>Consulta tus puntuación en el ranking.</p>
                    </div>
                    <div class="col-md-4" style="text-align: right;">
                        <i class="bi bi-chevron-compact-right" style="font-size: 75px;"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ url('/dashboard/proximos-eventos') }}" class="dashboard-card-link">
                <div class="dashboard-card p-4 row" style="margin-left: 0px">
                    <div class="col-md-8">
                        <h3>Próximos Torneos</h3><br>
                        <p>Descubre los torneos que están por venir.</p>
                    </div>
                    <div class="col-md-4" style="text-align: right;">
                        <i class="bi bi-chevron-compact-right" style="font-size: 75px;"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <br>
    <div class="row mb-4">
        <div class="col-md-6">
            {{-- <div class="dashboard-card p-4 row" style="margin-right: 0px" wire:click="proximosEventos">
                <div class="col-md-8">
                    <h3>Registro Torneo</h3><br>
                    <p>Completa tu registros pendientes.</p>
                </div>
                <div class="col-md-4" style="text-align: right;">
                    <i class="bi bi-chevron-compact-right" style="font-size: 75px;"></i>
                </div>
            </div> --}}
        </div>
        <div class="col-md-6">
            {{-- <div class="dashboard-card p-4">
                <h3>Otra Categoría</h3><br>
                <p>Información relevante sobre otra categoría</p>
                <button class="btn">Ver Más</button>
            </div> --}}
        </div>
    </div>
</div>
