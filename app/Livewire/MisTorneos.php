<?php

namespace App\Livewire;

use App\Models\Alumno;
use App\Models\RegistroTorneo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class MisTorneos extends Component
{
    #[Title('Torneos')]
    #[Layout('components.layouts.layout')]

    public function render()
    {
        if (Auth::check()) {
            $usuarioId = Auth::id();

            $alumno = Alumno::where('user_id', $usuarioId)->first();

            if ($alumno) {
                $misTorneos = $alumno->registros()->with(['torneo', 'categoria'])->get();

                return view('livewire.mis-torneos', [
                    'misTorneos' => $misTorneos
                ]);
            } else {
                flash()->options([
                    'position' => 'top-center',
                ])->addError('No se encontró el perfil de alumno.');
                return view('livewire.mis-torneos');
            }
        } else {
            flash()->options([
                'position' => 'top-center',
            ])->addError('Debe iniciar sesión para ver esta página.');
            return view('livewire.mis-torneos');
        }
    }
}
