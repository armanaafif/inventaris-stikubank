<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE stock_requests
            MODIFY quantity INT NULL
        ");

        DB::statement("
            ALTER TABLE stock_requests
            MODIFY type ENUM('IN','OUT','ADJUST') NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE stock_requests
            MODIFY quantity INT NOT NULL
        ");

        DB::statement("
            ALTER TABLE stock_requests
            MODIFY type ENUM('IN','OUT','ADJUST') NOT NULL
        ");
    }
};