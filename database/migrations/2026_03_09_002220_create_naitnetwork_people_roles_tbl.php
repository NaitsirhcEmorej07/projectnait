<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('naitnetwork_people_roles_tbl', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('role_id');

            $table->timestamps();

            $table->unique(['person_id', 'role_id']);

            $table->foreign('person_id')->references('id')->on('naitnetwork_people_tbl');
            $table->foreign('role_id')->references('id')->on('naitnetwork_roles_tbl');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('naitnetwork_people_roles_tbl');
    }
};