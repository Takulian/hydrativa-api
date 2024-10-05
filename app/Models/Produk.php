<?php

namespace App\Models;

use App\Models\User;
use App\Models\TransaksiItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'produk_id';
    protected $fillable = [
        'id_user',
        'nama_produk',
        'kategori',
        'deskripsi',
        'harga',
        'gambar'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'user_id');
    }

    public function transaksiItem(): HasMany
    {
        return $this->hasMany(TransaksiItem::class, 'id_produk', 'produk_id');
    }
}
