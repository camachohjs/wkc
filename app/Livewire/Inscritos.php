<?php

namespace App\Livewire;

use App\Http\Controllers\EscuelasParticipantes;
use App\Jobs\GenerarPDFTorneo;
use App\Models\Alumno;
use App\Models\Escuela;
use App\Models\Maestro;
use App\Models\RegistroTorneo;
use App\Models\Torneo;
use App\Models\Categoria;
use App\Models\TorneosPrecios;
use App\services\GetPreciosService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use DateTime;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use ZipArchive;

class Inscritos extends Component
{
    use WithPagination;
    public $nombre, $search, $id, $torneo_datos;
    public $filtroEscuela, $filtroSensei;
    public $ArrayEscuelas = [], $ArraySenseis = [];
    public $isOpen = 0;
    public $perPage =  25;
    public $profesores = [];
    public $checkPago = [];
    public $checkAll = [];
    public $checkedCategories = [];
    public $participantess;
    public $checkTodos = 0;

    #[Title('Inscritos')]
    #[Layout('components.layouts.layout')]

    public function update()
    {
        $currentPage = 1;
        Paginator::currentPageResolver(function () use ($currentPage) {
        return  $currentPage;
        });
	}

    public function exportarExcel()
    {
        $torneoId = $this->id;
        $torneo = Torneo::findOrFail($torneoId);
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('Img/KARATE.png')); 
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        
        $sheet->getRowDimension(1)->setRowHeight(75);
        $sheet->mergeCells('A1:B1');
    
        $sheet->setCellValue('C1', $torneo->nombre);
        $sheet->setCellValue('D1', 'Nombre de la Escuela');
        $sheet->setCellValue('E1', 'Cantidad');
        
