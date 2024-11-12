<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\TransaksiItem;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\RatingResource;
use App\Http\Resources\TransaksiItemResource;

class RatingController extends Controller
{
    public function unrated(){
        $user = Auth::user();
        $transaksi = Transaksi::where('status', 'delivered')->whereHas('transaksiItem', function ($query) use ($user) {
            $query->where('id_user', $user->user_id);
        })
        ->with(['transaksiItem.produk', 'transaksiItem.rating'])
        ->get();

        $response = $transaksi->map(function ($transaksi) {
            return [
                'transaksi_id' => $transaksi->transaksi_id,
                'status' => $transaksi->status,
                'total_harga' => $transaksi->total,
                'produk' => $transaksi->transaksiItem->map(function ($item) {
                    $itemData = [
                        'transaksi_item_id' => $item->transaksi_item_id,
                        'israted' => $item->israted,
                        'produk_id' => $item->produk->produk_id,
                        'produk_name' => $item->produk->nama_produk,
                        'harga' => $item->produk->harga,
                        'quantity' => $item->quantity,
                        'gambar' => url('/storage/' . $item->produk->gambar),
                    ];

                    if ($item->israted == 1 && $item->rating) {
                        $itemData['rating'] = [
                            'rating_value' => $item->rating->rating,
                            'comment' => $item->rating->comment,
                            'gambar' => url('/storage/' . $item->rating->gambar)
                        ];
                    }

                    return $itemData;
                }),
            ];
        });
        return response()->json($response);
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
            if ($keranjang->isRated == true) {
                return response()->json([
                    'message' => 'Sudah dirating.'
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
    }

}
