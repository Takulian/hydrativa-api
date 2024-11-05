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
        Schema::create('alamat', function (Blueprint $table) {
            $table->id('alamat_id');
            $table->foreignId('id_user')
                ->constrained('user', 'user_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('label_alamat');
            $table->string('nama_penerima');
            $table->string('alamat');
            $table->string('isMain');
            $table->string('catatan_kurir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
