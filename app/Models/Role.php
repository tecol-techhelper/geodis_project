<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    //For create_at and update_at
    public $timestamps = true;

    // Attributes can be filled by functions. A whitelist to prevent mass assignment vulnerabilities
    protected $fillable = [
        'rol_key',
        'rol_name',
        'rol_description'
    ];

    // Relation many-to-many with permissions table through permission_roles table
    public function permissions() : BelongsToMany {
        return $this->belongsToMany(Permission::class, 'permission_roles');
    }

    // Relation many-to-many with users table through role_users table
    public function users() : HasMany {
        return $this->hasMany(User::class);
    }
}
