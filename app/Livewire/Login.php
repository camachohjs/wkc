<?php

namespace App\Livewire;

use App\Models\TorneoUser;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Title;

class Login extends Component
{
    public $email, $password, $rememberMe;
    public $showPassword = false;
    public $showError = false;

    #[Title('Iniciar Sesion')]

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password,], $this->rememberMe)) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();

            if ($this->rememberMe) {
                session()->put('login_remembered_email', $this->email);
                session()->put('login_remembered_password', $this->password);
                cookie()->forever('remember_Me', true);
            } else {
                session()->forget('login_remembered_email');
                session()->forget('login_remembered_password');
                cookie()->forget('remember_Me');
            }

            /* $existingSession = $user->current_session_id;

            if ($existingSession && $existingSession !== Session::getId()) {
                // El usuario ya tiene una sesión activa en otro lugar
                $this->showError = true;
                $this->addError('error', 'Ya tienes una sesión activa en otro lugar 😓.');
                return;
            } */

            $user->current_session_id = Session::getId();
            $user->save();

            if ($user->hasRole('torneo user')) {
                $torneoUser = TorneoUser::where('user_id', $user->id)->first();
                if ($torneoUser) {
                    return redirect()->route('areas-categorias', [
                        'torneoId' => $torneoUser->torneo_id,
                        'fechaId' => '0',
                        'areaId' => $torneoUser->area
                    ]);
                }
            }

            return redirect('/panel');
        }

        $this->showError = true;
        $this->addError('error', 'Credenciales incorrectas.');
    }

    public function mount()
    {
        $this->email = session('login_remembered_email', '');
        $this->password = session('login_remembered_password', '');
        $this->rememberMe = session('login_remembered_email') !== null && session('login_remembered_password') !== null;
    }

    public function toggleShowPassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function logout()
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $user->current_session_id = null;
            $user->save();
        }

        auth('web')->logout();

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.login');
    }
}
