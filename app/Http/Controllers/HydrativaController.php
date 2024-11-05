<?php

namespace App\Http\Controllers;
use App\Models\Hydrativa;

use Illuminate\Http\Request;

class HydrativaController extends Controller
{

    public function show(){
        $hydra = Hydrativa::all();
        return response()->json($hydra);
    }
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'moisture' => 'required|integer',
            'pH' => 'required|numeric',
            'status' => 'required|string',
        ]);

        // Simpan data ke database
        Hydrativa::create([
            'moisture' => $request->moisture,
            'pH' => $request->pH,
            'status' => $request->status,
        ]);

    
        return response()->json(['message' => 'Data stored successfully'], 200);

    }
}