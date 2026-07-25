<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consumables', function (Blueprint $table) {
            $table->string('item_number', 20)->nullable()->after('item_code');
            $table->string('brand')->nullable()->after('name');
            $table->string('model')->nullable()->after('brand');
            $table->string('serial_number')->nullable()->after('model');
            $table->text('specification')->nullable()->after('serial_number');
            $table->text('description')->nullable()->after('specification');
        });

        Schema::table('stock_requests', function (Blueprint $table) {
            $table->string('item_number', 20)->nullable()->after('item_code');
            $table->string('brand')->nullable()->after('item_name');
            $table->string('model')->nullable()->after('brand');
            $table->string('serial_number')->nullable()->after('model');
            $table->text('specification')->nullable()->after('serial_number');
            $table->text('description')->nullable()->after('specification');
        });
    }

    public function down(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->dropColumn(['item_number', 'brand', 'model', 'serial_number', 'specification', 'description']);
        });

        Schema::table('consumables', function (Blueprint $table) {
            $table->dropColumn(['item_number', 'brand', 'model', 'serial_number', 'specification', 'description']);
        });
    }
};
