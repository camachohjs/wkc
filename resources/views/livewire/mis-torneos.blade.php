<div class="container-fluid mt-3">
    <div class="d-flex align-items-center justify-content-between">
        <div class="text-left">
            <h2 class="text-white">🏆 Torneos a los que me he inscrito</h2>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-dark mt-3">
            <thead>
                <tr>
                    <th>Nombre del Torneo</th>
                    <th>Peso</th>
                    <th>Categoria</th>
                </tr>
            </thead>
            <tbody>
                @foreach($misTorneos as $registro)
                    <tr>
                        <td>{{ $registro->torneo->nombre }}</td>
                        <td>{{ $registro->peso }}</td>
                        <td>{{ $registro->categoria->division.' - '.$registro->categoria->nombre }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
