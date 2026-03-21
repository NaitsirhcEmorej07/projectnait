<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('naitnotes_tbl', function (Blueprint $table) {
            $table->string('color')->nullable()->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('naitnotes_tbl', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};