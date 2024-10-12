<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use App\Models\TransaksiItem;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\RatingResource;
use App\Http\Resources\TransaksiItemResource;

class RatingController extends Controller
{
    public function unrated(){
        $user = Auth::user();
        $data = TransaksiItem::where('id_user', $user->user_id)->where('israted', false)->whereNotNull('id_transaksi')->get();
        return response()->json($data);
        return TransaksiItemResource::collection($data);
    }

    public function rating(Request $request, $id){
        $user = Auth::user();
        // return response()->json($request);
        $data = $request->validate([
            'rating' => "required"
        ]);
        Rating::create([
            'id_user'=>$user->user_id,
            'id_transaksi_item'=> $id,
            'rating'=>$request->rating,
            'comment'=>$request->comment,
            'gambar'=>$request->gambar
        ]);
        $keranjang = TransaksiItem::findOrFail($id);
        $keranjang->update([
            'israted' => true
        ]);
        return response()->json('Rating berhasil ditambahkan');
    }

    public function rate($id){
        $data = TransaksiItem::where('id_produk', $id)->where('israted', true)->get();
        return RatingResource::collection($data);
    }
}
