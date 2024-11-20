<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MateriResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'judul' => $data->judul,
            'deskripsi' => $data->deskripsi,
            'gambar' => $data->gambar ? url('/storage/' . $data->gambar) : null
        ];
    }
}
