<?php

namespace App\Imports;

use App\Models\Produk;
use Maatwebsite\Excel\Facades\Excel;

class ProdukImport
{
    public function import($file)
    {
        $data = Excel::toArray([], $file);  // Read the file as an array

        foreach ($data[0] as $row) {  // $data[0] contains the rows of the first sheet
            Produk::create([
                'nama_produk' => $row[0],
                'kategori' => $row[1],
                'deskripsi' => $row[2],
                'harga' => $row[3],
                'stok' => $row[4],
            ]);
        }
    }
}
