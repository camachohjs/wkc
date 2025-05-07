<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\RegistroTorneo;
use App\Models\Torneo;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generatePDF($participanteId, $torneoId)
    {
        $competidor = Alumno::with('escuelas.maestros', 'registrosTorneo')
                            ->findOrFail($participanteId);
    
        $torneo = Torneo::findOrFail($torneoId);
    
        $fechaNacimiento = new DateTime($competidor->fec);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNacimiento)->y;
    
        $registroTorneo = RegistroTorneo::where('alumno_id', $participanteId)
                                            ->where('torneo_id', $torneoId)
                                            ->first();
    
        $pdf = Pdf::loadView('pdf2', [
            'competidor' => $competidor,
            'edad' => $edad,
            'torneo' => $torneo,
            'registroTorneo' => $registroTorneo
        ]);
    
        return $pdf->stream('ficha.pdf');
    
        // para descargar return $pdf->download('ficha.pdf');
    }

    public function verificarArchivo(Request $request)
    {
        $torneoId = session('torneo_id');

        if (!$torneoId) {
            return response()->json(['descargar' => null]);
        }

        $torneo = Torneo::find($torneoId);
        $ruta = storage_path("app/public/Torneo-{$torneo->nombre}.zip");

        if (file_exists($ruta)) {
            return response()->json(['descargar' => asset("storage/Torneo-{$torneo->nombre}.zip")]);
        }

        return response()->json(['descargar' => null]);
    }
}