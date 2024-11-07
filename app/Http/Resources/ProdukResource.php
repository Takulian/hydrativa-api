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
        // Collect all ratings from transaksi items
        $rating = $this->transaksiItem->map(function ($item) {
            return $item->rating ? $item->rating->rating : null;
        })->filter()->values()->all();

        // Calculate the final rating if there are ratings
        $finalRating = count($rating) > 0 ? round(array_sum($rating) / count($rating), 1) : 0;

        // Map the ratings and return user comments
        $ratings = $this->transaksiItem->map(function ($item) {
            return $item->rating ? [
                'nama_user' => $item->rating->user->name,
                'rating_user' => $item->rating->rating,
                'komen_user' => $item->rating->comment,
                'gambar_komen' => $item->rating->gambar,
                'tanggal' => date_format($item->rating->created_at, "Y/m/d H:i:s")
            ] : null;
        })->filter()->values()->all();

        return [
            'id' => $this->produk_id,
            'nama' => $this->nama_produk,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'harga' => $this->harga,
            'gambar' => 'http://127.0.0.1:8000/storage/' . $this->gambar,
            'stok' => $this->stok,
            'final_rating' => $finalRating, // The calculated final rating
            'rating' => $ratings // The individual ratings for each user
        ];
    }
}
