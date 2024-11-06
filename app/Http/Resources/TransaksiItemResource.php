<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->transaksi_item_id,
            'nama_produk'=>$this->produk->nama_produk,
            'gambar' => $this->produk->gambar,
            'quantity'=>$this->quantity,
            'harga' => $this->produk->harga,
            'gambar' => 'http://127.0.0.1:8000/storage/'.$this->produk->gambar
        ];
    }
}
