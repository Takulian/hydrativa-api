<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_item', function (Blueprint $table) {
            $table->id('transaksi_item_id');
            $table->foreignId('id_transaksi')
                ->nullable()
                ->constrained('transaksi', 'transaksi_id')
                ->noActionOnUpdate()
                ->noActionOnDelete();
            $table->foreignId('id_produk')
                ->constrained('produk', 'produk_id')
                ->noActionOnUpdate()
                ->noActionOnDelete();
            $table->foreignId('id_user')
                ->constrained('user', 'user_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->integer('quantity');
            $table->boolean('israted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_item');
    }
};
