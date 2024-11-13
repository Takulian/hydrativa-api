<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\TransaksiItem;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function store(Request $request){
        $user = Auth::user();

        foreach ($request->id_item as $id_item) {
            $cari = TransaksiItem::findOrFail($id_item);
            if($cari->id_transaksi){
                return response()->json([
                    'message' => 'Item sudah memiliki transaksi.'
                ],405);
            }
        }

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
        $transaksi = Transaksi::create([
            'total' => $request->total,
            'status' => 'pending',
            'id_alamat' => $request->id_alamat,
            'snaptoken' => $snapToken
        ]);
        foreach ($request->id_item as $id_item) {
            $cari = TransaksiItem::findOrFail($id_item);
            $cari->update([
                'id_transaksi' => $transaksi->transaksi_id
            ]);
        }
        return response()->json($snapToken);
    }

    public function berhasil($id){
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'status'=>'success'
        ]);
        return response()->json([
            'message' => 'Transaksi berhasil dibayar'
        ]);
    }

    public function gagal($id){
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'status'=>'failed'
        ]);
        return response()->json([
            'message' => 'Transaksi digagalkan'
        ]);
    }

    public function show(){
        $user = Auth::user();

        $transaksi = Transaksi::whereHas('transaksiItem', function ($query) use ($user) {
            $query->where('id_user', $user->user_id);
        })
        ->with(['transaksiItem.produk'])
        ->get();


        $response = $transaksi->map(function ($transaksi) {
            return [
                'transaksi_id' => $transaksi->transaksi_id,
                'status' => $transaksi->status,
                'resi' => $transaksi->resi,
                'total_harga' => $transaksi->total,
                'transaksi_item' => $transaksi->transaksiItem->map(function ($item) {
                    return [
                        'transaksi_item_id' => $item->transaksi_item_id,
                        'produk_id' => $item->produk->produk_id,
                        'nama_produk' => $item->produk->nama_produk,
                        'israted' => $item->israted,
                        'harga' => $item->produk->harga,
                        'quantity' => $item->quantity,
                        'gambar' => url('/storage/' . $item->produk->gambar)
                    ];
                }),
            ];
        });

        return response()->json($response);
    }

    public function showAdmin(){
        $user = Auth::user();

        $transaksi = Transaksi::where('status', 'success')
        ->orWhere('status', 'delivering')
        ->orWhere('status', 'delivered')
        ->with(['transaksiItem.produk', 'alamat.user'])
        ->get();


        $response = $transaksi->map(function ($transaksi) {
            return [
                'transaksi_id' => $transaksi->transaksi_id,
                'status' => $transaksi->status,
                'total_harga' => $transaksi->total,
                'pembeli' => $transaksi->alamat->user->name,
                'alamat' => [
                    'no_telepon' => $transaksi->alamat->no_telepon,
                    'label_alamat' => $transaksi->alamat->label_alamat,
                    'nama_penerima' => $transaksi->alamat->nama_penerima,
                    'detail' => $transaksi->alamat->detail,
                    'kelurahan' => $transaksi->alamat->kelurahan,
                    'kecamatan' => $transaksi->alamat->kecamatan,
                    'kabupaten' => $transaksi->alamat->kabupaten,
                    'provinsi' => $transaksi->alamat->provinsi,
                    'kodepos' => $transaksi->alamat->kodepos,
                    'catatan_kurir' => $transaksi->alamat->catatan_kurir,

                ],
                'transaksi_item' => $transaksi->transaksiItem->map(function ($item) {
                    return [
                        'transaksi_item_id' => $item->transaksi_item_id,
                        'produk_id' => $item->produk->produk_id,
                        'nama_produk' => $item->produk->nama_produk,
                        'israted' => $item->israted,
                        'harga' => $item->produk->harga,
                        'quantity' => $item->quantity,
                        'gambar' => url('/storage/' . $item->produk->gambar)
                    ];
                }),
            ];
        });

        return response()->json($response);
    }

    public function sampai($id){
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'status'=>'delivered'
        ]);
        return response()->json([
            'message' => 'Transaksi sudah sampai ke tempat tujuan'
        ]);
    }

    public function resi(Request $request, $id){
        $request->validate([
            'resi' => 'required'
        ]);
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'resi' => $request->resi,
            'status' => 'delivering'
        ]);


    }
}
