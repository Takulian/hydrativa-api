<?php

namespace App\Http\Controllers;
use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{

    public function show(){
        $hydra = Alat::with('kebun')->get();
        return $hydra;
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_alat' => 'required|string',
            'moisture' => 'required|integer',
            'pH' => 'required|numeric',
            'status' => 'required|string',
        ]);
        Alat::create([
            'kode_alat' => $request->kode_alat,
            'moisture' => $request->moisture,
            'pH' => $request->pH,
            'status' => $request->status,
        ]);
        return response()->json(['message' => 'Alat berhasil ditambahkan'], 200);
    }

    public function updateStatus(Request $request,String $id){
        $data = $request->validate([
            'moisture'=>'required',
            'pH'=>'required',
            'status' => 'required'
        ]);
        $alat = alat::findOrFail($id);
        $alat->update($request->all());
        return response()->json([
            'message' => 'Status berhasil diperbarui'
        ]);
    }
}
