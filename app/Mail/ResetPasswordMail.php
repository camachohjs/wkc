<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    public $resetLink;

    public function __construct($resetLink)
    {
        

        $this->resetLink = $resetLink;
    }

    public function build()
    {
        return $this->view('emails.reset_password')
            ->subject('Restablecimiento de Contraseña');
    }
}