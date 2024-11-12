<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Resources\ProdukResource;
use App\Http\Resources\ProdukDetailResource;

class ProdukController extends Controller
{
    public function show(){
        $produk = Produk::all();
        return response()->json(ProdukResource::collection($produk));
    }

    public function detail($id){
        $produk = Produk::with('transaksiItem.rating')->find($id);
        return response()->json(new ProdukDetailResource($produk));
    }

    public function store(Request $request){
        $data = $request->validate([
            'nama_produk' => 'required',
            'kategori' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'stok' => 'required|integer|min:0'
        ]);

        $data = Produk::create([
            'id_user' => Auth::user()->user_id,
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'gambar' => null,
            'stok' => $request->stok
        ]);

        if($request->hasFile('gambar')){
            $file = $request->file('gambar');
            $fileName = $this->quickRandom().'.'.$file->extension();
            $path = $file->storeAs('produk', $fileName, 'public');
            $data->update([
                'gambar' => $path
            ]);
        }

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
            'stok' => 'required|integer|min:0'
        ]);
        if($request->hasFile('gambar')){
            $pathLama = storage_path('app/public/'.$produk->gambar);
            if(File::exists($pathLama)){
                File::delete($pathLama);
                $file = $request->file('gambar');
                $fileName = $this->quickRandom().'.'.$file->extension();
                $path = $file->storeAs('kebun', $fileName, 'public');
                $produk->update([
                    'gambar' => $path
                ]);
            }
        }
        $produk->update([
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok
        ]);
        return response()->json([
            'message' => 'Produk berhasil diperbarui'
        ]);
    }

    public function destroy($id){
        $produk = Produk::findOrFail($id);
        $path = storage_path('app/public/'.$produk->gambar);
        if(File::exists($path)){
            File::delete($path);
        }
        $produk->delete();
        return response()->json([
            'message' => 'Produk berhasil dihapus'
        ]);
    }

    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}
