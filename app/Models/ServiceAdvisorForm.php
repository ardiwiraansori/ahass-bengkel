<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceAdvisorForm extends Model
{
    public const STATUS_OPEN = 'OPEN';
    public const STATUS_CONVERTED = 'CONVERTED';
    public const STATUS_CANCELLED = 'CANCELLED';

    public const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_CONVERTED,
        self::STATUS_CANCELLED,
    ];

    protected $primaryKey = 'id_sa';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_sa',
        'id_customer',
        'vehicle_id',
        'tanggal_kedatangan',
        'kilometer',
        'keluhan',
        'catatan_sa',
        'status',
    ];

    protected $casts = [
        'vehicle_id' => 'integer',
        'tanggal_kedatangan' => 'datetime',
        'kilometer' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Customer::class,
            'id_customer',
            'id_customer'
        );
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(
            Vehicle::class,
            'vehicle_id',
            'id'
        );
    }

    public function workOrder(): HasOne
    {
        return $this->hasOne(
            WorkOrder::class,
            'id_sa',
            'id_sa'
        );
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isConverted(): bool
    {
        return $this->status === self::STATUS_CONVERTED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }
}
