<?php

namespace App\Livewire;

use App\Models\TorneoUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Credenciales extends Component
{
    #[Title('Credenciales')]
    #[Layout('components.layouts.layout')]
    public $email;
    public $password;
    public $torneoId;
    public $editPassword;
    public $editEmail;
    public $editUserId;
    public $id;
    public $mostrar = false;
    public $users;
    public $credentials;

    public function mount($torneoId)
    {
        $this->torneoId = $torneoId;
        $this->users = TorneoUser::where('torneo_id', $torneoId)->with('user')->get();
        $this->credentials = session()->pull('credentials', []);

        session()->put('credentials', $this->credentials);
    }

    public function editarUsuario($id)
    {
        $this->mostrar = true;
        $user = User::findOrFail($id);
        $this->editUserId = $user->id;
        $this->editEmail = $user->email;
    }

    private function updateCredentialsInSession($userId, $email, $password = null)
    {
        $credentials = session()->pull('credentials', []);
        foreach ($credentials as &$cred) {
            if ($cred['user_id'] == $userId) {
                $cred['email'] = $email;
                if ($password !== null) {
                    $cred['password'] = $password;
                }
                break;
            }
        }

        session()->put('credentials', $credentials);
    }

    public function actualizarUsuario()
    {
        $this->validate([
            'editEmail' => 'required|email|max:255',
            'editPassword' => 'nullable|string|min:8',
        ]);

        $user = User::findOrFail($this->editUserId);
        $user->email = $this->editEmail;
        if ($this->editPassword) {
            $newPassword = $this->editPassword;
            $user->password = Hash::make($newPassword);
        } else {
            $newPassword = null;
        }
        $user->save();

        $this->users = TorneoUser::where('torneo_id', $this->torneoId)->with('user')->get();

        $this->updateCredentialsInSession($this->editUserId, $this->editEmail, $newPassword);

        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Usuario actualizado correctamente.');

        $this->reset(['editUserId', 'editEmail', 'editPassword']);
        $this->dispatch('close-modal');
        return redirect()->route('credenciales', [
            'torneoId' => $this->torneoId,
        ]);
    }

    private function removeCredentialsFromSession($userId)
    {
        $credentials = session()->pull('credentials', []);
        $credentials = array_filter($credentials, function($cred) use ($userId) {
            return isset($cred['user_id']) && $cred['user_id'] != $userId;
        });

        session()->put('credentials', array_values($credentials));
    }

    public function borrarUsuario($id)
    {
        TorneoUser::where('user_id', $id)->where('torneo_id', $this->torneoId)->delete();
        User::find($id)->forceDelete();

        $this->removeCredentialsFromSession($id);

        $this->users = TorneoUser::where('torneo_id', $this->torneoId)->with('user')->get();

        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Usuario borrado correctamente.');
    }

    public function render()
    {
        return view('livewire.credenciales', [
            'users' => $this->users,
        ]);
    }
}