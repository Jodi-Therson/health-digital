<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('antrian_id')->constrained('antrians');
            $table->foreignId('pasien_id')->constrained('pasiens');
            $table->string('kode_invoice', 50)->unique();
            $table->decimal('jumlah', 12, 2);
            $table->enum('metode', ['bpjs', 'transfer', 'tunai', 'qris']);
            $table->enum('status', ['menunggu', 'dibayar', 'gagal', 'dikembalikan'])->default('menunggu');
            $table->string('bukti_bayar')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('dibayar_pada')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
