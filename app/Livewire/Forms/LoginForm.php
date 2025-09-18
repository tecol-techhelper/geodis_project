<?php

namespace App\Livewire\Forms;

use App\Core\InternalControllers\BlockedIpController;
use App\Core\InternalControllers\FailedSessionController;
use App\Core\InternalControllers\SessionLogController;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string')]
    public string $username = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIpIsNotBlocked();

        $userName  = User::where('username', $this->username)->first();

        // For validating if user exists
        if (!$userName) {
            $ipAddress = request()->ip();
            (new FailedSessionController())->logFailedSession($this->username, $ipAddress, request()->userAgent(), 'Usuario no encontrado');
            (new BlockedIpController)->evaluateAndBlockIp($ipAddress);
            throw ValidationException::withMessages([
                'form.username' => 'Usuario no encontrado'
            ]);
        }

        // For validating if user is active
        if ($userName->is_active !== 1) {
            throw ValidationException::withMessages([
                'form.username' => 'Usuarios inhabilitado. Comunicarse con el administrador'
            ]);
        }

        if (! Auth::attempt($this->only(['username', 'password']), $this->remember)) {
            RateLimiter::hit($this->throttleKey());
            (new FailedSessionController())->logFailedSession($this->username, request()->ip(), request()->userAgent(), 'Credenciales incorrectas');
            throw ValidationException::withMessages([
                'form.username' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        Session::regenerate();

        (new SessionLogController())->logSession(
            Auth::id(),
            Auth::user()->username,
            Auth::user()->role->rol_key,
            request()->ip(),
            request()->userAgent(),
            session()->id()
        );
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIpIsNotBlocked(): void
    {
        $ipAddress = request()->ip();

        $blocked = (new BlockedIpController())->isBlocked($ipAddress);

        if ($blocked) {
            throw ValidationException::withMessages([
                'username' => 'Ty IP ha sido bloqueada por seguridad. Contacta con el administrador'
            ]);
        }
    }
    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->username) . '|' . request()->ip());
    }
}
