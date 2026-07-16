<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkOrder extends Model
{
    public const STATUS_DRAFT = 'DRAFT';
    public const STATUS_MENUNGGU = 'MENUNGGU';
    public const STATUS_DIKERJAKAN = 'DIKERJAKAN';
    public const STATUS_SELESAI = 'SELESAI';
    public const STATUS_BATAL = 'BATAL';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_MENUNGGU,
        self::STATUS_DIKERJAKAN,
        self::STATUS_SELESAI,
        self::STATUS_BATAL,
    ];

    public const DGI_PENDING = 'PENDING';
    public const DGI_SENT = 'SENT';
    public const DGI_FAILED = 'FAILED';

    public const DGI_STATUSES = [
        self::DGI_PENDING,
        self::DGI_SENT,
        self::DGI_FAILED,
    ];

    protected $primaryKey = 'id_wo';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_wo',
        'id_sa',
        'id_mekanik',
        'status',
        'total_jasa',
        'total_part',
        'diskon',
        'grand_total',
        'metode_pembayaran',
        'jumlah_bayar',
        'kembalian',
        'catatan_mekanik',
        'started_at',
        'finished_at',
        'paid_at',
        'dgi_status',
        'dgi_response',
        'dgi_sent_at',
    ];

    protected $casts = [
        'total_jasa' => 'integer',
        'total_part' => 'integer',
        'diskon' => 'integer',
        'grand_total' => 'integer',
        'jumlah_bayar' => 'integer',
        'kembalian' => 'integer',

        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'paid_at' => 'datetime',
        'dgi_sent_at' => 'datetime',

        'dgi_response' => 'array',
    ];

    public function serviceAdvisorForm(): BelongsTo
    {
        return $this->belongsTo(
            ServiceAdvisorForm::class,
            'id_sa',
            'id_sa'
        );
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(
            Mechanic::class,
            'id_mekanik',
            'id_mekanik'
        );
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(
            WorkOrderJob::class,
            'id_wo',
            'id_wo'
        );
    }

    public function parts(): HasMany
    {
        return $this->hasMany(
            WorkOrderPart::class,
            'id_wo',
            'id_wo'
        );
    }

    public function isEditable(): bool
    {
        return in_array(
            $this->status,
            [
                self::STATUS_DRAFT,
                self::STATUS_MENUNGGU,
            ],
            true
        );
    }

    public function isFinished(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_BATAL;
    }

    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }

    public function recalculateTotals(): void
    {
        $totalJasa = (int) $this->jobs()
            ->sum('subtotal');

        $totalPart = (int) $this->parts()
            ->sum('subtotal');

        $diskon = (int) $this->diskon;

        $grandTotal = max(
            0,
            ($totalJasa + $totalPart) - $diskon
        );

        $this->update([
            'total_jasa' => $totalJasa,
            'total_part' => $totalPart,
            'grand_total' => $grandTotal,
        ]);
    }
}
