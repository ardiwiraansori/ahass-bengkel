<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceJob extends Model
{
    protected $table = 'master_jobs';

    protected $primaryKey = 'id_job';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_job',
        'kode_motor',
        'keterangan',
        'harga',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'integer',
        'is_active' => 'boolean',
    ];
}
