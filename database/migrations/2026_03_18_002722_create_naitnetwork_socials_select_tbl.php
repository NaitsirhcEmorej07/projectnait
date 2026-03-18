<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('naitnetwork_socials_select_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Facebook
            $table->string('code')->unique(); // facebook
            $table->string('icon')->nullable(); // pi pi-facebook
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('naitnetwork_socials_select_tbl');
    }
};