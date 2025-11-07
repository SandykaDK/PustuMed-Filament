<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nama_obat_id')->constrained('nama_obat');
            $table->integer('stok_awal')->nullable();
            $table->integer('jumlah_masuk')->nullable();
            $table->integer('jumlah_keluar')->nullable();
            $table->integer('stok_akhir')->nullable();
            $table->string('lokasi_penyimpanan')->nullable();
            $table->date('tanggal_kadaluwarsa_terdekat')->nullable();
            $table->integer('min_stok')->default(0);
            $table->integer('max_stok')->default(0);
            $table->string('status_stok');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_stok');
    }
};
