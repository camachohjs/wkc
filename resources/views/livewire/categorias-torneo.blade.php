<div class="container">
    <h4 class="text-white mt-4">Categorías y Combinaciones del Torneo {{ $torneo->nombre }}</h4>
    <div class="table-responsive">
        <table class="table table-dark mt-5">
            <thead>
                <tr>
                    <th style="color: #EBC010;">Tipo</th>
                    <th style="color: #EBC010;">División</th>
                    <th style="color: #EBC010;">Nombre</th>
                    <th style="color: #EBC010;">Edad</th>
                    <th style="color: #EBC010;">Género</th>
                    <th style="color: #EBC010;">Área</th>
                    <th style="color: #EBC010;">Horario</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categoriasFiltradas as $item)
                    <tr>
                        <td>{{ $item['tipo'] }}</td>
                        <td>{{ $item['division'] }}</td>
                        <td colspan="3">
                            {{ $item['nombre'] }} &nbsp;
                            @if ($item['edad_minima'] == 18)
                                +18 años
                            @elseif ($item['edad_maxima'] == 10 && $item['edad_minima'] == 1)
                                -10 años
                            @elseif ($item['edad_maxima'] == 6 && $item['edad_minima'] == 1)
                                -6 años
                            @elseif ($item['edad_minima'] == 35)
                                +35 años
                            @elseif ($item['edad_minima'] == 42)
                                +42 años
                            @elseif ($item['edad_minima'] == 48)
                                +48 años
                            @elseif ($item['edad_minima'] == 1 && $item['edad_maxima'] == 99)
                                Todas las edades
                            @elseif(isset($item['edad_minima']) && isset($item['edad_maxima']))
                                {{ $item['edad_minima'] }} - {{ $item['edad_maxima'] }} años
                            @else
                                N/A
                            @endif
                            &nbsp;
                            @if ($item['genero'] == 'masculino')
                                (Varonil)
                            @elseif ($item['genero'] == 'femenino')
                                (Femenino)
                            @else
                                (Mixta)
                            @endif
                            &nbsp;
                            @if (
                                ($item['forma_id'] == '12' || $item['forma_id'] == '13' || $item['forma_id'] == '14') &&
                                    $item['peso_maximo'] <= 99 &&
                                    isset($item['peso_maximo']))
                                <b style="color:red;">-</b> {{ $item['peso_maximo'] }} Kg
                            @elseif (
                                ($item['forma_id'] == '12' || $item['forma_id'] == '13' || $item['forma_id'] == '14') &&
                                    $item['peso_maximo'] >= 100 &&
                                    isset($item['peso_minimo']))
                                <b style="color:green;">+</b> {{ $item['peso_minimo'] }} Kg
                            @elseif (
                                ($item['forma_id'] == '12' || $item['forma_id'] == '13' || $item['forma_id'] == '14') &&
                                    $item['peso_minimo'] <= 10 &&
                                    isset($item['peso_minimo']))
                                <b style="color:green;"></b> Todos los pesos
                            @endif
                        </td>
                        <td>Área {{ $item['area'] }}</td>
                        <td>{{ $item['horario'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
