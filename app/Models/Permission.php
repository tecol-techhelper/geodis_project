<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    // use SoftDeletes;

    //For create_at and update_at
    public $timestamps = true;

    // Attributes can be filled by functions. A whitelist to prevent mass assignment vulnerabilities
    protected $fillable = [
        'permission_key',
        'permission_name',
        'permission_description'
    ];

    // Relation many-to-many with roles table through permission_roles table
    public function rol() : BelongsToMany {
        return $this->belongsToMany(Role::class, 'permission_roles');
    }

    // Polymorfic relation many-to-many with users table through role_users table
    public function users() : BelongsToMany {
        return $this->belongsToMany(User::class, 'permission_users');
    }
}
