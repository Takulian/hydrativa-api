<?php

namespace App\Models;

use App\Models\Alat;
use App\Models\User;
use App\Models\Histori;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kebun extends Model
{
    use HasFactory;

    protected $table = 'kebun';
    protected $primaryKey = 'kebun_id';
    protected $fillable = [
        'id_user',
        'id_alat',
        'nama_kebun',
        'luas_lahan',
        'lokasi_kebun',
        'gambar'
    ];
    public $timestamps = false;


    public function histori(): HasMany
    {
        return $this->hasMany(Histori::class, 'id_kebun', 'kebun_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'user_id');
    }
    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'id_alat', 'alat_id');
    }
}
