<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Hash;

class PasswordForm extends Component
{
    public $email, $token, $password, $password_confirmation;
    
    public $showPassword = false;

    #[Title('Recuperar Password')]

    public function resetPassword()
    {
        $this->validate([
            'password' => 'required|min:4',
            'password_confirmation' => 'required|same:password',
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'token' => $this->token,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        /* dd($status, $this->email, $this->token, $this->password, $this->password_confirmation); */

        if ($status === Password::PASSWORD_RESET) {
            flash()->options([
                'position' => 'top-center',
            ])->addSuccess('Contraseña restablecida con éxito.');
            redirect()->route('login');
        } else {
            flash()->options([
                'position' => 'top-center',
            ])->addError('No se pudo restablecer la contraseña. Intenta de nuevo.');
        }
    }

    public function toggleShowPassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function render()
    {
        return view('livewire.password-form');
    }
}
