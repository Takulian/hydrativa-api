<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoriResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'moisture' => $this->moisture,
            'pH' => $this->pH,
            'status' => $this->status,
            'waktu' => date_format($this->created_at, "Y/m/d H:i:s")
        ];

    }
}
