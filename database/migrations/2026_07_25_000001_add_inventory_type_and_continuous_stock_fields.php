<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('consumables', 'inventory_type')) {
            Schema::table('consumables', function (Blueprint $table) {
                $table->enum('inventory_type', ['UNIT', 'CONTINUOUS'])->default('UNIT')->after('name');
            });
        }

        Schema::table('consumable_stocks', function (Blueprint $table) {
            if (!Schema::hasColumn('consumable_stocks', 'batch_code')) {
                $table->string('batch_code')->nullable()->after('location_id');
            }
            if (!Schema::hasColumn('consumable_stocks', 'brand')) {
                $table->string('brand')->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('consumable_stocks', 'model')) {
                $table->string('model')->nullable()->after('brand');
            }
            if (!Schema::hasColumn('consumable_stocks', 'serial_number')) {
                $table->string('serial_number')->nullable()->after('model');
            }
            if (!Schema::hasColumn('consumable_stocks', 'specification')) {
                $table->text('specification')->nullable()->after('serial_number');
            }
            if (!Schema::hasColumn('consumable_stocks', 'condition')) {
                $table->enum('condition', ['BARU', 'BEKAS', 'LAYAK', 'RUSAK'])->nullable()->after('specification');
            }
            if (!Schema::hasColumn('consumable_stocks', 'inventory_type')) {
                $table->enum('inventory_type', ['UNIT', 'CONTINUOUS'])->default('UNIT')->after('condition');
            }
            if (!Schema::hasColumn('consumable_stocks', 'roll_count')) {
                $table->integer('roll_count')->default(0)->after('inventory_type');
            }
            if (!Schema::hasColumn('consumable_stocks', 'initial_length')) {
                $table->decimal('initial_length', 12, 2)->nullable()->after('roll_count');
            }
            if (!Schema::hasColumn('consumable_stocks', 'remaining_length')) {
                $table->decimal('remaining_length', 12, 2)->nullable()->after('initial_length');
            }
            if (!Schema::hasColumn('consumable_stocks', 'length_unit')) {
                $table->string('length_unit')->nullable()->after('remaining_length');
            }
        });

        Schema::table('consumable_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('consumable_transactions', 'length_amount')) {
                $table->decimal('length_amount', 12, 2)->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('consumable_transactions', 'length_unit')) {
                $table->string('length_unit')->nullable()->after('length_amount');
            }
        });

        Schema::table('stock_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_requests', 'inventory_type')) {
                $table->enum('inventory_type', ['UNIT', 'CONTINUOUS'])->default('UNIT')->after('item_name');
            }
            if (!Schema::hasColumn('stock_requests', 'roll_count')) {
                $table->integer('roll_count')->nullable()->after('initial_stock');
            }
            if (!Schema::hasColumn('stock_requests', 'roll_length')) {
                $table->decimal('roll_length', 12, 2)->nullable()->after('roll_count');
            }
            if (!Schema::hasColumn('stock_requests', 'length_amount')) {
                $table->decimal('length_amount', 12, 2)->nullable()->after('roll_length');
            }
            if (!Schema::hasColumn('stock_requests', 'length_unit')) {
                $table->string('length_unit')->nullable()->after('length_amount');
            }
        });

        DB::table('consumable_stocks')
            ->update([
                'inventory_type' => 'UNIT',
                'roll_count' => 0,
            ]);
    }

    public function down(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->dropColumn(['inventory_type', 'roll_count', 'roll_length', 'length_amount', 'length_unit']);
        });

        Schema::table('consumable_transactions', function (Blueprint $table) {
            $table->dropColumn(['length_amount', 'length_unit']);
        });

        Schema::table('consumable_stocks', function (Blueprint $table) {
            $table->dropColumn([
                'batch_code',
                'brand',
                'model',
                'serial_number',
                'specification',
                'condition',
                'inventory_type',
                'roll_count',
                'initial_length',
                'remaining_length',
                'length_unit',
            ]);
            $table->unique(['consumable_id', 'location_id']);
        });

        Schema::table('consumables', function (Blueprint $table) {
            $table->dropColumn('inventory_type');
        });
    }
};
