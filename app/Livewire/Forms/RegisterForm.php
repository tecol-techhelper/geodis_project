<?php

namespace App\Livewire\Forms;

use App\Enums\Rol;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RegisterForm extends Form
{
    #[Validate()]
    public string $first_name;
    public string $last_name;
    public string $username;
    public string $user_area;
    public string $email;
    public string $password;
    public string $password_confirmation;
    public UserStatus $is_active;
    public Rol $rol_id;
    public $user_icon;

    public function register() : void {
        $this->validate();



    }

    protected function rules() : array {
        return [
            'first_name' => ['string','max:64'],
            'last_name' => ['string','max:64'],
            'username' => ['required','string','max:32'],
            'user_area' => ['string','max:32'],
            'email' => ['required','email','max:256','lowercase','email:rfc,dns','unique:'.User::class],
            'password' => ['required','email','max:256','confirmed',Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'is_active' => ['required', 'integer', new Enum(UserStatus::class)],
            'rol_id' => ['required', 'integer', new Enum(Rol::class)],
            'user_icon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024']
        ];
    }

    public function messages():array{
        return [
            'required' => 'Este campo es obligatorio',
            'max' => 'Valor supera la longitud máxima',
            'confirmed' => 'Las contraseñas no coinciden',
            'mimes' => 'Formato de imagen no permitido. Solo jpg, jpeg, png o webp',
            'image' => 'Debe seleccionar una imagen válida',
            'email' => 'Debe ser un correo válido',
            'username.unique' => 'Ya existe un usuario con ese nombre',
            'email.unique' => 'Este correo ya se encuentra registrado',
            'user_icon.max' => 'El tamaño de la imagen no puede ser mayor a 1MB',
            'rol_id.required' => 'Debe asignar un Rol al usuario',
            'is_active.required' => 'Debe definir el estado del usuario'
        ];
    }

}
