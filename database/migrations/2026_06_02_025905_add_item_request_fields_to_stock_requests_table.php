<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {

            $table->string('item_name')->nullable();

            $table->foreignId('unit_measure_id')
                ->nullable()
                ->after('item_name');

            $table->integer('minimum_stock')
                ->nullable();

            $table->integer('initial_stock')
                ->nullable();

            $table->string('condition')
                ->nullable();

            $table->string('item_status')
                ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {

            $table->dropColumn([
                'item_name',
                'minimum_stock',
                'initial_stock',
                'condition',
                'item_status'
            ]);

            $table->dropConstrainedForeignId('unit_measure_id');

        });
    }
};