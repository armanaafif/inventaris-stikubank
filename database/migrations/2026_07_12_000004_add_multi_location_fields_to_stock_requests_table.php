<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->string('item_code')->nullable()->after('request_type');
            $table->foreignId('location_id')->nullable()->after('unit_measure_id')->constrained('locations')->nullOnDelete();
            $table->string('purchase_receipt_path')->nullable()->after('item_status');
        });
    }

    public function down(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('location_id');
            $table->dropColumn(['item_code', 'purchase_receipt_path']);
        });
    }
};
