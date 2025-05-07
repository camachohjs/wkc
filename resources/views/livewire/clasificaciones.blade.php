<div class="text-white container-fluid">
    <br><br>
    <h4 class="margin-left">Registro a competencias</h4>
    <div class="d-flex justify-content-between">
        <h6 class="margin-left">Lorem ipsum dolor sit amet consectetur. Ultrices porta libero massa vulputate dictum sodales pulvinar.</h6>
        <div class="d-flex justify-content-center" style="width: 342px; height: 80px; flex-shrink: 0; border-radius: 5px; background: #111;">
            <div class="input-group mb-3" style="width: 85% !important;">
                <input type="text" class="form-control" style="background: #111; border: 1px  solid #EBC010; border-width: 0 0 1px 0; border-radius: 0px;" wire:model.live.debounce.150ms="search" placeholder="Buscar..." id="miInput" aria-describedby="button-addon2">
                <button class="btn text-white" type="button" id="button-addon2" style="border: 1px solid #EBC010; border-width: 0 0 1px 0; border-radius: 0px;"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                    <path d="M6.73523 0.0625004C5.68108 0.0630638 4.64177 0.31087 3.70082 0.786003C2.75987 1.26114 1.94353 1.95034 1.31742 2.79823C0.691312 3.64611 0.27289 4.62903 0.0957866 5.66797C-0.0813169 6.70692 -0.0121619 7.77291 0.297694 8.78028C0.607549 9.78764 1.14946 10.7083 1.87988 11.4682C2.61029 12.2281 3.50884 12.8061 4.50329 13.1558C5.49775 13.5054 6.56037 13.6169 7.60576 13.4813C8.65115 13.3457 9.65015 12.9668 10.5225 12.3751L14.6871 16.5354C14.8027 16.6595 14.9422 16.7591 15.0972 16.8281C15.2522 16.8972 15.4195 16.9343 15.5892 16.9373C15.7588 16.9403 15.9274 16.9091 16.0847 16.8456C16.242 16.782 16.3849 16.6875 16.5049 16.5675C16.6249 16.4475 16.7195 16.3047 16.783 16.1474C16.8466 15.9901 16.8778 15.8216 16.8748 15.652C16.8718 15.4823 16.8347 15.3151 16.7656 15.1601C16.6966 15.0051 16.597 14.8657 16.4728 14.75L12.3116 10.5863C13 9.57331 13.3993 8.39186 13.4664 7.169C13.5335 5.94613 13.2659 4.72811 12.6925 3.64589C12.119 2.56367 11.2614 1.65819 10.2118 1.02682C9.16212 0.395444 7.9602 0.0620561 6.73523 0.0625004ZM2.52346 6.7999C2.52346 5.6831 2.9672 4.61205 3.75706 3.82236C4.54692 3.03267 5.6182 2.58902 6.73523 2.58902C7.85226 2.58902 8.92354 3.03267 9.7134 3.82236C10.5033 4.61205 10.947 5.6831 10.947 6.7999C10.947 7.91669 10.5033 8.98774 9.7134 9.77743C8.92354 10.5671 7.85226 11.0108 6.73523 11.0108C5.6182 11.0108 4.54692 10.5671 3.75706 9.77743C2.9672 8.98774 2.52346 7.91669 2.52346 6.7999Z" fill="#F9F9F9"/>
                    </svg></button>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between col-md-6" style="border-radius: 0px; border: 1px solid #F9F9F9; background: #010206;">
        @foreach(['perfil', 'categorias', 'carrito', 'orden', 'mis divisiones'] as $tab)
            <span style="padding: 15px; {{ $activeTab === $tab ? 'background-color: #EBC010; color: black;' : '' }}">
                <a href="#" style="text-decoration: none; {{ $activeTab === $tab ? 'background-color: #EBC010; color: black;' : 'color: white' }}" wire:click.prevent="setActiveTab('{{ $tab }}')">
                    {{ ucfirst($tab) }}
                </a>
            </span>
        @endforeach
    </div>

    @if ($activeTab == 'perfil')
        @include('subviews.perfil')
    @elseif ($activeTab == 'categorias')
        @include('subviews.categorias')
    @elseif ($activeTab == 'carrito')
        @include('subviews.carrito')
    @elseif ($activeTab == 'orden')
        @include('subviews.orden')
    @elseif ($activeTab == 'divisiones')
        @include('subviews.divisiones')
    @endif

</div>
