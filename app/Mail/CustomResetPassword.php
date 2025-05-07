<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public string $token;
    public string $email;
    public string $username;


    public function __construct($token, $email, $username)
    {
        $this->token = $token;
        $this->email = $email;
        $this->username = $username;
    }

    public function build()
    {
        $resetUrl = url(route('password.reset',['token'=>$this->token,'email'=>$this->email], false));
        return $this->subject('Restablecimiento de contraseña')
                    ->markdown('emails.aut.reset-password',['resetUrl'=>$resetUrl, 'username'=>$this->username]);
    }

}
