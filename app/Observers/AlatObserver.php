<?php

namespace App\Observers;

use App\Models\Alat;
use App\Models\Histori;

class AlatObserver
{

    public function updated(Alat $alat): void
    {
        if ($alat->isDirty('moisture') || $alat->isDirty('pH') || $alat->isDirty('status')){
            Histori::create([
                'id_kebun' => $alat->kebun->kebun_id,
                'moisture' => $alat->moisture,
                'pH' => $alat->pH,
                'status' => $alat->status
            ]);
        }
    }
}
