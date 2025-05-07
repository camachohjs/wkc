<?php

namespace App\Livewire;

use App\Models\Combate;
use App\Models\Torneo;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AreasCategorias extends Areas
{

    public $torneoId;
    public $fechaId;
    public $areaId;
    public $areaSeleccionada;
    public $areas = [];
    public $selectedArea = [];

    public $areasFecha;

    #[Layout('components.layouts.combates')]

    public function updateCategoriasOrder($items)
    {
        foreach($items as $item){
            DB::table('categoria_torneo')->where('categoria_id', $item['value'])->update(['order_position' => $item['order']]);
        }
    }

    public function moverCategoria($categoriaId)
    {
        if (!empty($this->selectedArea[$categoriaId])) {
            DB::table('categoria_torneo')
                ->where('categoria_id', $categoriaId)
                ->where('torneo_id', $this->torneoId)
                ->update(['area' => $this->selectedArea[$categoriaId]]);

            $this->categoriasArea = DB::table('categoria_torneo')
                ->where('torneo_id', $this->torneoId)
                ->where('area', $this->selectedArea[$categoriaId])
                ->get();
            
            flash()->options([
                'position' => 'top-center',
            ])->addSuccess('', 'Categoría movida correctamente.');
            return redirect()->to(request()->header('Referer'));
        } else {
            flash()->options([
                'position' => 'top-center',
            ])->addError('', 'Selecciona un área válida.');
        }
    }

    public function render()
    {
        $infoTorneo = $this->obtenerInfoTorneo($this->torneoId);
        $this->areaSeleccionada = $infoTorneo[$this->fechaId]['areas'][$this->areaId];
        unset($this->areasFecha[$this->areaId]);
        $torneoId = $this->torneoId;

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $this->categoriasArea = collect($this->areaSeleccionada['categorias'])->sort(function ($a, $b) {
                $result = strcmp($a['horario_categoria'], $b['horario_categoria']);
                if ($result === 0) {
                    return strnatcmp($a['division_categoria'], $b['division_categoria']);
                }
                return $result;
            });
        } else {
            $this->categoriasArea = collect($this->areaSeleccionada['categorias'])->sort(function ($a, $b) {
                $result = strcmp($a['horario_categoria'], $b['horario_categoria']);
                if ($result === 0) {
                    return strnatcmp($a['division_categoria'], $b['division_categoria']);
                }
                return $result;
            }); 
            foreach ($this->categoriasArea as $categoria){
                $this->categoriasArea = collect($this->areaSeleccionada['categorias'])->filter(function ($categoria) use ($torneoId) {
                    $hayGanador = DB::table('categoria_torneo')
                        ->where('torneo_id', $torneoId)
                        ->where('categoria_id', $categoria['categoria_id'])
                        ->whereNotNull('ganador_id')
                        ->exists();
                
                    $hayGanadorKata = DB::table('katas')
                        ->where('torneo_id', $torneoId)
                        ->where('categoria_id', $categoria['categoria_id'])
                        ->where('order_position', 1)
                        ->whereNotNull('total_nuevo')
                        ->exists();
                
                    // Solo devolver categorías que no tienen ganador en ninguna tabla
                    return !$hayGanador && !$hayGanadorKata;
                });
            }
        }

        $this->seleccionArea = TRUE;

        $this->partidos = Combate::with(['participante1', 'participante2'])
            ->where('torneo_id', $this->torneoId)
            ->get();

            $conteo = count($this->partidos);

            /* dd($conteo); */
            if ($conteo > 0){
                $this->mostrarRondas = true;
            }

        return view('livewire.areas-categorias');
    }
}
