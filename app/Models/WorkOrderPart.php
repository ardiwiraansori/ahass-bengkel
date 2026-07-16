<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrderPart extends Model
{
    protected $fillable = [
        'id_wo',
        'part_number',
        'nama_part',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga_satuan' => 'integer',
        'subtotal' => 'integer',
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(
            WorkOrder::class,
            'id_wo',
            'id_wo'
        );
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(
            Part::class,
            'part_number',
            'part_number'
        );
    }

    public static function calculateSubtotal(
        int $qty,
        int $hargaSatuan
    ): int {
        return $qty * $hargaSatuan;
    }
}
