<?php

namespace App\Models;

use App\Models\Kebun;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Histori extends Model
{
    use HasFactory;

    protected $table = 'histori';
    protected $primaryKey = 'histori_id';
    protected $fillable = [
        'id_kebun',
        'moisture',
        'pH',
        'status'
    ];

    public function kebun(): BelongsTo
    {
        return $this->belongsTo(Kebun::class, 'id_kebun', 'kebun_id');
    }
}
