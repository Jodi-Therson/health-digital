<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pembayarans MODIFY COLUMN status ENUM('menunggu', 'menunggu_verifikasi', 'dibayar', 'ditolak', 'gagal', 'dikembalikan') DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pembayarans MODIFY COLUMN status ENUM('menunggu', 'dibayar', 'gagal', 'dikembalikan') DEFAULT 'menunggu'");
    }
};
