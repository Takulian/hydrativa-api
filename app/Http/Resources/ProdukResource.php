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
                'profile_picture' => 'http://127.0.0.1:8000/storage/' . $item->rating->user->gambar,
                'nama_user' => $item->rating->user->name,
                'rating_user' => $item->rating->rating,
                'komen_user' => $item->rating->comment,
                'gambar_komen' => $item->rating->gambar,
                'tanggal' => date_format($item->rating->created_at, "Y/m/d H:i:s")
            ] : null;
        })->filter()->values()->all();

        // Calculate total number of ratings
        $jumlahRating = count($rating);

        // Count ratings with comments
        $jumlahRatingDenganKomen = $this->transaksiItem->filter(function ($item) {
            return $item->rating && !empty($item->rating->comment);
        })->count();

        // Calculate the number of users who rated 1, 2, 3, 4, 5 and their percentages
        $ratingCounts = array_count_values($rating);
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $ratingCounts[$i] ?? 0;
            $percentage = $jumlahRating > 0 ? round(($count / $jumlahRating) * 100, 2) : 0;
            $ratingDistribution[$i] = [
                'jumlah' => $count,
                'persentase' => $percentage
            ];
        }

        // Calculate the percentage of satisfied customers (rating 4 or 5)
        $jumlahPuas = ($ratingCounts[4] ?? 0) + ($ratingCounts[5] ?? 0);
        $persentasePuas = $jumlahRating > 0 ? round(($jumlahPuas / $jumlahRating) * 100, 2) : 0;

        // Get the total number of items sold
        $jumlahTerjual = $this->transaksiItem->filter(function ($item) {
            return $item->transaksi && $item->transaksi->status === "success";
        })->count();
        
        return [
            'id' => $this->produk_id,
            'nama' => $this->nama_produk,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'harga' => $this->harga,
            'gambar' => 'http://127.0.0.1:8000/storage/' . $this->gambar,
            'stok' => $this->stok,
            'final_rating' => $finalRating, // The calculated final rating
            'jumlah_rating' => $jumlahRating, // Total number of ratings
            'jumlah_ulasan' => $jumlahRatingDenganKomen, // Number of ratings with comments
            'rating' => $ratings, // The individual ratings for each user
            'distribusi_rating' => $ratingDistribution, // Distribution of ratings and percentages
            'persentase_puas' => $persentasePuas, // Percentage of satisfied customers
            'jumlah_terjual' => $jumlahTerjual // Total number of items sold
        ];
    }
}
