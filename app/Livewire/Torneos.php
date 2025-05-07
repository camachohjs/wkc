<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Torneo;
use App\Models\TorneoUser;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class Torneos extends Component
{
    use WithPagination, WithFileUploads;
    public $torneo_id;
    public $nombre, $descripcion, $fecha_evento, $fecha_registro, $direccion, $banner, $banner_actual, $ranking, $search;
    public $isOpen = 0;
    public $modalFormVisible = false;
    public $perPage =  25;
    public $username;
    public $password;
    public $email;

    #[Title('Torneos')]
    #[Layout('components.layouts.layout')]

    public function render()
    {
        $query = Torneo::query();
        /* $query->where('fecha_evento', '>=', now()->subDay()); */

        if ($this->search) {
            $query->where(function ($subquery) {
                $subquery->where('nombre', 'LIKE', "%{$this->search}%")
                ->orWhere('direccion', 'like', "%{$this->search}%")
                ->orWhere('descripcion', 'like', "%{$this->search}%")
                ->orWhere('fecha_evento', 'like', "%{$this->search}%")
                ->orWhere('fecha_registro', 'like', "%{$this->search}%")
                ->orWhere('ranking', 'like', "%{$this->search}%");
            });
        }

        $torneos = $query->orderBy('fecha_evento', 'DESC')
                            ->paginate($this->perPage);

        return view('livewire.torneos', [
            'torneos' => $torneos,
        ]);
    }

    public function update()
    {
        $currentPage = 1;
        Paginator::currentPageResolver(function () use ($currentPage) {
        return  $currentPage;
        });
	}

    public function buscar()
    {
        $this->resetPage();
    }

    public function create()
    {
        return redirect('torneos-edit');
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

    public function inscritos($id) {
        return redirect()->route('inscritos', ['id' => $id]);
    }

    public function resultados($id) {
        return redirect()->route('resultados', ['id' => $id]);
    }

    public function iniciarTorneo($id) {
        return redirect()->route('areas', ['torneoId' => $id , 'fechaId' => 'todas' ]);
    }

    public function generateRandomString($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:,.<>?';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function areas($id)
    {
        $torneo = Torneo::findOrFail($id);
        $this->torneo_id = $id;
        $this->nombre = $torneo->nombre;
        $credentials = [];

        for ($area = 0; $area < 34; $area++) {
            $areaReal = $area + 1;
            $torneoUser = TorneoUser::where('torneo_id', $id)->where('area', $area)->first();

            if ($torneoUser) {
                $user = User::find($torneoUser->user_id);
                $newPassword = $this->generateRandomString(16);
                $user->update(['password' => Hash::make($newPassword)]);
                $credentials[] = [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'password' => $newPassword,
                ];
            } else {
                $username = 'torneo_' . Str::slug($this->nombre, '_');
                $password = $this->generateRandomString(16);
                $email = $username . '@area'.$areaReal.'.com';
            
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

        session()->put('credentials', $credentials);

        return redirect()->route('credenciales', [
            'torneoId' => $id,
        ]);
    }

    public function showCreateButton()
    {
        return Auth::user() && Auth::user()->maestro;
    }

    public function delete($id)
    {
        Torneo::find($id)->delete();
        flash()->options([
                'position' => 'top-center',
            ])->addSuccess('Torneo eliminado correctamente.');
    }

}
