<?php

namespace App\Livewire;

use App\Models\Torneo;
use App\Models\TorneoUser;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Torneos extends Component
{
    use WithPagination;

    #[Title('Torneos')]
    #[Layout('components.layouts.layout')]

    // Datos de Torneo
    public $torneo_id;
    public $nombre, $descripcion, $fecha_evento, $fecha_registro, $direccion;
    public $banner, $banner_actual, $ranking;

    // Búsqueda y estado
    public $search = '';
    public $perPage = 25;

    // Control UI
    public $isOpen = false;
    public $modalFormVisible = false;

    // Renderizado del componente
    public function render()
    {
        $torneos = Torneo::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'LIKE', "%{$this->search}%")
                        ->orWhere('direccion', 'LIKE', "%{$this->search}%")
                        ->orWhere('descripcion', 'LIKE', "%{$this->search}%")
                        ->orWhere('fecha_evento', 'LIKE', "%{$this->search}%")
                        ->orWhere('fecha_registro', 'LIKE', "%{$this->search}%")
                        ->orWhere('ranking', 'LIKE', "%{$this->search}%");
                });
            })
            ->orderByDesc('fecha_evento')
            ->paginate($this->perPage);

        return view('livewire.torneos', [
            'torneos' => $torneos,
        ]);
    }

    public function update()
    {
        $this->resetPage();
    }

    public function buscar()
    {
        $this->resetPage();
    }

    public function showCreateButton()
    {
        return Auth::check() && Auth::user()->hasRole('admin|maestro');
    }

    public function create()
    {
        return redirect()->route('torneos-edit');
    }

    public function edit($id)
    {
        $torneo = Torneo::findOrFail($id);

        $this->torneo_id = $id;
        $this->nombre = $torneo->nombre;
        $this->descripcion = $torneo->descripcion;
        $this->fecha_evento = $torneo->fecha_evento;
        $this->fecha_registro = $torneo->fecha_registro;
        $this->direccion = $torneo->direccion;
        $this->banner_actual = $torneo->banner;
        $this->ranking = $torneo->ranking;

        return redirect()->route('torneos-edit', ['id' => $id]);
    }

    public function inscritos($id)
    {
        return redirect()->route('inscritos', ['id' => $id]);
    }

    public function resultados($id)
    {
        return redirect()->route('resultados', ['id' => $id]);
    }

    public function iniciarTorneo($id)
    {
        return redirect()->route('areas', ['torneoId' => $id, 'fechaId' => 'todas']);
    }

    public function areas($id)
    {
        $torneo = Torneo::findOrFail($id);
        $this->torneo_id = $id;
        $this->nombre = $torneo->nombre;

        $credentials = [];

        for ($area = 0; $area < 34; $area++) {
            $areaReal = $area + 1;
            $torneoUser = TorneoUser::where('torneo_id', $id)
                                     ->where('area', $area)
                                     ->first();

            if ($torneoUser) {
                $user = User::find($torneoUser->user_id);

                if ($user) {
                    $newPassword = $this->generateRandomString(16);
                    $user->update(['password' => Hash::make($newPassword)]);

                    $credentials[] = [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'password' => $newPassword,
                    ];
                }
            } else {
                $username = 'torneo_' . Str::slug($this->nombre, '_') . '_area' . $areaReal;
                $email = $username . '@example.com';
                $password = $this->generateRandomString(16);

                $user = User::create([
                    'name' => $username,
                    'email' => $email,
                    'password' => Hash::make($password),
                ]);

                $user->assignRole('torneo user');

                TorneoUser::create([
                    'user_id' => $user->id,
                    'torneo_id' => $id,
                    'area' => $area,
                ]);

                $credentials[] = [
                    'user_id' => $user->id,
                    'email' => $email,
                    'password' => $password,
                ];
            }
        }

        Session::put('credentials', $credentials);

        return redirect()->route('credenciales', ['torneoId' => $id]);
    }

    public function delete($id)
    {
        $torneo = Torneo::find($id);

        if ($torneo) {
            $torneo->delete();
            flash()->options([
                'position' => 'top-center',
            ])->addSuccess('Torneo eliminado correctamente.');
        }
    }

    private function generateRandomString($length = 16): string
    {
        return Str::random($length);
    }
}
