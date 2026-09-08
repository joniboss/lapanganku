<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_reservasi')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lapangan_id')->constrained('lapangans')->onDelete('cascade');
            $table->date('tanggal_main');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('durasi_jam');
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('diskon_persen', 5, 2)->default(0);
            $table->decimal('total_harga', 10, 2);
            $table->enum('status_reservasi', ['pending','dikonfirmasi','selesai','dibatalkan'])->default('pending');
            $table->enum('status_pembayaran', ['belum_bayar','menunggu_verifikasi','lunas'])->default('belum_bayar');
            $table->string('metode_pembayaran')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->datetime('tanggal_bayar')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};