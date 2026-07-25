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
            $table->foreignId('category_id')
                ->nullable()
                ->after('item_code')
                ->constrained('categories')
                ->nullOnDelete();
        });

        Schema::table('stock_requests', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('item_name')
                ->constrained('categories')
                ->nullOnDelete();
        });

        $defaultCategoryId = DB::table('categories')->where('code', 'ATK')->value('id');

        DB::table('consumables')->whereNull('category_id')->update([
            'category_id' => $defaultCategoryId,
        ]);

        DB::table('stock_requests')->whereNull('category_id')->update([
            'category_id' => $defaultCategoryId,
        ]);
    }

    public function down(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::table('consumables', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
