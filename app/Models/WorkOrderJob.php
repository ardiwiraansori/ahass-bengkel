<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrderJob extends Model
{
    protected $fillable = [
        'id_wo',
        'id_job',
        'keterangan_job',
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

    public function serviceJob(): BelongsTo
    {
        return $this->belongsTo(
            ServiceJob::class,
            'id_job',
            'id_job'
        );
    }

    public static function calculateSubtotal(
        int $qty,
        int $hargaSatuan
    ): int {
        return $qty * $hargaSatuan;
    }
}
