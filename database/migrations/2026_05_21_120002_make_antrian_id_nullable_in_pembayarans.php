<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Drop the existing foreign key constraint first
            $table->dropForeign(['antrian_id']);
            // Make antrian_id nullable (konsultasi payments don't have an antrian)
            $table->unsignedBigInteger('antrian_id')->nullable()->change();
            // Re-add the foreign key as nullable
            $table->foreign('antrian_id')->references('id')->on('antrians')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropForeign(['antrian_id']);
            $table->unsignedBigInteger('antrian_id')->nullable(false)->change();
            $table->foreign('antrian_id')->references('id')->on('antrians');
        });
    }
};
