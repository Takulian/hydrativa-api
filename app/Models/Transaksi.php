<?php

namespace App\Models;

use App\Models\User;
use App\Models\Alamat;
use App\Models\TransaksiItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'transaksi_id';
    protected $fillable = [
        'total',
        'status',
        'id_alamat',
        'resi',
        'snaptoken'
    ];


    public function transaksiItem(): HasMany
    {
        return $this->hasMany(TransaksiItem::class, 'id_transaksi', 'transaksi_id');
    }
    public function alamat(): belongsTo
    {
        return $this->belongsTo(Alamat::class, 'id_alamat', 'alamat_id');
    }
}
