<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\User;
use App\Models\Kebun;
use App\Models\Histori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\KebunResource;
use App\Http\Resources\HistoriResource;

class KebunController extends Controller
{
    public function show(){
        $kebun = Kebun::all();
        return KebunResource::collection($kebun);
    }

    public function profileKebun(){
        $kebun = Kebun::where('id_user', Auth::user()->user_id)->get();
        return KebunResource::collection($kebun);
    }

    public function store(Request $request){
        $data = $request->validate([
            'nama_kebun'=>'required',
            'luas_lahan'=>'required',
            'lokasi_kebun'=>'required',
            'kode_alat' => 'required'
        ]);

        $alat = Alat::where('kode_alat', $request->kode_alat)->first();
        Kebun::create([
            'id_user'=>Auth::user()->user_id,
            'nama_kebun'=>$request->nama_kebun,
            'luas_lahan'=>$request->luas_lahan,
            'lokasi_kebun'=>$request->lokasi_kebun,
            'id_alat' =>$alat->alat_id
        ]);
        return response()->json([
            'message' => 'Kebun berhasil ditambah'
        ]);
    }

    public function histori($id){
        $histori = Histori::where('id_kebun', $id)->get();
        return HistoriResource::collection($histori);
    }
}
