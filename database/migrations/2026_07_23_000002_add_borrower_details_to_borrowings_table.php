<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->string('borrower_phone', 50)->after('borrower_name');
            $table->string('borrower_unit')->nullable()->after('borrower_phone');
            $table->text('purpose')->after('borrower_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['borrower_phone', 'borrower_unit', 'purpose']);
        });
    }
};
