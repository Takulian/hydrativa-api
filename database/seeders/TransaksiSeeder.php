<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaksi::create([
            'transaksi_id' => 1,
            'total' => 150000,
            'status' => 'pending',
            'resi' => null,
            'id_alamat' => 1, // pastikan alamat dengan ID 1 sudah ada
            'snaptoken' => 'dummy-snaptoken-123'
        ]);
    }
}
