<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\User;
use App\Models\Kebun;
use App\Models\Histori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
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
        return response()->json(KebunResource::collection($kebun));
    }

    public function detailKebun($id){
        $kebun = Kebun::findOrFail($id);
        $histori_terakhir = $kebun->histori()->latest()->first();
        if(!$histori_terakhir){
            return response()->json([
                'kebun_id' => $kebun->kebun_id,
                'nama_kebun' => $kebun->nama_kebun,
                'luas_lahan' => $kebun->luas_lahan,
                'lokasi_kebun' => $kebun->lokasi_kebun,
                'gambar' => $kebun->gambar,
                'moisture' => 0,
                'pH' => 0.00,
                'status' => "Kering",
            ]);
        }
        return response()->json([
            'kebun_id' => $kebun->kebun_id,
            'nama_kebun' => $kebun->nama_kebun,
            'luas_lahan' => $kebun->luas_lahan,
            'lokasi_kebun' => $kebun->lokasi_kebun,
            'gambar' => $kebun->gambar,
            'moisture' => $histori_terakhir->moisture,
            'pH' => $histori_terakhir->pH,
            'status' => $histori_terakhir->status,
        ]);
    }

    public function store(Request $request){
        $data = $request->validate([
            'nama_kebun'=>'required',
            'luas_lahan'=>'required',
            'lokasi_kebun'=>'required',
            'kode_alat' => 'required'
        ]);

        $data = Kebun::create([
            'id_user'=>Auth::user()->user_id,
            'nama_kebun'=>$request->nama_kebun,
            'luas_lahan'=>$request->luas_lahan,
            'lokasi_kebun'=>$request->lokasi_kebun,
            'id_alat' =>$request->kode_alat,
            'gambar' => null
        ]);
        if($request->hasFile('gambar')){
            $file = $request->file('gambar');
            $fileName = $this->quickRandom().'.'.$file->extension();
            $path = $file->storeAs('kebun', $fileName, 'public');
            $data->update([
                'gambar' => $path
            ]);
        }
        return response()->json([
            'message' => 'Kebun berhasil ditambah'
        ]);
    }

    public function update(Request $request, $id){
        $cari = Kebun::findOrFail($id);
        $request->validate([
            'nama_kebun'=>'required',
            'luas_lahan'=>'required',
            'lokasi_kebun'=>'required',
            'gambar' => 'required'
        ]);
        if($request->hasFile('gambar')){
            $pathLama = storage_path('app/public/'.$cari->gambar);
            if(File::exists($pathLama)){
                File::delete($pathLama);
                $file = $request->file('gambar');
                $fileName = $this->quickRandom().'.'.$file->extension();
                $path = $file->storeAs('kebun', $fileName, 'public');
                $cari->update([
                    'gambar' => $path
                ]);
            }
        }
        $cari->update([
            'nama_kebun' => $request->nama_kebun,
            'luas_lahan' => $request->luas_lahan,
            'lokasi_kebun' => $request->lokasi_kebun
        ]);
        return response()->json([
            'message' => 'Kebun berhasil di-update'
        ]);
    }

    public function destroy($id){
        $cari = Kebun::findOrFail($id);
        $path = storage_path('app/public/'.$cari->gambar);
        if(File::exists($path)){
            File::delete($path);
        }
        $cari->delete();
        return response()->json([
            'message' => 'Kebun berhasil dihapus'
        ]);
    }

    public function updateStatus(Request $request,String $id){
        $request->validate([
            'moisture' => 'required',
            'pH' => 'required',
            'status' => 'required',
        ]);
        $kebun = Kebun::where('id_alat', $id)->first();
        Histori::create([
            'id_kebun' => $kebun->kebun_id,
            'moisture' => $request->moisture,
            'pH' => $request->pH,
            'status' => $request->status
        ]);
        return response()->json([
            'message' => 'Status berhasil diperbarui'
        ]);
    }

    public function histori($id){
        $histori = Histori::where('id_kebun', $id)->get();
        return response()->json(HistoriResource::collection($histori));
    }

    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}
