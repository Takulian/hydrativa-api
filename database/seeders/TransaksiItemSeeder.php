<?php

namespace Database\Seeders;

use App\Models\TransaksiItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransaksiItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransaksiItem::create([
            'transaksi_item_id' => 1,
            'id_transaksi' => 1, // pastikan transaksi_id = 1 sudah ada
            'id_produk' => 1,    // pastikan produk_id = 1 sudah ada
            'id_user' => 1,      // pastikan user_id = 1 sudah ada
            'quantity' => 2,
            'israted' => false
        ]);
    }
}
