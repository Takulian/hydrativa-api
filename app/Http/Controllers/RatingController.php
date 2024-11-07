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
        return response()->json(TransaksiItemResource::collection($data));
    }

    public function rating(Request $request, $id){
        $user = Auth::user();
        $data = $request->validate([
            'rating' => "required"
        ]);
        $keranjang = TransaksiItem::with('transaksi')->find($id);
        if(!$keranjang->id_transaksi){
            return response()->json([
                'message' => 'Produk belum dibeli'
            ], 405);
        }else{
            if($keranjang->transaksi->status == 'success'){
                Rating::create([
                    'id_user'=>$user->user_id,
                    'id_transaksi_item'=> $id,
                    'rating'=>$request->rating,
                    'comment'=>$request->comment,
                    'gambar'=>$request->gambar
                ]);
                $keranjang->update([
                    'israted' => true
                ]);
                return response()->json([
                    'message' => 'Rating berhasil ditambahkan'
                ]);
            }
            else{
                return response()->json([
                    'message' => 'Transaksi belum dibayar.'
                ], 405);
            }
            }
        }

    public function rate($id){
        $data = TransaksiItem::where('id_produk', $id)->where('israted', true)->get();
        return response()->json(RatingResource::collection($data));
    }
}
