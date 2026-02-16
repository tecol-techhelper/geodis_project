<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'segment_tag',
        'note_text',
        'raw_segment',
        'purchase_order_item_id',
        'note_type_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'purchase_order_item_id' => 'integer',
        'note_type_id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relation (as son)

    // 1-to-1 with note_type table
    public function note_type(): BelongsTo
    {
        return $this->belongsTo(NoteType::class, 'note_type_id');
    }

    // 1-to-N with service table
    public function purchase_order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'purchase_order_id');
    }
}
