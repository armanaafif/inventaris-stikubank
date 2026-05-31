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
        // Hapus foreign key constraint yang lama
        Schema::table('consumable_transactions', function (Blueprint $table) {
            $table->dropForeign(['consumable_id']);
        });
        
        // Tambahkan foreign key baru dengan cascade delete
        Schema::table('consumable_transactions', function (Blueprint $table) {
            $table->foreign('consumable_id')
                  ->references('id')
                  ->on('consumables')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consumable_transactions', function (Blueprint $table) {
            $table->dropForeign(['consumable_id']);
        });
        
        Schema::table('consumable_transactions', function (Blueprint $table) {
            $table->foreign('consumable_id')
                  ->references('id')
                  ->on('consumables');
        });
    }
};