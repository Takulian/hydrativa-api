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
                "detail" => "Jl. Contoh No. 123, RT 01/RW 02",
                "kelurahan" => "Babakan Tengah",
                "kecamatan" => "Babakan",
                "kabupaten" => "Kota Bogor",
                "provinsi" => "Jawa Barat",
                "kodepos" => "12345",
                "isPrimary" => 0,
                "catatan_kurir" => "Harap hubungi sebelum tiba",
                "id_user" => 1     
        ]);
    }
}
