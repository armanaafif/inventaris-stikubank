<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consumable_transactions', function (Blueprint $table) {
            $table->foreignId('consumable_stock_id')
                ->nullable()
                ->after('consumable_id')
                ->constrained('consumable_stocks')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('consumable_transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('consumable_stock_id');
        });
    }
};
