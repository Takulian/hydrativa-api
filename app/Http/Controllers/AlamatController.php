<?php

namespace App\Http\Controllers;

use App\Http\Resources\alamatResource;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlamatController extends Controller
{


    public function store(Request $request)
    {
        $data = $request->validate([
            'label_alamat' => 'required',
            'nama_penerima' => 'required',
            'no_telepon' => 'required',
            'detail' => 'required',
            'kelurahan' => 'required',
            'kecamatan' => 'required',
            'kabupaten' => 'required',
            'provinsi' => 'required',
            'kodepos' => 'required',
            'catatan_kurir' => 'nullable|string',
        ]);
        $user = Auth::user();
        $cari = Alamat::where('id_user', $user->user_id)->get();
        if(!$cari){
            Alamat::create([
                'id_user' => Auth::user()->user_id,
                'label_alamat' => $data['label_alamat'],
                'nama_penerima' => $data['nama_penerima'],
                'no_telepon' => $data['no_telepon'],
                'detail' => $data['detail'],
                'kelurahan' => $data['kelurahan'],
                'kecamatan' => $data['kecamatan'],
                'kabupaten' => $data['kabupaten'],
                'provinsi' => $data['provinsi'],
                'kodepos' => $data['kodepos'],
                'isPrimary' => 1,
                'catatan_kurir' => $data['catatan_kurir'],
            ]);
        }elseif($cari){
            Alamat::create([
                'id_user' => Auth::user()->user_id,
                'label_alamat' => $data['label_alamat'],
                'nama_penerima' => $data['nama_penerima'],
                'no_telepon' => $data['no_telepon'],
                'detail' => $data['detail'],
                'kelurahan' => $data['kelurahan'],
                'kecamatan' => $data['kecamatan'],
                'kabupaten' => $data['kabupaten'],
                'provinsi' => $data['provinsi'],
                'kodepos' => $data['kodepos'],
                'isPrimary' => 0,
                'catatan_kurir' => $data['catatan_kurir'],
            ]);
        }
        return response()->json([
            'message' => 'Alamat berhasil ditambah'
        ]);

    }

    public function show()
    {
        $user = Auth::user();
        $cari = Alamat::where('id_user', $user->user_id)->get();
        return response()->json(AlamatResource::collection($cari));
    }

    public function update(Request $request, $id)
    {
        $cari = Alamat::findOrFail($id);
        $data = $request->validate([
            'label_alamat' => 'required',
            'nama_penerima' => 'required',
            'no_telepon' => 'required',
            'detail' => 'required',
            'kelurahan' => 'required',
            'kecamatan' => 'required',
            'kabupaten' => 'required',
            'provinsi' => 'required',
            'kodepos' => 'required',
            'isPrimary' => 'integer',
            'catatan_kurir' => 'nullable|string',
        ]);

        $cari->update($request->all());
        return response()->json([
            'message' => 'Alamat berhasil diperbarui'
        ]);


    }

    public function destroy($id)
    {
        $cari = Alamat::findOrFail($id);
        if ($cari->isPrimary == false) {
            $cari->delete();
            return response()->json([
                'message' => 'Alamat berhasil dihapus'
            ]);
        } else{
            return response()->json([
                'message' => 'Alamat utama tidak dapat dihapus'
            ]);
        }
    }

    public function utama($id){
        $user = Auth::user();
        $cari = Alamat::where('alamat_id', $id)->first();
        $primary = Alamat::where('id_user', $user->user_id)->where('isPrimary', true)->first();
        $cari->update([
            'isPrimary' => true
        ]);
        $primary->update([
            'isPrimary' => false
        ]);
        return response()->json([
            'message' => 'Alamat utama telah diganti'
        ]);
    }
}
