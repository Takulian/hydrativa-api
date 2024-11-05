<?php

namespace App\Http\Controllers;

use App\Http\Resources\alamatResource;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlamatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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
            'isPrimary' => 'integer', 
            'catatan_kurir' => 'nullable|string',
        ]);
        
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
            'isPrimary' => $data['isPrimary'],
            'catatan_kurir' => $data['catatan_kurir'],
        ]);
        
        return response()->json([
            'message' => 'Alamat berhasil ditambah'
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = Auth::user();
        $cari = Alamat::where('id_user', $user->user_id)->get();
        return AlamatResource::collection($cari);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alamat $alamat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alamat $alamat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alamat $alamat)
    {
        //
    }
}