        $sheet->getStyle('C1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'EBC010',
                ],
            ]
        ]);
    
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getColumnDimension('E')->setWidth(20);
    
        $escuelas = Escuela::with(['alumnos', 'maestros'])->get()->map(function ($escuela) use ($torneoId) {
        
            $alumnosCount = $escuela->alumnos()->whereHas('registros', function ($query) use ($torneoId) {
                $query->where('torneo_id', $torneoId);
            })->count();
        
            $maestrosCount = $escuela->maestros()->whereHas('registros', function ($query) use ($torneoId) {
                $query->where('torneo_id', $torneoId);
            })->count();
        
            $totalParticipantes = $alumnosCount + $maestrosCount;
        
            return [
                'nombre' => $escuela->nombre,
                'cantidad' => $totalParticipantes
            ];
        })->filter(function ($escuela) {
            return $escuela['cantidad'] > 0; 
        });
    
        $fila = 2; 
        foreach ($escuelas as $escuela) {
            $sheet->setCellValue('D' . $fila, $escuela['nombre']);
            $sheet->setCellValue('E' . $fila, $escuela['cantidad']);
            $fila++;
        }
    
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();
    
        return response()->streamDownload(function() use ($content) {
            echo $content;
        }, "Reporte-Escuelas-{$torneo->nombre}.xlsx", [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }

    public function torneoExcel()
    {
        $torneoId = $this->id;
        $torneo = Torneo::findOrFail($torneoId);
        $spreadsheet = new Spreadsheet();
        $sheetCompetidores = $spreadsheet->getActiveSheet();
        $sheetCompetidores->setTitle('Competidores');

        // LOGO
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('Img/KARATE.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheetCompetidores);
        $sheetCompetidores->mergeCells('A1:B2');

        // Encabezado
        $sheetCompetidores->setCellValue('C1', $torneo->nombre);
        $sheetCompetidores->setCellValue('C2', 'Nombre');
        $sheetCompetidores->setCellValue('D2', 'Escuela');
        $sheetCompetidores->setCellValue('E2', 'Edad');
        $sheetCompetidores->setCellValue('F2', 'División');
        $sheetCompetidores->setCellValue('G2', 'Total Pagado');
        $sheetCompetidores->setAutoFilter('C2:G2');

        // Estilos
        $sheetCompetidores->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 20, 'color' => ['rgb' => '000000']]
        ]);
        $sheetCompetidores->getStyle('A2:G2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '000000']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EBC010'],
            ]
        ]);
        $sheetCompetidores->getStyle('A:Z')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheetCompetidores->getColumnDimension('C')->setWidth(40);
        $sheetCompetidores->getColumnDimension('D')->setWidth(40);
        $sheetCompetidores->getColumnDimension('E')->setWidth(20);
        $sheetCompetidores->getColumnDimension('F')->setWidth(30);
        $sheetCompetidores->getColumnDimension('G')->setWidth(20);

        // Obtener registros de torneo
        $registros = RegistroTorneo::where('torneo_id', $torneoId)
            ->with(['alumno.escuelas', 'maestro.escuelas', 'categoria'])
            ->whereHas('categoria', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->get();

        $getPrecios = new GetPreciosService();
        $escuelasTotales = [];
        $fila = 3;

        foreach ($registros as $registro) {
            $persona = $registro->alumno ?? $registro->maestro;
            $nombreCompleto = $persona->nombre . ' ' . $persona->apellidos;
            $escuela = $persona->escuelas->pluck('nombre')->implode(', ');
            $edad = $persona->fec ? Carbon::parse($persona->fec)->age : 'N/A';
            $division = $registro->categoria->division;
            
            // Obtener precio de la categoría
            $categoriaDetalles = [[
                'id_registro' => $registro->id,
                'id_categoria' => $registro->categoria->id,
                'checked' => (bool) $registro->check_pago,
                'division' => $registro->categoria->division,
                'nombre' => $registro->categoria->nombre,
                'forma' => $registro->categoria->forma_id,
                'precio' => '-',
                'fecha' => $registro->created_at->format('d/m/Y')
                ]];
            $categoriaDetalles = $getPrecios->precioCategoria($categoriaDetalles, $this->id);
            $totalPagado = $categoriaDetalles[0]['precio'] ?? 0;

            $sheetCompetidores->setCellValue('C' . $fila, $nombreCompleto);
            $sheetCompetidores->setCellValue('D' . $fila, $escuela);
            $sheetCompetidores->setCellValue('E' . $fila, $edad);
            $sheetCompetidores->setCellValue('F' . $fila, $division);
            $sheetCompetidores->setCellValue('G' . $fila, $totalPagado);

            // Acumular total por escuela
            if (!isset($escuelasTotales[$escuela])) {
                $escuelasTotales[$escuela] = 0;
            }
            $escuelasTotales[$escuela] += $totalPagado;

            $fila++;
        }

        // ========================
        // SEGUNDA HOJA: ESCUELAS
        // ========================
        $spreadsheet->createSheet();
        $sheetEscuelas = $spreadsheet->setActiveSheetIndex(1);
        $sheetEscuelas->setTitle('Escuelas');

        // Encabezado
        $sheetEscuelas->setCellValue('A1', 'Nombre de la Escuela');
        $sheetEscuelas->setCellValue('B1', 'Total Pagado');
        $sheetEscuelas->getStyle('A1:B1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '000000']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EBC010'],
            ]
        ]);
        $sheetEscuelas->getColumnDimension('A')->setWidth(40);
        $sheetEscuelas->getColumnDimension('B')->setWidth(20);

        // Llenado de datos - Escuelas
        $fila = 2;
        foreach ($escuelasTotales as $escuela => $total) {
            $sheetEscuelas->setCellValue('A' . $fila, $escuela);
            $sheetEscuelas->setCellValue('B' . $fila, $total);
            $fila++;
        }

        // Volver a la primera hoja
        $spreadsheet->setActiveSheetIndex(0);

        // Generar y descargar
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response()->streamDownload(function() use ($content) {
            echo $content;
        }, "Reporte-{$torneo->nombre}.xlsx", [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }

    protected $servicioPrecios;
    
    public function mount($id)
    {
        $this->id = $id;
        $this->TorneoDatos();
        // $this->servicioPrecios = $miServicio;

        // Obtener todos los registros de registros_torneos para el torneo
        $participantes = RegistroTorneo::where('torneo_id', $this->id)
        ->whereHas('categoria', function ($query) {
            $query->whereNull('deleted_at');
        })
        ->get();

        foreach ($participantes as $participante) {
            // Verificar si todas las categorías del participante tienen check_pago activado
            $categoriasDelParticipante = RegistroTorneo::where('torneo_id', $this->id)
                ->where(function ($query) use ($participante) {
                    $query->where('alumno_id', $participante->alumno_id)
                        ->orWhere('maestro_id', $participante->maestro_id);
                })
                ->pluck('check_pago');

            $todasActivadas = $categoriasDelParticipante->every(function ($value) {
                return $value == 1;
            });

            // Asignar a checkAll si todas están activadas
            $this->checkAll[$participante->id] = $todasActivadas;

            // Asignar el estado de check_pago para cada categoría
            foreach ($categoriasDelParticipante as $categoriaId => $check) {
                $this->checkPago[$participante->id][$categoriaId] = $check;
            }
        }
    }

    public function toggleCheckAll($index)
    {
        $this->checkAll[$index] = isset($this->checkAll[$index]) ? !$this->checkAll[$index] : true;

        if (isset($this->participantess[$index])) {
            foreach ($this->participantess[$index]['categorias'] as $catIndex => $categoria) {
                $this->checkPago[$index][$catIndex] = $this->checkAll[$index];
                RegistroTorneo::where('id', $categoria['id_registro'])->update(['check_pago' => $this->checkAll[$index]]);
            }
        }
    }

    public function actualizarCheckTodo(){
        $this->checkTodos = !$this->checkTodos;
        foreach ($this->participantess as $participante) {
            foreach ($participante['categorias'] as $categoria) {
                RegistroTorneo::where('id', $categoria['id_registro'])->update(['check_pago' => $this->checkTodos]);

                $this->checkPago[$categoria['id_registro']] = $this->checkTodos;
            }
        }
        redirect()->route('inscritos', ['id' => $this->id]);
    }

    public function actualizarCheckPago($idRegistro)
    {
        $registro = RegistroTorneo::findOrFail($idRegistro);

        // Actualiza el valor en la base de datos
        $registro->check_pago = !$registro->check_pago;
        $registro->save();

        // Sincroniza el valor en el componente
        $this->checkPago[$idRegistro] = $registro->check_pago;
        $this->validarCheckTodo();
        
    }

    public function TorneoDatos()
    {
        if ($this->id) {
            $this->torneo_datos = Torneo::findOrFail($this->id);
        }
    }

    public function calificar($id) {
        return redirect()->route('puntuar', ['id' => $id]);
    }

    public function verificar($id) {
        return redirect()->route('calificacion', ['id' => $id]);
    }

    public function delete($id)
    {
        $registro = RegistroTorneo::find($id);

        $registro->delete();

        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Registro eliminado exitosamente.');
    }

    public function buscar()
    {
        $this->resetPage();
    }

    public function filtroUpdated($tipo, $value)
    {
        if($tipo == 'escuela'){
            $this->filtroEscuela = $value;
        }else{
            $this->filtroSensei = $value;
        }
    }


    public function render()
    {
        
        $query = RegistroTorneo::where('torneo_id', $this->id)
                                    ->with(['categoria', 'alumno.escuelas','alumno.maestros', 'maestro.escuelas', 'alumno', 'maestro'])
                                    ->whereHas('categoria', function ($query) {
                                        $query->whereNull('deleted_at');
                                    })
                                    ->orderBy('email');
        
        if($this->filtroEscuela){
            $query->whereHas('alumno.escuelas', function ($query) {
                $query->where('escuelas.id', $this->filtroEscuela);
            });
        }

        if($this->filtroSensei){
            $query->whereHas('alumno.maestros', function ($query) {
                $query->where('maestros.id', $this->filtroSensei);
            });
        }

        $this->ArrayEscuelas = Escuela::whereHas('alumnos.registros', function ($res) {
            $res->where('torneo_id', $this->id);
        })->orWhereHas('maestros.registros', function ($res) {
            $res->where('torneo_id', $this->id);
        })->get();

        $this->ArraySenseis = Maestro::whereHas('alumnos.registros', function ($res) {
            $res->where('torneo_id', $this->id);
        })->get();

        if ($this->search) {
            $query->where(function ($query) {
                $query->whereHas('alumno', function ($query) {
                    $query->whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$this->search}%"]);
                })->orWhereHas('maestro', function ($query) {
                    $query->whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$this->search}%"]);
                })->orWhereHas('alumno.escuelas', function ($query) {
                    $query->where('nombre', 'like', "%{$this->search}%");
                })->orWhereHas('maestro.escuelas', function ($query) {
                    $query->where('nombre', 'like', "%{$this->search}%");
                });
            });
        }
        $participantesPaginados = $query->paginate($this->perPage);
    
        $participantesAgrupados = $participantesPaginados->getCollection()->groupBy(function ($item) {

            return ($item->alumno_id ? 'A' . $item->alumno_id : 'M' . $item->maestro_id);
        })->map(function ($items) {
            $persona = $items->first()->alumno ?? $items->first()->maestro;
            $fechaNacimiento = $persona->fec ? new Carbon($persona->fec) : null;
            $edad = $fechaNacimiento ? $fechaNacimiento->diffInYears(now()) : 'N/A';
            $categoriasDetalles = $items->map(function ($item) {
                return [
                    'id_registro' => $item->id, 
                    'id_categoria' => $item->categoria->id,
                    'checked' => (bool) $item->check_pago,
                    'division' => $item->categoria->division,
                    'nombre' => $item->categoria->nombre,
                    'forma' => $item->categoria->forma_id,
                    'precio' => '-',
                    'fecha' => $item->created_at->format('d/m/Y')
                ];
            });
            $getPrecios = new GetPreciosService();
            $categoriasDetalles = $getPrecios->precioCategoria($categoriasDetalles, $this->id);

            $escuelas = $persona->escuelas ? $persona->escuelas->pluck('nombre')->unique()->implode(', ') : 'N/A';
            $maestros = $persona->maestros ? $persona->maestros->map(function ($maestro) {
                return $maestro->nombre . ' ' . $maestro->apellidos;
            })->unique()->implode(', '): 'N/A';
    
            return [
                'nombre' => $persona->nombre . ' ' . $persona->apellidos,
                'categorias' => $categoriasDetalles,
                'edad' => $edad,
                'escuelas' => $escuelas,
                'maestros' => $maestros
            ];
        });
        $this->participantess = $participantesAgrupados->mapWithKeys(function($items, $key) {
            $nuevoIndice = substr($key, 1); 
            return [$nuevoIndice => $items];
        });
        $this->validarCheckTodo();
        return view('livewire.inscritos', [
            'participantes' => $participantesPaginados->setCollection(collect($participantesAgrupados)),
        ]);
    }

    public function validarCheckTodo(){
        $this->checkTodos = 1;
        foreach ($this->participantess as $participante) {
            foreach ($participante['categorias'] as $categoria) {
                $register = RegistroTorneo::where('id', $categoria['id_registro'])->first();
                if($register->check_pago != 1){
                    $this->checkTodos = false;
                    break;
                }
            }
        }

    }

    public function abrirModalPDF($torneoId)
    {
        $this->id = $torneoId;
        $this->dispatch('abrir-modal-pdf');
    }

    public function generarPDF()
    {
        session(['torneo_id' => $this->id]);

        GenerarPDFTorneo::dispatch($this->id);

        return redirect()->route('esperando-descarga', $this->id);
    }

    public function esperandoDescarga($id)
    {
        if (cache("torneo_zip_{$id}_fallo")) {
            flash()->options([
                'position' => 'top-center',
            ])->addError('', 'No se pudieron generar los archivos PDF.');
            return back();
        }

        return view('esperando-descarga', ['id' => $id]);
    }
}