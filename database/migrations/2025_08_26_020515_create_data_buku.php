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
        Schema::create('data_buku', function (Blueprint $table) {
    $table->id();
    $table->string('kode_buku')->unique();
    $table->string('judul_buku');
    $table->string('penerbit');
    $table->string('pengarang');
    $table->integer('tahun_terbit');
    $table->string('cover_buku')->nullable();
    $table->unsignedBigInteger('kategori_id');
    $table->timestamps();

    $table->foreign('kategori_id')->references('id')->on('kategori_buku')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_buku');
    }
};
