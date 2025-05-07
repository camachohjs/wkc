<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Escuela;
use App\Models\Puntaje;
use App\Models\RegistroTorneo;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Puntuar extends Component
{
    #[Title('Check-In')]
    #[Layout('components.layouts.layout')]
    public $registroTorneo, $puntaje;
    public $checkPago;

    public function mount($id)
    {
        /* dd($id); */
        $this->registroTorneo = RegistroTorneo::findOrfail($id);
        $this->checkPago = $this->registroTorneo->check_pago;
        /* dd($this->registroTorneo); */
    }

    public function actualizarPago()
    {
        $this->registroTorneo->check_pago = $this->checkPago;
        $this->registroTorneo->save();

        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('Estado de pago actualizado con éxito.');

        return redirect()->route('inscritos', ['id' => $this->registroTorneo->torneo_id]);
    }

    public function render()
    {
        return view('livewire.puntuar', [
            'registroTorneo' => $this->registroTorneo,
            'categoriasInscritas' => $this->registroTorneo->categoria, 
        ]);
    }
}
