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
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('jenis_surat')->constrained();
            $table->integer('nomor');
            $table->string('nomor_surat')->uniqie();
            $table->string('tanggal_surat');
            $table->string('tanggal_diterima');
            $table->string('sifat_surat');
            $table->string('lampiran');
            $table->string('pengirim_surat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masuks');
    }
};
