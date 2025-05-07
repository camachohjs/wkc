<?php

namespace App\Jobs;

use App\Models\Alumno;
use App\Models\Maestro;
use App\Models\RegistroTorneo;
use App\Models\Torneo;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

use ZipArchive;

class GenerarPDFTorneo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function handle(): void
    {
        $torneoId = $this->id;
        $registros = RegistroTorneo::where('torneo_id', $torneoId)
                ->whereHas('categoria', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->with('categoria.forma')
                ->get();

        $alumnosIds = $registros->pluck('alumno_id')->filter();
        $maestrosIds = $registros->pluck('maestro_id')->filter();

        $alumnos = Alumno::with(['escuelas.maestros', 'registros'])
            ->whereIn('id', $alumnosIds)->get()->keyBy('id');

        $maestros = Maestro::with(['escuelas', 'registros'])
            ->whereIn('id', $maestrosIds)->get()->keyBy('id');


            $competidores = $registros->map(function ($registro) use ($alumnos, $maestros) {
                $competidor = $registro->alumno_id
                    ? $alumnos[$registro->alumno_id] ?? null
                    : $maestros[$registro->maestro_id] ?? null;
            
            if (!$competidor) return null;

            $edad = $competidor->fec ? now()->year - (new DateTime($competidor->fec))->format('Y') : 'N/A';
            $formaNombre = $registro->categoria?->forma?->nombre ?? '';
            $tipoCompetencia = stripos($formaNombre, 'KATA') !== false ? 'kata' : 'combate';

            return [
                'competidor' => $competidor,
                'edad' => $edad,
                'tipo' => $registro->alumno_id ? 'alumno' : 'maestro',
                'division' => $registro->categoria->division ?? 'Sin división',
                'tipo_competencia' => $tipoCompetencia,
            ];
        })->filter();

        $kata = $competidores->where('tipo_competencia', 'kata');
        $combate = $competidores->where('tipo_competencia', 'combate');

        $torneo = Torneo::findOrFail($torneoId);
        $archivos = [];

        if ($kata->isNotEmpty()) {
            $pdfKata = Pdf::loadView('pdf3', [
                'competidores' => $kata,
                'torneo' => $torneo,
            ]) ->setOptions(['dpi' => 96, 'defaultFont' => 'sans-serif']);
            
            $rutaKata = storage_path("app/public/Torneo-{$torneo->nombre}-KATAS.pdf");
            $pdfKata->save($rutaKata);
            $archivos[] = $rutaKata;
        }  else {
            Log::warning('KATA está vacío. No se generará PDF.');
        }

        if ($combate->isNotEmpty()) {
            $pdfCombate = Pdf::loadView('pdf2', [
                'competidores' => $combate,
                'torneo' => $torneo,
            ]) ->setOptions(['dpi' => 96, 'defaultFont' => 'sans-serif']);
            
            $rutaCombate = storage_path("app/public/Torneo-{$torneo->nombre}-COMBATES.pdf");
            $pdfCombate->save($rutaCombate);
            $archivos[] = $rutaCombate;
        } else {
            Log::warning('COMBATE está vacío. No se generará PDF.');
        }
        $zipFile = storage_path("app/public/Torneo-{$torneo->nombre}.zip");

        $zipFile = storage_path("app/public/Torneo-{$torneo->nombre}.zip");

        if (count($archivos) > 0) {
            $zip = new \ZipArchive();
            if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                foreach ($archivos as $archivo) {
                    $zip->addFile($archivo, basename($archivo));
                }
                $zip->close();

                // Guardamos el ZIP en storage/public
                copy($zipFile, storage_path("app/public/Torneo-{$torneo->nombre}.zip"));

                cache()->put("torneo_zip_{$torneo->id}", true, now()->addMinutes(10));
            } else {
                Log::error("No se pudo abrir ZIP para {$torneo->nombre}");
            }
        } else {
            Log::warning('No se generaron PDFs. ZIP no creado.');
        }
        
    }
}
