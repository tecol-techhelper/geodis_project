<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PowerComponents\LivewirePowerGrid\Concerns\SoftDeletes;

class NoteType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'note_type_code',
        'note_type_name',
        'note_type_description',
    ];

    protected $casts = [
        'id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // Relation (as father)

    // 1-to-1 with item_note table
    public function item_notes(): HasMany
    {
        return $this->hasMany(ItemNote::class, 'note_types_id');
    }

    // 1-to-1 with item_note table
    public function purchase_order_note(): HasMany
    {
        return $this->hasMany(ItemNote::class, 'note_types_id');
    }
}
