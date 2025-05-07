<?php

namespace App\Livewire;

use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RecuperarPassword extends Component
{
    public $email, $token, $password, $passwordConfirmation;

    #[Title('Recuperar Password')]
    #[Layout('components.layouts.app')]

    public function recuperarPassword()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        try {
            $status = Password::sendResetLink(['email' => $this->email]);

            if ($status === Password::RESET_LINK_SENT) {
                Mail::to($this->email)->send(new ResetPasswordMail($status));
                //dd($status);

                flash()->options([
                'position' => 'top-center',
            ])->addSuccess('Se ha enviado un enlace para restablecer la contraseña a tu correo electrónico.');
            } else {
                flash()->options([
                'position' => 'top-center',
            ])->addError('No se pudo enviar el enlace para restablecer la contraseña. Por favor, verifica la dirección de correo electrónico.');
            }
        } catch (\Exception $exception) {
            flash()->options([
                'position' => 'top-center',
            ])->addError('Error al enviar el correo electrónico: ' . $exception->getMessage());
        }
    }

    public function showResetForm($token)
    {
        return view('password-form', ['token' => $token]);
    }
    public function render()
    {
        return view('livewire.recuperar-password');
    }
}
