<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $table = 'master_parts';

    protected $primaryKey = 'part_number';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'part_number',
        'nama_part',
        'harga',
        'qty_stock',
        'qty_rfs',
        'qty_book',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'integer',
        'qty_stock' => 'integer',
        'qty_rfs' => 'integer',
        'qty_book' => 'integer',
        'is_active' => 'boolean',
    ];
}
