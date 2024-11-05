<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hydrativa extends Model
{
    use HasFactory;
    protected $table = 'hydrativas';

    protected $fillable = [
        'moisture', // Kolom yang dapat diisi
        'pH',
        'status'
    ];
}
