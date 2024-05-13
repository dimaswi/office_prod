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
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('nomor')->unique();
            $table->foreignUuid('jenis_surat')->constrained();
            $table->string('nomor_surat')->unique();
            $table->string('tanggal_surat');
            $table->string('perihal_surat');
            $table->string('sifat_surat');
            $table->string('penerima_surat');
            $table->longText('isi_surat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluars');
    }
};
