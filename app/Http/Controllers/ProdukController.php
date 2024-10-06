<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProdukResource;

class ProdukController extends Controller
{
    public function show(){
        $produk = Produk::all();
        return ProdukResource::collection($produk);
    }

    public function detail($id){
        $produk = Produk::findOrFail($id);
        return new ProdukResource($produk);
    }

    public function store(Request $request){
        $data = $request->validate([
            'nama_produk' => 'required',
            'kategori' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'gambar' => 'required'
        ]);

        Produk::create([
            'id_user' => Auth::user()->user_id,
            'nama_produk'=>$request->nama_produk,
            'kategori'=>$request->kategori,
            'deskripsi'=>$request->deskripsi,
            'harga'=>$request->harga,
            'gambar'=>$request->gambar
        ]);
        return response()->json('Produk berhasil ditambahkan', 200);
    }

    public function edit(Request $request, $id){
        $produk = Produk::findOrFail($id);
        $data = $request->validate([
            'nama_produk' => 'required',
            'kategori' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'gambar' => 'required',
        ]);
        $produk->update($request->all());
        return response()->json('Produk berhasil di-update');
    }

    public function destroy($id){
        $produk = Produk::findOrFail($id);
        $produk->delete();
        return response()->json('Produk berhasil dihapus');
    }
}
