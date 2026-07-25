<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consumables', function (Blueprint $table) {
            $table->string('item_code')->nullable()->unique()->after('id');
            $table->string('purchase_receipt_path')->nullable()->after('status');
            $table->integer('minimum_stock')->nullable()->change();
        });

        DB::table('consumables')
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($consumables) {
                foreach ($consumables as $consumable) {
                    $lastNumber = (int) DB::table('consumables')
                        ->whereNotNull('item_code')
                        ->where('item_code', 'like', 'INV-%')
                        ->selectRaw("MAX(CAST(SUBSTRING(item_code, 5) AS UNSIGNED)) as max_number")
                        ->value('max_number');

                    DB::table('consumables')
                        ->where('id', $consumable->id)
                        ->whereNull('item_code')
                        ->update([
                            'item_code' => 'INV-' . str_pad((string) ($lastNumber + 1), 6, '0', STR_PAD_LEFT),
                            'updated_at' => now(),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('consumables', function (Blueprint $table) {
            $table->dropUnique(['item_code']);
            $table->dropColumn(['item_code', 'purchase_receipt_path']);
            $table->integer('minimum_stock')->default(0)->change();
        });
    }
};
