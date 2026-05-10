<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsultasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens');
            $table->foreignId('dokter_id')->constrained('dokters');
            $table->string('judul');
            $table->text('pesan');
            $table->text('balasan')->nullable();
            $table->enum('status', ['menunggu', 'dijawab', 'ditutup'])->default('menunggu');
            $table->boolean('dibaca_dokter')->default(false);
            $table->boolean('dibaca_pasien')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultasis');
    }
};
