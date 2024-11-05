<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alamat extends Model
{
    use HasFactory;

    protected $table = 'alamat';
    protected $primaryKey = 'alamat_id';
    protected $fillable = [
        'id_user',
        'no_telepon',
        'label_alamat',
        'nama_penerima',
        'detail',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kodepos',
        'isPrimary',
        'catatan_kurir'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'user_id');
    }

}
