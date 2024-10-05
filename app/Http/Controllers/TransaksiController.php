<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function store(Request $request){
        $data = $request->validate([
            'total' => 'required',
            'snaptoken' => 'required'
        ]);
        Transaksi::create([
            'id_user' => Auth::user()->user_id,
            'total' => $request->total,
            'status' => 'pending',
            'snaptoken' => $request->snaptoken
        ]);
        return response()->json('Transaksi berhasil dibuat');
    }

    public function success($id){
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'status'=>'success'
        ]);
    }

    public function failed($id){
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'status'=>'failed'
        ]);
    }
}
