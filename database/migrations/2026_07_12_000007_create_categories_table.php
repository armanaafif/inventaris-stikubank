<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->timestamps();
        });

        $categories = [
            ['name' => 'Elektronik', 'code' => 'ELK'],
            ['name' => 'Furniture', 'code' => 'FUR'],
            ['name' => 'ATK', 'code' => 'ATK'],
            ['name' => 'Laboratorium', 'code' => 'LAB'],
            ['name' => 'Jaringan', 'code' => 'NET'],
            ['name' => 'Kebersihan', 'code' => 'KBR'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'code' => $category['code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
