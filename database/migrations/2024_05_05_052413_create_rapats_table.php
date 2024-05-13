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
        Schema::create('rapats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor_rapat');
            $table->foreignId('user_id')->constrained();
            $table->string('agenda_rapat');
            $table->string('tempat_rapat');
            $table->string('jam_rapat');
            $table->string('hari_rapat');
            $table->string('jam_mulai');
            $table->string('jam_selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapats');
    }
};
