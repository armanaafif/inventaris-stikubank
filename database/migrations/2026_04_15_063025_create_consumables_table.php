<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run migrations
     */
    public function up(): void
    {
        Schema::create('consumables', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Informasi Barang
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            /*
            |--------------------------------------------------------------------------
            | Satuan Barang
            |--------------------------------------------------------------------------
            */

            $table->foreignId('unit_measure_id')
                ->constrained('unit_measures');

            /*
            |--------------------------------------------------------------------------
            | Minimum Stock
            |--------------------------------------------------------------------------
            */

            $table->integer('minimum_stock')
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Kondisi Barang
            |--------------------------------------------------------------------------
            */

            $table->enum('condition', [

                'BARU',
                'BEKAS',
                'LAYAK',
                'RUSAK'

            ])->default('BARU');

            /*
            |--------------------------------------------------------------------------
            | Status Barang
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [

                'AKTIF',
                'NONAKTIF'

            ])->default('AKTIF');

            $table->timestamps();

        });
    }

    /**
     * Reverse migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('consumables');
    }
};