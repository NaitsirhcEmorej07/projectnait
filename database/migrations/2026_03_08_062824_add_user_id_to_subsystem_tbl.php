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
        if (!Schema::hasColumn('subsystem_tbl', 'user_id')) {
            Schema::table('subsystem_tbl', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('subsystem_tbl', 'user_id')) {
            Schema::table('subsystem_tbl', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
};
