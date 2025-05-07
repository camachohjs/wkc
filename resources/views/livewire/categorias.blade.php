<div class="container-fluid mt-3">
    @if (session()->has('message'))
        <div class="alert alert-success w-100" id="success-message" x-init="init()">
            {{ session('message') }}
        </div>
    @endif
    <div class="d-flex align-categorias-center justify-content-between">
        <h2 class="text-white">Categorias</h2>
        <div class="text-right">
            <button class="btn btn-amarillo" wire:click="create">Crear categoria</button>
            <button class="btn btn-amarillo" wire:click="vistaformas">Formas</button>
        </div>
    </div>
    <div class="d-flex align-categorias-center justify-content-between mt-3">
        <div class="text-left col-md-5">
            <div class="input-group">
                <input type="text" class="form-control buscar w-100" wire:model.live.debounce.150ms="search"
                    placeholder="Buscar categoría..." aria-describedby="button-addon2">
            </div>
        </div>
    </div>
    <div class="table-responsive">
        @foreach ($secciones as $seccion)
            <!-- Verifica si la sección tiene alguna forma asociada -->
            @if ($formas->contains('seccion_id', $seccion->id))
                <h3 class="text-yellow"><br>{{ $seccion->nombre }}<br></h3>
                @foreach ($formas->where('seccion_id', $seccion->id) as $forma)
                    <!-- Verifica si la forma tiene alguna categoría asociada -->
                    @if ($categorias->contains('forma_id', $forma->id))
                        <table id="mitabla" class="table table-dark mt-5">
                            <thead>
                                <tr>
                                    <th colspan="4">{{ $forma->nombre }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categorias->where('forma_id', $forma->id) as $categoria)
                                    <tr>
                                        <td>{{ $categoria->division }}</td>
                                        <td>
                                            {{ $categoria->nombre }} &nbsp;
                                            @if ($categoria['edad_minima'] == 18)
                                                +18 años
                                            @elseif ($categoria['edad_maxima'] == 10 && $categoria['edad_minima'] == 1)
                                                -10 años
                                            @elseif ($categoria['edad_maxima'] == 6 && $categoria['edad_minima'] == 1)
                                                -6 años
                                            @elseif ($categoria['edad_minima'] == 35)
                                                +35 años
                                            @elseif ($categoria['edad_minima'] == 42)
                                                +42 años
                                            @elseif ($categoria['edad_minima'] == 48)
                                                +48 años
                                            @elseif ($categoria['edad_minima'] == 1 && $categoria['edad_maxima'] == 99)
                                                Todas las edades
                                            @elseif(isset($categoria['edad_minima']) && isset($categoria['edad_maxima']))
                                                {{ $categoria['edad_minima'] }} - {{ $categoria['edad_maxima'] }} años
                                            @else
                                                N/A
                                            @endif
                                            &nbsp;
                                            @if ($categoria['genero'] == 'masculino')
                                                (Varonil)
                                            @elseif ($categoria['genero'] == 'femenino')
                                                (Femenino)
                                            @else
                                                (Mixta)
                                            @endif
                                            &nbsp;
                                            @if (
                                                ($categoria['forma_id'] == '12' || $categoria['forma_id'] == '13' || $categoria['forma_id'] == '14') &&
                                                    $categoria['peso_maximo'] <= 99 &&
                                                    isset($categoria['peso_maximo']))
                                                <b style="color:red;">-</b> {{ $categoria['peso_maximo'] }} Kg
                                            @elseif (
                                                ($categoria['forma_id'] == '12' || $categoria['forma_id'] == '13' || $categoria['forma_id'] == '14') &&
                                                    $categoria['peso_minimo'] <= 10)
                                                <b style="color:green;">Todos los pesos</b>
                                            @elseif (
                                                ($categoria['forma_id'] == '12' || $categoria['forma_id'] == '13' || $categoria['forma_id'] == '14') &&
                                                    $categoria['peso_maximo'] >= 100 &&
                                                    isset($categoria['peso_minimo']))
                                                <b style="color:green;">+</b> {{ $categoria['peso_minimo'] }} Kg
                                            @endif
                                        <td>
                                            <button wire:click="edit({{ $categoria->id }})"
                                                class="btn btn-outline-primary btn-sm mt-2"
                                                data-bs-custom-class="edit-tooltip" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-title="Editar categoria"><i
                                                    class="bi bi-pencil"></i></button>
                                            <button wire:click="delete({{ $categoria->id }})"
                                                class="btn btn-outline-danger btn-sm mt-2"
                                                wire:confirm="¿Estás seguro de eliminar la categoria?"
                                                data-bs-custom-class="delete-tooltip" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-title="Eliminar categoria"><i
                                                    class="bi bi-trash3-fill"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>
</div>
