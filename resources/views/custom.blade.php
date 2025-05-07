<!DOCTYPE html>
<html>

<head>
    <title>Page Title</title>
</head>

<body>
</body>

@if ($paginator->hasPages())
<nav aria-label="Page navigation example" style="color: #000">
    <ul class="pagination justify-content-center">
        @if ($paginator->onFirstPage())
        <li class="page-item disabled">
            <a class="page-link" tabindex="-1" style="background-color: black; border: 1px solid #EBC010; color:#695a1e; cursor: pointer">Anterior</a>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" wire:click="gotoPage({{ $paginator->currentPage() - 1 }})" style="background-color: black; border: 1px solid #EBC010; color:#EBC010; cursor: pointer">Anterior</a>
        </li>
        @endif

		@foreach ($elements as $element)
			@if (is_string($element))
			<li class="page-item disabled">{{ $element }}</li>
			@endif
		
			@if (is_array($element))
				@foreach ($element as $page => $url)
					@if ($page == $paginator->currentPage())
					<li class="page-item active" >
						<a class="page-link" style="background-color: #EBC010; border: 1px solid #EBC010; color:black; cursor: pointer">{{ $page }}</a>
					</li>
					@else
					<li class="page-item">
						<a class="page-link" wire:click="gotoPage({{ $page }})" style="background-color: black; border: 1px solid #EBC010; color:#EBC010; cursor: pointer" >{{ $page }}</a>
					</li>
					@endif
				@endforeach
			@endif
		@endforeach

        @if ($paginator->hasMorePages()) 
		<li class="page-item">
			<a class="page-link"
			wire:click="gotoPage({{ $paginator->currentPage() + 1 }})"
			rel="next" style="background-color: black; border: 1px solid #EBC010; color:#EBC010; cursor: pointer">Siguiente</a> 
		</li> 
		@else 
		<li class="page-item disabled">
            <a class="page-link" tabindex="-1" style="background-color: black; border: 1px solid #EBC010; color:#695a1e; cursor: pointer">Siguiente</a>
        </li>
		@endif 
    </ul>
</nav>
@endif

</html>

