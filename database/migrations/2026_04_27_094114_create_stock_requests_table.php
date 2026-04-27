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
        Schema::create('stock_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('consumable_id');
    $table->integer('quantity');
    $table->enum('type', ['IN', 'OUT', 'ADJUST']);
    $table->text('note')->nullable();
    $table->string('status')->default('pending');
    $table->foreignId('user_id');
    $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_requests');
    }
};
