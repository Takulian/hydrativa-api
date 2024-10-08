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
        return [
            'id' => $this->produk_id,
            'nama' => $this->nama_produk,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'harga' => $this->harga,
            'gambar'=> $this->gambar,
        ];
    }
}
