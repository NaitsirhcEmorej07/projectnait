<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_codes', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique(); // actual code
            $table->boolean('is_used')->default(false); // if already used
            $table->timestamp('used_at')->nullable(); // when used

            $table->unsignedBigInteger('used_by')->nullable(); // user who used it

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_codes');
    }
};