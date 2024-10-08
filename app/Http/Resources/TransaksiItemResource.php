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
            'quantity'=>$this->quantity,
            'harga' => $this->produk->harga
        ];
    }
}
