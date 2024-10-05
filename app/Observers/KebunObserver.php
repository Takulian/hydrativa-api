<?php

namespace App\Observers;

use App\Models\Kebun;
use App\Models\Histori;

class KebunObserver
{

    public function updated(Kebun $kebun): void
    {
        if ($kebun->isDirty('keadaan_tanah') || $kebun->isDirty('status_penyiraman')){
            Histori::create([
                'id_kebun' => $kebun->kebun_id,
                'keadaan_tanah' => $kebun->keadaan_tanah,
                'status_penyiraman' => $kebun->status_penyiraman
            ]);
        }
    }

}
