<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';
    protected $primaryKey = 'materi_id';
    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar'
    ];
}
