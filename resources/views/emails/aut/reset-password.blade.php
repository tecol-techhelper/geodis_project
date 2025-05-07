{{-- View for a customize emial message to reset password --}}

@component('mail::message')

<div style="text-align: center; margin-bottom: 20px;" >
    <img src="{{ asset('images/logos/logo_top.png') }}" alt="">
</div>

Hola <b>{{ ucfirst(strtolower($username)) }}</b>,

Recibimos una solicitud para restablecer la contraseña de tu cuenta. Haz clic en el botón de abajo para continuar

@component('mail::button',['url' => $resetUrl, 'color'=>'success'])
Restablecer contraseña
@endcomponent

Si el boton no funciona, copia y pega el siguiente enlace en tu navegador:
[{{ $resetUrl }}]({{ $resetUrl }})

@endcomponent