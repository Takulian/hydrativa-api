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
            'id' => $this->materi_id,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'sumber' => $this->sumber,
            'gambar' => $this->gambar ? url('/storage/' . $this->gambar) : null,
            'waktu' => date_format($this->created_at, "Y/m/d H:i:s")
        ];
    }
}
