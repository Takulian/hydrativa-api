<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';
    protected $primaryKey = 'materi_id';
    protected $fillable = [
        'judul',
        'deskripsi',
        'sumber',
        'gambar'
    ];
}
