<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KebunResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->kebun_id,
            'nama_kebun' => $this->nama_kebun,
            'luas_lahan' => $this->luas_lahan,
            'lokasi_kebun' => $this->lokasi_kebun,
            'keadaan_tanah' => $this->keadaan_tanah,
            'status_penyiraman' => $this->status_penyiraman,
        ];
    }
}
