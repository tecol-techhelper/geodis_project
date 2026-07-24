<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;


use App\Models\Concerns\OptionalPassportHasApiTokens;
use App\Enums\UserStatus;
use App\Mail\CustomResetPassword;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use OptionalPassportHasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'username',
        'user_area',
        'email',
        'password',
        'user_icon',
        'is_active',
        'guard_name'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'int'
        ];
    }

    // Hashing password
    public function setPasswordAttribute($value): void
    {
        if ($value) {
            $this->attributes['password'] = Hash::needsRehash($value)
                ? Hash::make($value)
                : $value;
        }
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => trim((string) $this->first_name . ' ' . (string) $this->last_name),
            set: function ($value): array {
                $parts = preg_split('/\s+/', trim((string) $value), 2);

                return [
                    'first_name' => $parts[0] ?? null,
                    'last_name' => $parts[1] ?? null,
                ];
            },
        );
    }

    public function isActive()
    {
        return $this->is_active;
    }

    // Relation many-to-many with roles through role_user table
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    // Relation many-to-many with permissions through permissions_users table
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_users');
    }

    // For checking if user has a defined role
    public function hasRole($key): bool
    {
        return $this->roles()->where('rol_key', $key)->exists();
    }

    // For checking if user has a permission, by rol or direct assignment
    public function hasPermission($key): bool
    {
        $rolePermissions = $this->roles()->whereHas('permissions', fn($q) => $q->where('permission_key', $key))->exists();

        $userPermissions = $this->permissions()->where('permission_key', $key)->exists();

        return $rolePermissions || $userPermissions;
    }

    // Overwriting reset-pass notification
    public function sendPasswordResetNotification($token)
    {
        Mail::to($this->email)->send(new CustomResetPassword($token, $this->email, $this->first_name . " " . $this->last_name));
    }
}
