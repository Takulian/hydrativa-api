<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiItem;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TransaksiItemResource;

class TransaksiItemController extends Controller
{
    public function add(Request $request){
        $user = Auth::user();
        $cari = TransaksiItem::where('id_user', $user->user_id)->where('id_produk', $request->id_produk)->where('id_transaksi', null)->first();
        if(empty($cari) == true){
            TransaksiItem::create([
                'id_user' => $user->user_id,
                'id_produk' => $request->id_produk,
                'israted' => false,
                'quantity' => $request->quantity,
            ]);
            return response()->json('Item telah ditambahkan');
        }
        elseif(empty($cari) == false){
            $cari->update([
                'quantity'=>$cari->quantity + $request->quantity
            ]);
            return response()->json('Item telah ditambahkan');
        }
    }
    public function show(){
        $user = Auth::user();
        $cari = TransaksiItem::where('id_user', $user->user_id)->get();
        return TransaksiItemResource::collection($cari);
    }
}
