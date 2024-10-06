<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function store(Request $request){
        $user = Auth::user();

        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = config('midtrans.isSanitized');
        Config::$is3ds = config('midtrans.is3ds');

        $transaction_details = array(
            'order_id' => rand(),
            'gross_amount' => $request->total
        );
        $customer_details = array(
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email
        );
        $params = array(
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details
        );

        $snapToken = Snap::getSnapToken($params);
        $data = $request->validate([
            'total' => 'required',
        ]);
        Transaksi::create([
            'total' => $request->total,
            'status' => 'pending',
            'snaptoken' => $snapToken
        ]);
        return response()->json($snapToken);
    }

    public function berhasil($id){
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'status'=>'success'
        ]);
        return response()->json('Transaksi berhasil dibayar');
    }

    public function gagal($id){
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'status'=>'failed'
        ]);
        return response()->json('Transaksi digagalkan');
    }

    public function show(){
        $user = Auth::user();
        Transaski::where('id_user', $user->user_id)->get();
    }
}
