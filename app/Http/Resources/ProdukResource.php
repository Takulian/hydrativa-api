<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $rating = $this->transaksiItem->map(function ($item) {
            return $item->rating ? $item->rating->rating : null;
        })->filter()->values()->all();

        $finalRating = count($rating) > 0 ? round(array_sum($rating) / count($rating), 1) : 0;


        return [
            'id' => $this->produk_id,
            'nama_produk' => $this->nama_produk,  
            'kategori' => $this->kategori,          
            'deskripsi' => $this->deskripsi,            
            'harga' => $this->harga,
            'gambar' => $this->gambar ? url('/storage/' . $this->gambar) : null,
            'final_rating' => $finalRating
        ];
    }
}
