<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alat extends Model
{
    use HasFactory;
    protected $table = 'alat';
    protected $primaryKey = 'alat_id';
    protected $fillable = [
        'kode_alat',
        'moisture',
        'pH',
        'status'
    ];

    public $timestamps = false;

    public function kebun(): HasOne
    {
        return $this->hasOne(Kebun::class, 'id_alat', 'alat_id');
    }
}
