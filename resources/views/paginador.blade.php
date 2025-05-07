<!DOCTYPE html>
<html lang="es">

<head>
    <style>
        /* Personalización de los estilos de paginación */
        .pagination .page-link {
            background-color: black;
            border: 1px solid #EBC010;
            color: #EBC010;
        }

        .pagination .page-item.disabled .page-link {
            color: #695a1e;
        }

        .pagination .page-item.active .page-link {
            background-color: #EBC010;
            color: black;
            border-color: #EBC010;
        }

        /* Asegura que los elementos de paginación se ajusten bien en pantallas pequeñas */
        .pagination {
            flex-wrap: wrap;
        }

        @media (max-width: 576px) {
            .pagination .page-link {
                padding: 0.25rem 0.75rem;
                font-size: 0.875rem;
            }
        }
    </style>
</head>

<body> </body>
@if ($paginator->hasPages())
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" tabindex="-1">Anterior</a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" wire:click="gotoPage({{ $paginator->currentPage() - 1 }})"
                        style="cursor: pointer;">Anterior</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <a class="page-link">{{ $page }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" wire:click="gotoPage({{ $page }})"
                                    style="cursor: pointer;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" wire:click="gotoPage({{ $paginator->currentPage() + 1 }})" rel="next"
                        style="cursor: pointer;">Siguiente</a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" tabindex="-1">Siguiente</a>
                </li>
            @endif
        </ul>
    </nav>
@endif


</html>
