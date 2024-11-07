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

        return [
            'id' => $this->produk_id,
            'nama' => $this->nama_produk,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'harga' => $this->harga,
            'gambar' => 'http://127.0.0.1:8000/storage/' . $this->gambar,
            'stok' => $this->stok,
            'final_rating' => round(array_sum($rating)/count($rating),1),
            'rating' => $this->transaksiItem->map(function ($item) {
                return $item->rating ? [
                    'nama_user' => $item->rating->user->name,
                    'rating_user' => $item->rating->rating,
                    'komen_user' => $item->rating->comment,
                    'gambar_komen' => $item->rating->gambar,
                    'tanggal' => date_format($item->rating->created_at, "Y/m/d H:i:s")

                ] : null;
            })->filter()->values()->all()
        ];
    }
}
