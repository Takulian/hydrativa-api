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
        return response()->json(ProdukResource::collection($produk));
    }

    public function detail($id){
        $produk = Produk::findOrFail($id);
        return response()->json(new ProdukResource($produk));
    }

    public function store(Request $request){
        $data = $request->validate([
            'nama_produk' => 'required',
            'kategori' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'gambar' => 'required',
            'stok' => 'required|integer|min:0' 
        ]);

        Produk::create([
            'id_user' => Auth::user()->user_id,
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'gambar' => $request->gambar,
            'stok' => $request->stok 
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambah'
        ]);
    }

    public function edit(Request $request, $id){
        $produk = Produk::findOrFail($id);
        $data = $request->validate([
            'nama_produk' => 'required',
            'kategori' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'gambar' => 'required',
            'stok' => 'required|integer|min:0', // Add stok validation
        ]);
        
        $produk->update($request->all()); 
        return response()->json([
            'message' => 'Produk berhasil diperbarui'
        ]);
    }

    public function destroy($id){
        $produk = Produk::findOrFail($id);
        $produk->delete();
        return response()->json([
            'message' => 'Produk berhasil dihapus'
        ]);
    }
}
