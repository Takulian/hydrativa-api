<?php

namespace App\Http\Controllers;
use App\Models\Alat;
use App\Models\Histori;
use Illuminate\Http\Request;

class AlatController extends Controller
{

    public function show(){
        $hydra = Alat::with(['kebun', 'kebun.user'])->get();
        return $hydra;
    }

    public function store(Request $request)
    {
        $kode = $this->quickRandom();
        $alat = Alat::create([
        'alat_id' => $kode
        ]);
        return response()->json([
            'message' => 'Alat berhasil ditambahkan',
            'kode' => $kode,
            'alat' => $alat
        ], 200);
    }

    public static function quickRandom($length = 6)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $prefix = 'HYD_';
        $random = substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
        return $prefix.$random;
    }
}
