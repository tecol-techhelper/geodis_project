<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_area',
        'email',
        'password',
        'is_active',
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
            'is_active' => UserStatus::class
        ];
    }

    // Hashing password
    public function setPasswordAttribute($value){
        if($value){
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function isActive(){
        return $this->is_active;
    }

    // Relation one-to-one with roles table
    public function role(): BelongsTo
    {
        return $this -> belongsTo(Role::class);
    }

    // Polymorphic relation many-to-many with permissions through permissions_users table
    public function customPermissions()
    {
        return $this->morphToMany(Permission::class, 'userable', 'permission_users', 'userable_id', 'permission_id')
                    ->withTimestamps();
    }

    // For checking if user has a defined role
    public function hasRole($role):bool
    {
        return $this->role && $this->role->rol_key === $role;
    }

    // For checking if user has a permission, by rol or direct assignment
    public function hasPermission($permissionKey):bool
    {
        $rolePermissions = $this->role?->permissions()->where('permission_key', $permissionKey)->exist() ?? false;

        $userPermissions = $this->customPermissions()->where('permission_key', $permissionKey)->exist();

        return $rolePermissions || $userPermissions;
    }
}
