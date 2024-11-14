<?php

namespace Database\Seeders;

use App\Models\Alamat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlamatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Alamat::create([
                "alamat_id" => 1,
                "label_alamat" => "Rumah",
                "nama_penerima" => "John Doe",
                "no_telepon" => "081234567890",
                "detail" => "Jl. Kumbang No. 14",
                "kelurahan" => "BABAKAN",
                "kecamatan" => "BOGOR TENGAH",
                "kabupaten" => "KOTA BOGOR",
                "provinsi" => "JAWA BARAT",
                "kodepos" => "12345",
                "isPrimary" => 1,
                "catatan_kurir" => "Harap hubungi sebelum tiba",
                "id_user" => 1     
        ]);
    }
}
