<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->foreignId('from_location_id')
                ->nullable()
                ->after('location_id')
                ->constrained('locations')
                ->nullOnDelete();

            $table->foreignId('to_location_id')
                ->nullable()
                ->after('from_location_id')
                ->constrained('locations')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('to_location_id');
            $table->dropConstrainedForeignId('from_location_id');
        });
    }
};
