<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    protected $fillable = [
        'id_customer',
        'no_plat',
        'kode_motor',
        'nama_unit',
        'tahun',
        'no_rangka',
        'no_mesin',
    ];

    protected $casts = [
        'tahun' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Customer::class,
            'id_customer',
            'id_customer'
        );
    }
}
