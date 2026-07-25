<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumable_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumable_id')->constrained('consumables')->cascadeOnDelete();
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->unique(['consumable_id', 'location_id']);
        });

        $defaultLocationId = DB::table('locations')->where('name', 'Gudang Utama')->value('id');

        DB::table('consumables')
            ->select('consumables.id')
            ->orderBy('consumables.id')
            ->chunkById(100, function ($consumables) use ($defaultLocationId) {
                foreach ($consumables as $consumable) {
                    $stock = DB::table('consumable_transactions')
                        ->where('consumable_id', $consumable->id)
                        ->selectRaw("
                            COALESCE(SUM(CASE WHEN type = 'IN' THEN quantity ELSE 0 END), 0) -
                            COALESCE(SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END), 0)
                            as total
                        ")
                        ->value('total');

                    DB::table('consumable_stocks')->insert([
                        'consumable_id' => $consumable->id,
                        'location_id' => $defaultLocationId,
                        'quantity' => max(0, (int) $stock),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }, 'consumables.id', 'id');
    }

    public function down(): void
    {
        Schema::dropIfExists('consumable_stocks');
    }
};
