<?php

namespace App\Livewire\Forms;

use App\Enums\Rol;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class UserForm extends Form
{
    use WithFileUploads;

    public ?User $user = null;

    public string $first_name = '';
    public string $last_name = '';
    public string $username = '';
    public string $user_area = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public int $is_active = 1;
    public int $role_id = 1;
    public $user_icon;

    public function mount(?User $user = null): void
    {
        if ($user) {
            $this->user = $user;
            $this->username = $user->username ?? '';
            $this->first_name = $user->first_name ?? '';
            $this->last_name = $user->last_name ?? '';
            $this->user_area = $user->user_area ?? '';
            $this->email = $user->email ?? '';
            $this->is_active = $user->is_active ?? 0;
            $this->role_id = $user->role_id ?? 0;
        }
    }

    public function save(): User
    {
        $this->validate();

        $user = $this->user ?? new User();

        $user->username = $this->username;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->user_area = $this->user_area;
        $user->email = $this->email;
        $user->is_active = $this->is_active;
        $user->rol_id = $this->role_id;

        if ($this->password) {
            $user->forceFill([
                'password' => $this->password
            ]);
        }

        if ($this->user_icon) {
            if ($user->user_icon && Storage::exists($user->user_icon)) {
                Storage::delete($user->user_icon);
            }
            $path = $this->user_icon->store('users/icon', 'public');
            $user->user_icon = $path;
        }

        $user->save();

        return $user;
    }

    protected function rules(): array
    {
        return [
            'first_name' => ['string', 'max:64'],
            'last_name' => ['string', 'max:64'],
            'username' => ['required', 'string', 'max:32', 'unique:' . User::class],
            'user_area' => ['string', 'max:32'],
            'email' => ['required', 'email', 'max:256', 'lowercase', 'unique:' . User::class],
            'password' => ['required', 'max:256', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'is_active' => ['required', 'integer', new Enum(UserStatus::class)],
            'role_id' => ['required', 'integer', new Enum(Rol::class)],
            'user_icon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024']
        ];
    }

    public function messages(): array
    {
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
            'role_id.required' => 'Debe asignar un Rol al usuario',
            'is_active.required' => 'Debe definir el estado del usuario'
        ];
    }
}
