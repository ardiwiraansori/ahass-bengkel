<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $primaryKey = 'id_customer';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_customer',
        'nama_customer',
        'no_hp',
        'email',
        'no_identitas',
        'alamat',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'id_customer', 'id_customer');
    }
}
