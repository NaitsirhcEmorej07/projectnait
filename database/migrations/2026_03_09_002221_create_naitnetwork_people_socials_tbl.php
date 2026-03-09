<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('naitnetwork_people_socials_tbl', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('person_id');

            $table->string('platform', 50);
            $table->string('url');
            $table->boolean('is_public')->default(true);

            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('naitnetwork_people_tbl');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('naitnetwork_people_socials_tbl');
    }
};