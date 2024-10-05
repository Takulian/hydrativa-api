<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiItem;
use Illuminate\Support\Facades\Auth;

class TransaksiItemController extends Controller
{
    public function add(Request $request){
        $user = Auth::user();
        $produk = Produk::findOrFail($request->id_produk);
        $subtotal = $request->quantity * $produk->harga;
        TransaksiItem::create([
            'id_produk' => $request->id_produk,
            'quantity' => $request->quantity,
            'subtotal' => $subtotal
        ]);
        return response()->json('Item telah ditambahkan');
    }
}
