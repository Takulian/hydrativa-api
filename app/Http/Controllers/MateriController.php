<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Resources\MateriResource;

class MateriController extends Controller
{
    public function show(){
        $data = Materi::all();
        return MateriResource::collection($data);

    }

    public function detail($id){
        $data = Materi::findOrFail($id);
        return response()->json([
            'judul' => $data->judul,
            'deskripsi' => $data->deskripsi,
            'gambar' => $data->gambar ? url('/storage/' . $data->gambar) : null
        ]);
    }

    public function store(Request $request){
        $data = $request->validate([
            'judul'=>'required',
            'deskripsi'=>'required',
        ]);

        $data = Kebun::create([
            'judul'=>$request->judul,
            'deskripsi'=>$request->deskripsi,
            'gambar' => null
        ]);
        if($request->hasFile('gambar')){
            $file = $request->file('gambar');
            $fileName = $this->quickRandom().'.'.$file->extension();
            $path = $file->storeAs('materi', $fileName, 'public');
            $data->update([
                'gambar' => $path
            ]);
        }
        return response()->json([
            'message' => 'Materi berhasil ditambah'
        ]);
    }

    public function update(){
        $cari = Materi::findOrFail($id);
        $request->validate([
            'judul'=>'required',
            'deskripsi'=>'required',
        ]);
        if($request->hasFile('gambar')){
            $pathLama = storage_path('app/public/'.$cari->gambar);
            if(File::exists($pathLama)){
                File::delete($pathLama);
                $file = $request->file('gambar');
                $fileName = $this->quickRandom().'.'.$file->extension();
                $path = $file->storeAs('materi', $fileName, 'public');
                $cari->update([
                    'gambar' => $path
                ]);
            }
        }
        $cari->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
        ]);
        return response()->json([
            'message' => 'Materi berhasil di-update'
        ]);
    }

    public function destroy(){
        $cari = Materi::findOrFail($id);
        $path = storage_path('app/public/'.$cari->gambar);
        if(File::exists($path)){
            File::delete($path);
        }
        $cari->delete();
        return response()->json([
            'message' => 'Materi berhasil dihapus'
        ]);
    }

    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}
