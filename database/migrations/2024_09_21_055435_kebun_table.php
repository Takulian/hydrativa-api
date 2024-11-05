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
        Schema::create('kebun', function (Blueprint $table) {
            $table->id('kebun_id');
            $table->foreignId('id_user')
                ->constrained('user', 'user_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('id_alat')
                ->constrained('alat', 'alat_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('nama_kebun');
            $table->integer('luas_lahan');
            $table->string('lokasi_kebun');
            $table->string('gambar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebun');
    }
};
