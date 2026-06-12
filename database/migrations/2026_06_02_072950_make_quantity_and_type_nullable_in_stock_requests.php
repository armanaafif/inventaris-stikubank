<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->change();
            $table->enum('type', ['IN', 'OUT', 'ADJUST'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->integer('quantity')->nullable(false)->change();
            $table->enum('type', ['IN', 'OUT', 'ADJUST'])->nullable(false)->change();
        });
    }
};
