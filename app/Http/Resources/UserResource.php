<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if(!$this->email_verified_at){
            $verif = false;
        }else {
            $verif = true;
        }
        return [
            'username' => $this->username,
            'email' => $this->email,
            'telp' => $this->telp,
            'verification_email' => $verif,
            'name' => $this->name,
            'telepon' => $this->telp,
            'jenis_kelamin' => $this->jenis_kelamin,
            'gambar' => $this->gambar ? url('/storage/'. $this->gambar) : null
        ];
    }
}
