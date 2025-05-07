<!-- Modal -->
<div class="modal fade" id="pdfDiseñoModal" tabindex="-1" aria-labelledby="pdfDiseñoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfDiseñoModalLabel">Elige un Diseño de PDF</h5>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-octagon"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('Img/pdf/PDF1.jpg') }}" alt="Diseño 1" class="img-fluid"/>
                        <button wire:click="generar({{ $torneo_datos->id }}, 'pdf1')" class="btn btn-pdf mt-2" id="descargarPDF">Seleccionar Diseño 1</button>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('Img/pdf/PDF2.jpg') }}" alt="Diseño 2" class="img-fluid"/>
                        <button wire:click="generar({{ $torneo_datos->id }}, 'pdf2')" class="btn btn-pdf mt-2" id="descargarPDF">Seleccionar Diseño 2</button>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('Img/pdf/PDF3.jpg') }}" alt="Diseño 3" class="img-fluid"/>
                        <button wire:click="generar({{ $torneo_datos->id }}, 'pdf3')" class="btn btn-pdf mt-2" id="descargarPDF">Seleccionar Diseño 3</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>