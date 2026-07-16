<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    protected $primaryKey = 'id_mekanik';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_mekanik',
        'honda_id_mekanik',
        'nama_mekanik',
        'no_hp',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'honda_id_mekanik';
    }
}
