<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use App\Http\Resources\MateriResource;

class MateriController extends Controller
{
    public function show(){
        $data = Materi::all();
        return response()->json(MateriResource::collection($data));
    }

    public function detail($id){
        $data = Materi::findOrFail($id);
        return response()->json([
            'judul' => $data->judul,
            'deskripsi' => $data->deskripsi,
            'sumber' => $data->sumber,
            'gambar' => $data->gambar ? url('/storage/' . $data->gambar) : null,
            'waktu' => date_format($data->created_at, "Y/m/d H:i:s")
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'sumber' => 'required'
        ]);
        $data = Materi::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'sumber' => $request->sumber,
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

    public function edit(Request $request, $id){
        $materi = Materi::findOrFail($id);
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'sumber' => 'required'
        ]);
        if($request->hasFile('gambar')){
            $pathLama = storage_path('app/public/'.$materi->gambar);
            if(File::exists($pathLama)){
                File::delete($pathLama);
                $file = $request->file('gambar');
                $fileName = $this->quickRandom().'.'.$file->extension();
                $path = $file->storeAs('materi', $fileName, 'public');
                $materi->update([
                    'gambar' => $path
                ]);
            }
        }
        $materi->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'sumber' => $request->sumber,
        ]);
        return response()->json([
            'message' => 'Materi berhasil diperbarui'
        ]);
    }

    public function destroy($id){
        $materi = Materi::findOrFail($id);
        $path = storage_path('app/public/'.$materi->gambar);
        if(File::exists($path)){
            File::delete($path);
        }
        $Materi->delete();
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
