<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produk::create([
            "nama_produk" => "Teh Stevia",
            "kategori" => "Teh",
            "deskripsi" => "Teh Stevia",
            "harga" => "50000",
            "stok" => "10",
            'id_user' => 1
        ]);
    }
}
